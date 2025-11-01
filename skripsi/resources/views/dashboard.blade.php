@extends('layouts.app')

@section('title', 'Dashboard - Toko Sumber Rejeki')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
    <h2 class="h4">📊 Dashboard Omzet & Pembelian</h2>

    <!-- 🔽 Dropdown Filter -->
    <select id="filterSelect" class="form-select w-auto">
      <option value="tahun">Per Tahun</option>
      <option value="bulan" selected>Per Bulan</option>
      <option value="minggu">Per Minggu</option>
      <option value="hari">Per Hari</option>
    </select>
  </div>

  <div class="row">
    <!-- 📈 Grafik Penjualan -->
    <div class="col-md-6">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title text-center mb-3">Grafik Omzet Penjualan</h5>
          <canvas id="chartPenjualan" height="120"></canvas>
        </div>
      </div>
    </div>

    <!-- 📉 Grafik Pembelian -->
    <div class="col-md-6">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title text-center mb-3">Grafik Total Pembelian</h5>
          <canvas id="chartPembelian" height="120"></canvas>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    let chartPenjualan;
    let chartPembelian;

    function loadChart(filter = 'bulan') {
      fetch(`{{ route('dashboard.data') }}?filter=${filter}`)
        .then(res => res.json())
        .then(data => {
          const ctxPenjualan = document.getElementById('chartPenjualan').getContext('2d');
          const ctxPembelian = document.getElementById('chartPembelian').getContext('2d');

          // Hapus chart lama kalau sudah ada
          if (chartPenjualan) chartPenjualan.destroy();
          if (chartPembelian) chartPembelian.destroy();

          // 🔹 Chart Omzet Penjualan
          chartPenjualan = new Chart(ctxPenjualan, {
            type: (filter === 'tahun' || filter === 'bulan') ? 'bar' : 'line',
            data: {
              labels: data.labels,
              datasets: [{
                label: 'Omzet Penjualan',
                data: data.penjualan,
                backgroundColor: 'rgba(139, 13, 24, 0.5)',
                borderColor: '#8b0d18',
                borderWidth: 2,
                tension: 0.4,
                fill: true
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                  callbacks: {
                    label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: { callback: val => 'Rp ' + val.toLocaleString('id-ID') }
                }
              }
            }
          });

          // 🔹 Chart Total Pembelian
          chartPembelian = new Chart(ctxPembelian, {
            type: (filter === 'tahun' || filter === 'bulan') ? 'bar' : 'line',
            data: {
              labels: data.labels,
              datasets: [{
                label: 'Total Pembelian',
                data: data.pembelian,
                backgroundColor: 'rgba(24, 90, 189, 0.5)',
                borderColor: '#185abd',
                borderWidth: 2,
                tension: 0.4,
                fill: true
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                  callbacks: {
                    label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: { callback: val => 'Rp ' + val.toLocaleString('id-ID') }
                }
              }
            }
          });
        })
        .catch(err => console.error('Gagal memuat data chart:', err));
    }

    // Muat grafik pertama kali
    loadChart('bulan');

    // Event listener dropdown
    document.getElementById('filterSelect').addEventListener('change', e => {
      loadChart(e.target.value);
    });
  </script>
@endpush