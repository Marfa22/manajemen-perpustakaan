<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\SmartSearchService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request, SmartSearchService $smartSearchService)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            $documents = Document::query()
                ->latest()
                ->paginate(10)
                ->withQueryString();
        } else {
            $searchPool = Document::query()
                ->latest()
                ->get();

            $rankedDocuments = $smartSearchService->search(
                $q,
                $searchPool,
                function ($item): string {
                    return implode(' ', [
                        (string) ($item->nama_dokumen ?? ''),
                        (string) ($item->kategori ?? ''),
                        (string) ($item->tahun ?? ''),
                        (string) ($item->deskripsi ?? ''),
                    ]);
                }
            );

            $documents = $smartSearchService->paginate($rankedDocuments, 10, $request->query());
        }

        return view('pages.documents.index', compact('documents', 'q'));
    }

    public function create()
    {
        return view('pages.documents.create');
    }

    public function store(Request $request)
    {
        $maxYear = date('Y') + 1;

        $validatedData = $request->validate([
            'nama_dokumen' => 'required|string|min:3|max:255',
            'kategori' => 'required|string|max:120',
            'tahun' => "required|integer|min:1900|max:{$maxYear}",
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ], [
            'nama_dokumen.required' => 'Nama dokumen harus diisi.',
            'nama_dokumen.min' => 'Nama dokumen minimal 3 karakter.',
            'kategori.required' => 'Kategori harus diisi.',
            'tahun.required' => 'Tahun harus diisi.',
            'tahun.min' => 'Tahun minimal 1900.',
            'tahun.max' => "Tahun maksimal {$maxYear}.",
            'file_pendukung.file' => 'File pendukung tidak valid.',
            'file_pendukung.mimes' => 'Format file harus pdf, doc, docx, jpg, jpeg, atau png.',
            'file_pendukung.max' => 'Ukuran file maksimal 2MB.',
        ]);

        if ($request->hasFile('file_pendukung')) {
            $validatedData['file_path'] = $request->file('file_pendukung')->store('documents/supporting-files', 'public');
        }

        unset($validatedData['file_pendukung']);

        Document::create($validatedData);

        return redirect('/documents')->with('success', 'Berhasil menambahkan dokumen.');
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);

        return view('pages.documents.edit', compact('document'));
    }

    public function showFile($id)
    {
        $document = Document::findOrFail($id);

        if (empty($document->file_path) || !Storage::disk('public')->exists($document->file_path)) {
            return redirect('/documents')->with('error', 'File dokumen tidak ditemukan.');
        }

        $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
        $mimeType = Storage::disk('public')->mimeType($document->file_path) ?? 'application/octet-stream';
        $previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png'];

        return view('pages.documents.show', [
            'document' => $document,
            'previewUrl' => route('documents.file.preview', $document->id),
            'downloadUrl' => route('documents.file.download', $document->id),
            'fileExtension' => $extension,
            'mimeType' => $mimeType,
            'canPreviewInline' => in_array($extension, $previewableExtensions, true),
        ]);
    }

    public function previewFile($id)
    {
        $document = Document::findOrFail($id);

        if (empty($document->file_path) || !Storage::disk('public')->exists($document->file_path)) {
            abort(Response::HTTP_NOT_FOUND, 'File dokumen tidak ditemukan.');
        }

        $headers = [
            'Content-Type' => Storage::disk('public')->mimeType($document->file_path) ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . basename((string) $document->file_path) . '"',
        ];

        return Storage::disk('public')->response($document->file_path, basename((string) $document->file_path), $headers);
    }

    public function downloadFile($id)
    {
        $document = Document::findOrFail($id);

        if (empty($document->file_path) || !Storage::disk('public')->exists($document->file_path)) {
            return redirect('/documents')->with('error', 'File dokumen tidak ditemukan.');
        }

        $downloadName = trim((string) $document->nama_dokumen) !== ''
            ? $document->nama_dokumen . '.' . pathinfo((string) $document->file_path, PATHINFO_EXTENSION)
            : basename((string) $document->file_path);

        return Storage::disk('public')->download($document->file_path, $downloadName);
    }

    public function update(Request $request, $id)
    {
        $maxYear = date('Y') + 1;
        $document = Document::findOrFail($id);

        $validatedData = $request->validate([
            'nama_dokumen' => 'required|string|min:3|max:255',
            'kategori' => 'required|string|max:120',
            'tahun' => "required|integer|min:1900|max:{$maxYear}",
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ], [
            'nama_dokumen.required' => 'Nama dokumen harus diisi.',
            'nama_dokumen.min' => 'Nama dokumen minimal 3 karakter.',
            'kategori.required' => 'Kategori harus diisi.',
            'tahun.required' => 'Tahun harus diisi.',
            'tahun.min' => 'Tahun minimal 1900.',
            'tahun.max' => "Tahun maksimal {$maxYear}.",
            'file_pendukung.file' => 'File pendukung tidak valid.',
            'file_pendukung.mimes' => 'Format file harus pdf, doc, docx, jpg, jpeg, atau png.',
            'file_pendukung.max' => 'Ukuran file maksimal 2MB.',
        ]);

        if ($request->hasFile('file_pendukung')) {
            if (!empty($document->file_path) && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $validatedData['file_path'] = $request->file('file_pendukung')->store('documents/supporting-files', 'public');
        }

        unset($validatedData['file_pendukung']);

        $document->update($validatedData);

        return redirect('/documents')->with('success', 'Berhasil mengubah dokumen.');
    }

    public function delete($id)
    {
        $document = Document::findOrFail($id);

        if (!empty($document->file_path) && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect('/documents')->with('success', 'Berhasil menghapus dokumen.');
    }
}
