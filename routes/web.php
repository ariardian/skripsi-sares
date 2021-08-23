<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TataLetakController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', function () {
        // $category_name = '';
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'sales',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
        // $pageName = 'analytics';
        return view('dashboard2')->with($data);
    });
    Route::get('/tentang', function () {
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'tentang',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
        return view('pages.tentang.index')->with($data);
    });
    Route::get('/bantuan', function () {
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'bantuan',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
        return view('pages.bantuan.index')->with($data);
    });

    // FILE ENTRY
    Route::resource('file-entry', TransactionController::class);
    Route::post('file-import', [TransactionController::class, 'fileImport'])->name('file-import');
    Route::get('file-export', [TransactionController::class, 'fileExport'])->name('file-export');

    // TATA LETAK
    Route::resource('tata-letak', TataLetakController::class);
    // Route::prefix('tata-letak')->group(function () {
    // });

});

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();
Route::get('/register', function () {
    return redirect('/login');
});
Route::get('/password/reset', function () {
    return redirect('/login');
});



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');