<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
// use App\Exports\UsersExport;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use App\Models\Transaction;

class TransactionsApiContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Transaction::latest()->paginate(10);
        $config = [
            'category_name' => 'file',
            'page_name' => 'file-entry',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,

        ];

        $pagination = Response::json(View::make('inc.pagination', compact('result'))->render());
        return [
            "data" => $result,
            "pagination" => $pagination
        ];
    }
    public function fileImport(Request $request) 
    {
        // dump($request);
        $array = Excel::toArray(new UsersImport, $request->file('file')->store('temp'));
        $collection = Excel::toCollection(new UsersImport, $request->file('file')->store('temp'));
        // dump($array);
        return [
            "data" => $array,
            "pagination" => $collection
        ];
        // dump($collection);

        // Excel::import(new UsersImport, $request->file('file')->store('temp'));
        // return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
