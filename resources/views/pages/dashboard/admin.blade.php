@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
            {{-- @dd(auth()->check()) --}}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            </ol>
          </div>
        </div>
@endsection

@section('content')
    @php
      $inventoryConditionItems = [
        ['label' => 'Baik', 'count' => $inventoryBaikCount, 'color' => '#2f6fed'],
        ['label' => 'Rusak Ringan', 'count' => $inventoryRusakRinganCount, 'color' => '#5a8bff'],
        ['label' => 'Rusak Berat', 'count' => $inventoryRusakBeratCount, 'color' => '#9db8ff'],
      ];
      $inventoryConditionTotal = collect($inventoryConditionItems)->sum('count');
    @endphp

    <div class="row">
      <div class="col-xl-8">
        @php
          $summaryCards = [];

          if ($canManageMasterData) {
            $summaryCards[] = [
              'class' => 'dash-emerald',
              'icon' => 'fas fa-tags',
              'count' => $categoryCount,
              'label' => 'Kategori',
              'link' => '/categories',
            ];
          }

          if ($canDocuments) {
            $summaryCards[] = [
              'class' => 'dash-cyan',
              'icon' => 'fas fa-file-alt',
              'count' => $documentCount,
              'label' => 'Dokumen',
              'link' => '/documents',
            ];
          }

          if ($canInventory) {
            $summaryCards[] = [
              'class' => 'dash-amber',
              'icon' => 'fas fa-boxes',
              'count' => $inventoryCount,
              'label' => 'Barang Kantor',
              'link' => '/inventory',
            ];
          }

          if ($canFoundItems) {
            $summaryCards[] = [
              'class' => 'dash-blue',
              'icon' => 'fas fa-box-open',
              'count' => $productCount,
              'label' => 'Barang Temuan',
              'link' => '/products',
            ];
          }
        @endphp

        <div class="row">
          @forelse ($summaryCards as $summaryCard)
            <div class="col-lg-6 col-md-6 mb-3">
              <div class="dash-card {{ $summaryCard['class'] }}">
                <i class="dash-icon {{ $summaryCard['icon'] }}"></i>
                <h3 class="dash-countup" data-target="{{ $summaryCard['count'] }}">0</h3>
                <p>{{ $summaryCard['label'] }}</p>
                <a href="{{ $summaryCard['link'] }}" class="dash-link">Lihat Data <i class="fas fa-arrow-right ml-1"></i></a>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-info mb-3">
                Akun Anda belum memiliki akses menu. Silakan hubungi super admin.
              </div>
            </div>
          @endforelse

          @if ($canFoundItems)
            <div class="col-lg-6 col-md-6 mb-3">
              <div class="dash-card dash-slate">
                <i class="dash-icon fas fa-hourglass-half"></i>
                <h3 class="dash-countup" data-target="{{ $foundNotPickedCount }}">0</h3>
                <p>Barang Temuan Belum Diambil</p>
                <a href="{{ request()->getBaseUrl() }}/products?q=belum_diambil" class="dash-link">Lihat Data <i class="fas fa-arrow-right ml-1"></i></a>
              </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-3">
              <div class="dash-card dash-gold">
                <i class="dash-icon fas fa-check-circle"></i>
                <h3 class="dash-countup" data-target="{{ $foundPickedCount }}">0</h3>
                <p>Barang Temuan Sudah Diambil</p>
                <a href="{{ request()->getBaseUrl() }}/products?q=sudah_diambil" class="dash-link">Lihat Data <i class="fas fa-arrow-right ml-1"></i></a>
              </div>
            </div>
          @endif
        </div>
      </div>

      <div class="col-xl-4">
        @if ($canInventory)
          <div class="card inventory-chart-card mb-3">
            <div class="card-header border-0">
              <h3 class="font-weight-bold mb-1" style="font-size: 1rem;">Barang Kantor</h3>
              <p class="text-muted mb-0" style="font-size: .82rem;">Diagram kondisi inventaris barang kantor.</p>
            </div>
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-sm-5 mb-3 mb-sm-0">
                  <div class="inventory-chart-wrap compact">
                    <canvas id="inventoryConditionChart"></canvas>
                  </div>
                </div>

                <div class="col-sm-7">
                  <ul class="list-unstyled mb-0 inventory-condition-legend">
                    @foreach ($inventoryConditionItems as $item)
                      @php
                        $percentage = $inventoryConditionTotal > 0
                            ? round(($item['count'] / $inventoryConditionTotal) * 100, 1)
                            : 0;
                      @endphp
                      <li class="d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center">
                          <span class="legend-dot mr-2" style="background: {{ $item['color'] }};"></span>
                          <span class="legend-label">{{ $item['label'] }}</span>
                        </div>
                        <span class="legend-value">{{ number_format($percentage, 1) }}%</span>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          </div>
        @endif

        @if ($canFoundItems)
          <div class="card found-ratio-card mb-3">
            <div class="card-header border-0">
              <h3 class="font-weight-bold mb-1" style="font-size: 1rem;">Barang Temuan</h3>
              <p class="text-muted mb-0" style="font-size: .82rem;">Rasio status pengambilan barang temuan.</p>
            </div>
            <div class="card-body pt-2">
              <div class="ratio-row">
                <div class="ratio-label-wrap">
                  <span class="ratio-dot ratio-dot-pending"></span>
                  <span class="ratio-label">Belum Diambil</span>
                </div>
                <span class="ratio-value ratio-countup" data-target="{{ number_format($foundNotPickedRatio, 1, '.', '') }}">0.0%</span>
              </div>
              <div class="progress ratio-progress">
                <div class="progress-bar ratio-bar-pending ratio-animate-bar" data-target="{{ number_format($foundNotPickedRatio, 1, '.', '') }}" style="width: 0%"></div>
              </div>

              <div class="ratio-row mt-3">
                <div class="ratio-label-wrap">
                  <span class="ratio-dot ratio-dot-picked"></span>
                  <span class="ratio-label">Sudah Diambil</span>
                </div>
                <span class="ratio-value ratio-countup" data-target="{{ number_format($foundPickedRatio, 1, '.', '') }}">0.0%</span>
              </div>
              <div class="progress ratio-progress">
                <div class="progress-bar ratio-bar-picked ratio-animate-bar" data-target="{{ number_format($foundPickedRatio, 1, '.', '') }}" style="width: 0%"></div>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
