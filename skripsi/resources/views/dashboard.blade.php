@extends('layouts.app')

@section('title', 'Dashboard - Toko Sumber Rejeki')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
    <h2 class="h4">📈 Dashboard Omzet</h2>

    <!-- 🔽 Dropdown Filter -->
    <select id="filterSelect" class="form-select w-auto">
      <option value="tahun">Per Tahun</option>
      <option value="bulan" selected>Per Bulan</option>
      <option value="minggu">Per Minggu</option>
      <option value="hari">Per Hari</option>
    </select>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title text-center mb-3">Grafik Omzet Penjualan</h5>
      <canvas id="omzetChart" height="120"></canvas>
    </div>
  </div>

@endsection

@push('scripts')
  <!-- ✅ Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    let chart;

    function loadChart(filter = 'bulan') {
      fetch(`/dashboard/data?filter=${filter}`)
        .then(res => res.json())
        .then(data => {
          const ctx = document.getElementById('omzetChart').getContext('2d');

          // Hapus chart lama sebelum membuat yang baru
          if (chart) chart.destroy();

          // 🔹 Tentukan jenis grafik berdasarkan filter
          const chartType = (filter === 'tahun' || filter === 'bulan') ? 'bar' : 'line';

          chart = new Chart(ctx, {
            type: chartType,
            data: {
              labels: data.labels,
              datasets: [{
                label: `Omzet (${filter})`,
                data: data.values,
                backgroundColor: chartType === 'bar'
                  ? 'rgba(139, 13, 24, 0.5)'
                  : 'rgba(139, 13, 24, 0.2)',
                borderColor: '#8b0d18',
                borderWidth: 2,
                tension: 0.4,
                fill: chartType !== 'bar'
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                  callbacks: {
                    label: ctx => 'Rp ' + (ctx.parsed.y || ctx.parsed).toLocaleString('id-ID')
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    callback: val => 'Rp ' + val.toLocaleString('id-ID')
                  }
                },
                x: {
                  ticks: { autoSkip: true, maxRotation: 45, minRotation: 0 }
                }
              }
            }
          });
        })
        .catch(err => console.error('Gagal memuat data chart:', err));
    }

    // 🔹 Load pertama kali
    loadChart('bulan');

    // 🔹 Ganti filter
    document.getElementById('filterSelect').addEventListener('change', e => loadChart(e.target.value));
  </script>
@endpush