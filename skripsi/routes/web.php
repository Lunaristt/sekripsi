<?php

use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RegisController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PajakController;

use App\Http\Controllers\DashboardController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('regis')->group(function () {
    Route::get('/', [RegisController::class, 'create'])->name('register');
    Route::post('/', [RegisController::class, 'store'])->name('register.store');
});

Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('index');
    Route::post('/', [BarangController::class, 'store'])->name('store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('update');
    Route::post('/barang/{id}/tambah-stok', [BarangController::class, 'tambahStok'])->name('tambahStok');
    Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
    Route::post('/tambahkategori', [BarangController::class, 'kategori'])->name('tambahkategori');
    Route::post('/tambahsatuan', [BarangController::class, 'satuan'])->name('tambahsatuan');
    Route::post('/import', [BarangController::class, 'import'])->name('import');

});

Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard'); // tampilkan halaman
    })->name('dashboard');

    Route::get('/data', [DashboardController::class, 'dashboardData'])->name('dashboard.data');
});


Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/', [PelangganController::class, 'index'])->name('index');
    Route::get('/create', [PelangganController::class, 'create'])->name('create');
    Route::post('/store', [PelangganController::class, 'store'])->name('store');
    Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('edit');
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('update');
    Route::delete('/{id}', [PelangganController::class, 'destroy'])->name('destroy');
    Route::get('/get-no-telp', [PelangganController::class, 'getNoTelp'])->name('getNoTelp');
});

Route::prefix('statustransaksi')->name('statustransaksi.')->group(function () {
    Route::get('/', [PenjualanController::class, 'index'])->name('index');
    Route::get('/create', [PenjualanController::class, 'create'])->name('create');
    Route::post('/store', [PenjualanController::class, 'store'])->name('store');
    Route::get('/get-no-telp', [PenjualanController::class, 'getNoTelp'])->name('getNoTelp');
    Route::delete('/{id}', [PenjualanController::class, 'destroy'])->name('.destroy');
});

Route::get('tambahkategori', function () {
    return view('tambahmasterdata/tambahkategori'); // blade form kamu
})->name('tambahkategori');

Route::get('pajak', [PajakController::class, 'index'])->name('pajak');

Route::get('/tambahsatuan', function () {
    return view('tambahmasterdata/tambahsatuan');
})->name('tambahsatuan');

Route::get('/tambahpelanggan', function () {
    return view('pelanggan/tambahpelanggan'); // blade form kamu
})->name('tambahpelanggan');

Route::get('/tambahdistributor', function () {
    return view('tambahdistributor');
})->name('tambahdistributor');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::prefix('tambahbarang')->name('tambahbarang.')->group(function () {
    Route::get('/', [BarangController::class, 'create'])->name('create');
    Route::post('/', [BarangController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BarangController::class, 'update'])->name('update');
    Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
});

Route::prefix('transaksi')->name('transaksi.')->group(function () {
    // Route::get('/', [TransaksiController::class, 'index'])->name('index');
    Route::get('/', [TransaksiController::class, 'create'])->name('create');
    Route::post('/items', [TransaksiController::class, 'addItem'])->name('addItem');
    Route::post('/checkout', [TransaksiController::class, 'checkout'])->name('checkout');
    Route::post('/cancel', [TransaksiController::class, 'cancel'])->name('cancel');
    Route::delete('/{id_penjualan}/{id_barang}', [TransaksiController::class, 'destroy'])->name('destroy');
    Route::get('/show/{id_penjualan}', [TransaksiController::class, 'show'])->name('show');
    Route::post('/{id}/batal', [TransaksiController::class, 'batalTransaksi'])->name('batal');
});

Route::prefix('distributor')->name('distributor.')->group(function () {
    Route::get('/', [DistributorController::class, 'index'])->name('index');
    Route::get('/', [DistributorController::class, 'create'])->name('create');
    Route::post('/store', [DistributorController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [DistributorController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [DistributorController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [DistributorController::class, 'destroy'])->name('destroy');
});

Route::prefix('pembelian')->name('pembelian.')->group(function () {
    Route::get('/', [PembelianController::class, 'index'])->name('index');
    Route::get('/create', [PembelianController::class, 'create'])->name('create');
    Route::post('/add-item', [PembelianController::class, 'addItem'])->name('addItem');
    Route::post('/checkout', [PembelianController::class, 'checkout'])->name('checkout');
    Route::post('/destroy', [PembelianController::class, 'destroy'])->name('destroy');
    Route::get('/cancel', [PembelianController::class, 'cancel'])->name('cancel');
    Route::post('/batal/{id}', [PembelianController::class, 'batalPembelian'])->name('batalPembelian');
});


Route::get('/pembelian', function () {
    return view('pembelian');
})->name('pembelian');


Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/pengeluaran', [LaporanController::class, 'pengeluaran'])->name('pengeluaran');
    Route::get('/pemasukan', [LaporanController::class, 'pemasukan'])->name('pemasukan');
});