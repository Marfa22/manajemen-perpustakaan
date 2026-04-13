<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SmartSearchService
{
    /** @var array<string, bool> */
    protected array $stopwords = [
        'dan' => true,
        'yang' => true,
        'di' => true,
        'ke' => true,
        'dari' => true,
        'untuk' => true,
        'pada' => true,
        'dengan' => true,
        'atau' => true,
        'ini' => true,
        'itu' => true,
        'ada' => true,
        'tidak' => true,
        'sudah' => true,
        'belum' => true,
        'barang' => true,
        'temuan' => true,
    ];

    /** @var array<string, string> */
    protected array $synonymMap = [
        'hp' => 'ponsel',
        'handphone' => 'ponsel',
        'smartphone' => 'ponsel',
        'phone' => 'ponsel',
        'ponsel' => 'ponsel',
        'laptop' => 'laptop',
        'notebook' => 'laptop',
        'dok' => 'dokumen',
        'dokumen' => 'dokumen',
        'berkas' => 'dokumen',
        'arsip' => 'dokumen',
        'file' => 'dokumen',
        'merek' => 'merek',
        'merk' => 'merek',
        'brand' => 'merek',
        'lokasi' => 'lokasi',
        'tempat' => 'lokasi',
        'area' => 'lokasi',
        'ruangan' => 'lokasi',
        'serial' => 'serial',
        'sn' => 'serial',
        'sku' => 'kode',
        'kode' => 'kode',
        'kunci' => 'kunci',
        'key' => 'kunci',
        'dompet' => 'dompet',
        'wallet' => 'dompet',
        'helm' => 'helm',
        'helmet' => 'helm',
        'tas' => 'tas',
        'bag' => 'tas',
        'rusak' => 'rusak',
        'cacat' => 'rusak',
        'broken' => 'rusak',
        'baik' => 'baik',
        'bagus' => 'baik',
        'normal' => 'baik',
    ];

    /**
     * @param Collection<int, mixed> $items
     * @return Collection<int, mixed>
     */
    public function search(
        string $query,
        Collection $items,
        ?callable $textResolver = null,
        float $minScore = 0.02
    ): Collection
    {
        $queryTokens = $this->tokenize($query);
        if (empty($queryTokens) || $items->isEmpty()) {
            return collect();
        }

        $queryNormalized = $this->normalizeForDistance($query);

        $documents = [];
        $documentFrequency = [];

        foreach ($items as $index => $item) {
            $docText = $this->resolveDocumentText($item, $textResolver);
            $tokens = $this->tokenize($docText);

            if (empty($tokens)) {
                $tokens = $this->tokenize((string) ($item->name ?? ''));
            }

            if (empty($tokens)) {
                continue;
            }

            $termCounts = array_count_values($tokens);
            $documents[$index] = [
                'item' => $item,
                'term_counts' => $termCounts,
                'term_total' => max(array_sum($termCounts), 1),
                'normalized_text' => $this->normalizeForDistance($docText),
                'tokens' => array_keys($termCounts),
            ];

            foreach (array_keys($termCounts) as $term) {
                $documentFrequency[$term] = ($documentFrequency[$term] ?? 0) + 1;
            }
        }

        if (empty($documents)) {
            return collect();
        }

        $docCount = count($documents);

        $queryTermCounts = array_count_values($queryTokens);
        $queryTermTotal = max(array_sum($queryTermCounts), 1);

        $idf = [];
        foreach (array_keys($queryTermCounts) as $term) {
            $df = $documentFrequency[$term] ?? 0;
            $idf[$term] = log((($docCount + 1) / ($df + 1))) + 1;
        }

        $queryVector = [];
        $queryNormSq = 0.0;

        foreach ($queryTermCounts as $term => $count) {
            $tf = $count / $queryTermTotal;
            $weight = $tf * ($idf[$term] ?? 1.0);
            $queryVector[$term] = $weight;
            $queryNormSq += $weight * $weight;
        }

        $queryNorm = sqrt($queryNormSq);

        $scored = [];

        foreach ($documents as $document) {
            $item = $document['item'];
            $termCounts = $document['term_counts'];
            $termTotal = $document['term_total'];
            $documentTokens = $document['tokens'];
            $documentNormalizedText = $document['normalized_text'];

            $dotProduct = 0.0;
            $docNormSq = 0.0;

            foreach ($queryVector as $term => $queryWeight) {
                $termCount = $termCounts[$term] ?? 0;
                if ($termCount <= 0) {
                    continue;
                }

                $tf = $termCount / $termTotal;
                $docWeight = $tf * ($idf[$term] ?? 1.0);

                $dotProduct += $queryWeight * $docWeight;
                $docNormSq += $docWeight * $docWeight;
            }

            $tfidfScore = 0.0;
            if ($dotProduct > 0.0 && $docNormSq > 0.0 && $queryNorm > 0.0) {
                $tfidfScore = $dotProduct / (sqrt($docNormSq) * $queryNorm);
            }

            $levenshteinScore = $this->computeLevenshteinScore(
                $queryNormalized,
                $queryTokens,
                $documentNormalizedText,
                $documentTokens
            );

            $finalScore = (0.75 * $tfidfScore) + (0.25 * $levenshteinScore);

            if ($finalScore < $minScore) {
                continue;
            }

            $item->setAttribute('search_score', round($finalScore, 6));
            $scored[] = $item;
        }

        usort($scored, function ($a, $b) {
            $scoreA = (float) ($a->search_score ?? 0);
            $scoreB = (float) ($b->search_score ?? 0);

            if ($scoreA !== $scoreB) {
                return $scoreB <=> $scoreA;
            }

            return $this->resolveItemTimestamp($b) <=> $this->resolveItemTimestamp($a);
        });

        return collect($scored);
    }

    /**
     * @param array<string, mixed> $query
     */
    public function paginate(
        Collection $items,
        int $perPage,
        array $query = [],
        string $pageName = 'page'
    ): LengthAwarePaginator {
        $safePerPage = max($perPage, 1);
        $currentPage = max(1, LengthAwarePaginator::resolveCurrentPage($pageName));
        $total = $items->count();
        $currentItems = $items
            ->slice(($currentPage - 1) * $safePerPage, $safePerPage)
            ->values();

        return new LengthAwarePaginator(
            $currentItems,
            $total,
            $safePerPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $query,
                'pageName' => $pageName,
            ]
        );
    }

    protected function resolveDocumentText(mixed $item, ?callable $textResolver): string
    {
        if ($textResolver !== null) {
            $resolved = $textResolver($item);
            return is_string($resolved) ? $resolved : '';
        }

        return $this->buildDocumentText($item);
    }

    protected function buildDocumentText(mixed $item): string
    {
        return implode(' ', [
            (string) ($item->name ?? ''),
            (string) ($item->description ?? ''),
            (string) ($item->found_location ?? ''),
            (string) ($item->sku ?? ''),
            (string) optional($item->category)->name,
        ]);
    }

    /**
     * @return array<int, string>
     */
    protected function tokenize(string $text): array
    {
        $normalized = $this->normalizeText($text);
        if ($normalized === '') {
            return [];
        }

        $tokens = preg_split('/\s+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY);
        if ($tokens === false) {
            return [];
        }

        $result = [];
        foreach ($tokens as $token) {
            if (isset($this->stopwords[$token])) {
                continue;
            }

            if (mb_strlen($token) < 2) {
                continue;
            }

            $result[] = $this->canonicalizeToken($token);
        }

        return $result;
    }

    protected function normalizeText(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text) ?? '';
        $text = preg_replace('/\s+/u', ' ', $text) ?? '';

        return trim($text);
    }

    protected function normalizeForDistance(string $text): string
    {
        $normalized = $this->normalizeText($text);

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalized);
            if ($converted !== false) {
                $normalized = strtolower($converted);
            }
        }

        $normalized = preg_replace('/[^a-z0-9\s]+/', ' ', $normalized) ?? '';
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?? '';

        return trim($normalized);
    }

    /**
     * @param array<int, string> $queryTokens
     */
    protected function computeLevenshteinScore(
        string $queryNormalized,
        array $queryTokens,
        string $documentNormalizedText,
        array $documentTokens
    ): float {
        $wholeScore = $this->distanceSimilarity($queryNormalized, $documentNormalizedText);
        $tokenScore = $this->bestTokenSimilarity($queryTokens, $documentTokens);
        $containsScore = ($queryNormalized !== '' && str_contains($documentNormalizedText, $queryNormalized)) ? 1.0 : 0.0;

        return max($wholeScore, $tokenScore, $containsScore);
    }

    protected function distanceSimilarity(string $left, string $right): float
    {
        if ($left === '' || $right === '') {
            return 0.0;
        }

        $distance = levenshtein($left, $right);
        $maxLength = max(strlen($left), strlen($right));

        if ($maxLength <= 0) {
            return 0.0;
        }

        return max(0.0, 1.0 - ($distance / $maxLength));
    }

    /**
     * @param array<int, string> $queryTokens
     * @param array<int, string> $candidateTokens
     */
    protected function bestTokenSimilarity(array $queryTokens, array $candidateTokens): float
    {
        if (empty($queryTokens) || empty($candidateTokens)) {
            return 0.0;
        }

        $best = 0.0;

        foreach ($queryTokens as $queryToken) {
            foreach ($candidateTokens as $candidateToken) {
                $similarity = $this->distanceSimilarity(
                    $this->normalizeForDistance($queryToken),
                    $this->normalizeForDistance($candidateToken)
                );

                if ($similarity > $best) {
                    $best = $similarity;
                }
            }
        }

        return $best;
    }

    protected function resolveItemTimestamp(mixed $item): int
    {
        if (!empty($item->found_at)) {
            $time = strtotime((string) $item->found_at);
            if ($time !== false) {
                return $time;
            }
        }

        if (!empty($item->created_at)) {
            $time = strtotime((string) $item->created_at);
            if ($time !== false) {
                return $time;
            }
        }

        return 0;
    }

    protected function canonicalizeToken(string $token): string
    {
        return $this->synonymMap[$token] ?? $token;
    }
}
