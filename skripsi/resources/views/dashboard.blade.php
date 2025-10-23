<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Toko Sumber Rejeki</title>

  <!-- Bootstrap & Chart.js -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
  @include('layouts.navbar')
  <div class="container-fluid">
    <div class="row">
      @include('layouts.sidebar')

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
          <h2 class="h4">Dashboard Omzet</h2>

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
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
          let chartType = (filter === 'tahun' || filter === 'bulan') ? 'bar' : 'line';

          chart = new Chart(ctx, {
            type: chartType,
            data: {
              labels: data.labels,
              datasets: [{
                label: `Omzet (${filter})`,
                data: data.values,
                backgroundColor: chartType === 'bar'
                  ? 'rgba(139, 13, 24, 0.5)'    // Warna batang
                  : 'rgba(139, 13, 24, 0.2)',   // Warna area line
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
                    label: function (context) {
                      let val = context.parsed.y || context.parsed;
                      return 'Rp ' + val.toLocaleString('id-ID');
                    }
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
                  ticks: {
                    autoSkip: true,
                    maxRotation: 45,
                    minRotation: 0
                  }
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
    document.getElementById('filterSelect').addEventListener('change', function () {
      loadChart(this.value);
    });
  </script>
</body>

</html>