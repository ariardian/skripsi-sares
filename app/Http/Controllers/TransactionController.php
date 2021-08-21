<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = [
            'category_name' => 'file',
            'page_name' => 'file-entry',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];

        $result = Transaction::latest()->paginate(10);
        return view('pages.file-entry.index', compact('result'))->with($config);
    }

    public function fileImport(Request $request) 
    {
        if($request->file('file') === null) {
            return back()->withErrors('Silahkan pilih dulu file yang akan di import');
        }
        Excel::import(new UsersImport, $request->file('file')->store('temp'));
        // $collection = Excel::toCollection(new UsersImport, $request->file('file')->store('temp'));
        return back()->withStatus('Data telah berhasil di import dan tersimpan di basis data');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileExport() 
    {
        return Excel::download(new UsersExport, 'data-transaksi.xlsx');
    }  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $config = [
            'category_name' => 'file',
            'page_name' => 'file-entry',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,

        ];

        return view('pages.file-entry.create')->with($config);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $file_entry)
    {
        $config = [
            'category_name' => 'file',
            'page_name' => 'file-entry',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,

        ];
        $result = $file_entry;
        return view('pages.file-entry.view', compact('result'))->with($config);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $file_entry)
    {
        $config = [
            'category_name' => 'file',
            'page_name' => 'file-entry',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,

        ];
        $result = $file_entry;
        return view('pages.file-entry.edit', compact('result'))->with($config);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($file_entry)
    {
        Transaction::find($file_entry)->delete($file_entry);
        return response()->json(['success'=>'Item deleted!']);
    }
}