@endsection

@push('styles')
  <style>
    .dash-cyan {
      background: linear-gradient(140deg, #0891b2 0%, #06b6d4 100%);
    }

    .inventory-chart-card .card-body {
      padding-top: 0.35rem;
    }

    .found-ratio-card .ratio-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 0.38rem;
    }

    .found-ratio-card .ratio-label-wrap {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .found-ratio-card .ratio-dot {
      width: 10px;
      height: 10px;
      border-radius: 999px;
      display: inline-block;
      flex: 0 0 auto;
    }

    .found-ratio-card .ratio-dot-pending {
      background: #64748b;
    }

    .found-ratio-card .ratio-dot-picked {
      background: #f59e0b;
    }

    .found-ratio-card .ratio-label {
      color: #475569;
      font-weight: 600;
      font-size: 0.9rem;
    }

    .found-ratio-card .ratio-value {
      color: #1e293b;
      font-weight: 800;
      font-size: 0.9rem;
    }

    .found-ratio-card .ratio-progress {
      height: 11px;
      border-radius: 999px;
      background: #e2e8f0;
      overflow: hidden;
    }

    .found-ratio-card .ratio-bar-pending {
      background:
        linear-gradient(90deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0)),
        linear-gradient(90deg, #64748b, #94a3b8);
      border-radius: 999px;
    }

    .found-ratio-card .ratio-bar-picked {
      background:
        linear-gradient(90deg, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0)),
        linear-gradient(90deg, #f59e0b, #fbbf24);
      border-radius: 999px;
    }

    .found-ratio-card .ratio-animate-bar {
      transition: width 1.2s cubic-bezier(0.2, 0.75, 0.2, 1);
      background-size: 200% 100%, 100% 100%;
      animation: ratioShimmer 1.8s linear infinite;
      box-shadow: 0 5px 14px rgba(30, 41, 59, .12);
    }

    .inventory-chart-wrap {
      height: 250px;
      max-width: 260px;
      margin: 0 auto;
    }

    .inventory-chart-wrap.compact {
      height: 190px;
      max-width: 190px;
    }

    .inventory-condition-legend .legend-dot {
      width: 11px;
      height: 11px;
      border-radius: 999px;
      display: inline-block;
      flex: 0 0 auto;
    }

    .inventory-condition-legend .legend-label {
      color: #64748b;
      font-weight: 600;
    }

    .inventory-condition-legend .legend-value {
      color: #334155;
      font-weight: 700;
      font-size: 0.92rem;
    }

    .inventory-chart-wrap canvas.chart-spin-in {
      transform-origin: center center;
      animation: chartSpinIn 1.1s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    @keyframes chartSpinIn {
      0% {
        opacity: 0;
        transform: scale(0.72) rotate(-170deg);
      }
      100% {
        opacity: 1;
        transform: scale(1) rotate(0deg);
      }
    }

    @keyframes ratioShimmer {
      0% {
        background-position: 200% 0, 0 0;
      }
      100% {
        background-position: -200% 0, 0 0;
      }
    }

  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      function animateDashboardCardCounts() {
        var counters = document.querySelectorAll('.dash-countup');
        counters.forEach(function (counter, index) {
          var target = Number(counter.getAttribute('data-target') || 0);
          var startTime = null;
          var duration = 900 + (index * 90);

          function tick(timestamp) {
            if (startTime === null) {
              startTime = timestamp;
            }

            var progress = Math.min((timestamp - startTime) / duration, 1);
            var current = Math.round(target * progress);
            counter.textContent = current.toLocaleString('id-ID');

            if (progress < 1) {
              requestAnimationFrame(tick);
            }
          }

          requestAnimationFrame(tick);
        });
      }

      animateDashboardCardCounts();

      function animateRatioCard() {
        var ratioBars = document.querySelectorAll('.ratio-animate-bar');
        ratioBars.forEach(function (bar, index) {
          var target = Number(bar.getAttribute('data-target') || 0);
          setTimeout(function () {
            bar.style.width = target + '%';
          }, 160 + (index * 140));
        });

        var counters = document.querySelectorAll('.ratio-countup');
        counters.forEach(function (counter) {
          var target = Number(counter.getAttribute('data-target') || 0);
          var startTime = null;
          var duration = 1100;

          function tick(timestamp) {
            if (startTime === null) {
              startTime = timestamp;
            }

            var progress = Math.min((timestamp - startTime) / duration, 1);
            var current = (target * progress).toFixed(1);
            counter.textContent = current + '%';

            if (progress < 1) {
              requestAnimationFrame(tick);
            }
          }

          requestAnimationFrame(tick);
        });
      }

      animateRatioCard();

      var canvas = document.getElementById('inventoryConditionChart');
      if (!canvas || typeof Chart === 'undefined') {
        return;
      }

      var labels = ['Baik', 'Rusak Ringan', 'Rusak Berat'];
      var rawData = [
        {{ $inventoryBaikCount }},
        {{ $inventoryRusakRinganCount }},
        {{ $inventoryRusakBeratCount }}
      ];
      var total = rawData.reduce(function (sum, value) { return sum + value; }, 0);
      var data = total === 0 ? [1, 1, 1] : rawData;

      new Chart(canvas, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            data: data,
            backgroundColor: [
              '#2f6fed',
              '#5a8bff',
              '#9db8ff'
            ],
            borderColor: '#ffffff',
            borderWidth: 4,
            hoverOffset: 4
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          animation: {
            duration: 1500,
            easing: 'easeOutQuart',
            animateRotate: true,
            animateScale: true
          },
          animations: {
            rotation: {
              from: -1.5 * Math.PI
            },
            scale: {
              from: 0.75,
              to: 1
            }
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  var index = context.dataIndex;
                  var value = rawData[index] || 0;
                  var pct = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';
                  return context.label + ': ' + value + ' (' + pct + '%)';
                }
              }
            }
          },
          cutout: '58%'
        }
      });

      // Force visible entrance effect even when Chart.js animation is subtle.
      canvas.classList.remove('chart-spin-in');
      // Reflow to restart animation on each page load.
      void canvas.offsetWidth;
      canvas.classList.add('chart-spin-in');
    });
  </script>
@endpush
