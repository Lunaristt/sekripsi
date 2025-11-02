<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| 🟢 ROUTE BEBAS LOGIN (LOGIN / REGISTER)
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('regis')->group(function () {
    Route::get('/', [RegisController::class, 'create'])->name('register');
    Route::post('/', [RegisController::class, 'store'])->name('register.store');
});

/*
|--------------------------------------------------------------------------
| 🔒 ROUTE WAJIB LOGIN (AUTHCHECK)
|--------------------------------------------------------------------------
*/
Route::middleware(['authcheck'])->group(function () {

    // 🏠 Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', fn() => view('dashboard'))->name('dashboard');
        Route::get('/dashboard-data', [DashboardController::class, 'dashboardData'])->name('dashboard.data');
    });

    // 👤 Pengguna

    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        // 📋 List & CRUD dasar
        Route::get('/', [PenggunaController::class, 'index'])->name('index');
        Route::get('/create', [PenggunaController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [PenggunaController::class, 'edit'])->name('edit');
        Route::delete('/{id}', [PenggunaController::class, 'destroy'])->name('destroy');

        // ✅ Tambahan fitur approval system
        Route::post('/{id}/approve', [PenggunaController::class, 'approve'])->name('approve');
        Route::post('/{id}/restrict', [PenggunaController::class, 'restrict'])->name('restrict');

        // ✅ Fitur dropdown ubah role
        Route::patch('/{id}/update-role', [PenggunaController::class, 'updateRole'])->name('updateRole');
    });


    // 📦 Barang
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::post('/', action: [BarangController::class, 'store'])->name('store');
        Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('edit');
        Route::put('/barang/{id}', [BarangController::class, 'update'])->name('update');
        Route::post('/barang/{id}/tambah-stok', [BarangController::class, 'tambahStok'])->name('tambahStok');
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
        Route::post('/tambahkategori', [BarangController::class, 'kategori'])->name('tambahkategori');
        Route::post('/tambahsatuan', [BarangController::class, 'satuan'])->name('tambahsatuan');
        Route::post('/import', [BarangController::class, 'import'])->name('import');

    });

    // 👥 Pelanggan
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
        Route::get('/create', [PelangganController::class, 'create'])->name('create');
        Route::post('/store', [PelangganController::class, 'store'])->name('store');
        Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('edit');
        Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('update');
        Route::delete('/{id}', [PelangganController::class, 'destroy'])->name('destroy');
        Route::get('/get-no-telp', [PelangganController::class, 'getNoTelp'])->name('getNoTelp');
    });

    // 🧾 Status Transaksi / Penjualan
    Route::prefix('statustransaksi')->name('statustransaksi.')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('index');
        Route::get('/create', [PenjualanController::class, 'create'])->name('create');
        Route::post('/store', [PenjualanController::class, 'store'])->name('store');
        Route::get('/get-no-telp', [PenjualanController::class, 'getNoTelp'])->name('getNoTelp');
        Route::delete('/{id}', [PenjualanController::class, 'destroy'])->name('destroy');
    });

    // 💰 Pajak
    Route::prefix('pajak')->name('pajak.')->group(function () {
        Route::get('/', [PajakController::class, 'index'])->name('index');
    });

    // 📋 Master Tambahan (Views)
    Route::get('tambahkategori', fn() => view('tambahmasterdata/tambahkategori'))->name('tambahkategori');
    Route::get('/tambahsatuan', fn() => view('tambahmasterdata/tambahsatuan'))->name('tambahsatuan');
    Route::get('/tambahdistributor', fn() => view('distributor/tambahdistributor'))->name('tambahdistributor');
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    // 🧱 Tambah Barang
    Route::prefix('tambahbarang')->name('tambahbarang.')->group(function () {
        Route::get('/', [BarangController::class, 'create'])->name('create');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
    });

    // 💳 Transaksi
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransaksiController::class, 'create'])->name('create');
        Route::post('/items', [TransaksiController::class, 'addItem'])->name('addItem');
        Route::post('/checkout', [TransaksiController::class, 'checkout'])->name('checkout');
        Route::post('/cancel', [TransaksiController::class, 'cancel'])->name('cancel');
        Route::delete('/{id_penjualan}/{id_barang}', [TransaksiController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id_penjualan}', [TransaksiController::class, 'show'])->name('show');
        Route::post('/{id}/batal', [TransaksiController::class, 'batalTransaksi'])->name('batal');
    });

    // 🛍️ Penjualan Detail
    Route::prefix('penjualan')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::get('/{id}/print', [PenjualanController::class, 'print'])->name('penjualan.print');
    });

    // 🚚 Distributor
    Route::prefix('distributor')->name('distributor.')->group(function () {
        Route::get('/index', [DistributorController::class, 'index'])->name('index');
        Route::get('/create', [DistributorController::class, 'create'])->name('create');
        Route::post('/store', [DistributorController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DistributorController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [DistributorController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [DistributorController::class, 'destroy'])->name('destroy');
        Route::post('/import', [DistributorController::class, 'import'])->name('import');
        Route::get('/template', [DistributorController::class, 'downloadTemplate'])->name('downloadTemplate');
        Route::get('/distributor/all', [DistributorController::class, 'getAll'])->name('all');

    });

    // 🧾 Pembelian
    Route::prefix('pembelian')->group(function () {
        Route::get('/index', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::get('/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::post('/add-item', [PembelianController::class, 'addItem'])->name('pembelian.addItem');
        Route::delete('/remove-item/{id}', [PembelianController::class, 'removeItem'])->name('pembelian.removeItem');
        Route::post('/cancel', [PembelianController::class, 'cancel'])->name('pembelian.cancel');
        Route::post('/checkout', [PembelianController::class, 'checkout'])->name('pembelian.checkout');
        Route::get('/barang-by-distributor/{id}', [PembelianController::class, 'getBarangByDistributor']);
        Route::get('/harga-beli/{distributorId}/{barangId}', [PembelianController::class, 'getHargaBeli']);

    });

    Route::get('/pembelian', fn() => view('pembelian'))->name('pembelian');
    Route::get('/distributor', fn() => view('distributor/distributor'))->name('distributor');

    // 📊 Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/pengeluaran', [LaporanController::class, 'pengeluaran'])->name('pengeluaran');
        Route::get('/pemasukan', [LaporanController::class, 'pemasukan'])->name('pemasukan');
    });
});
