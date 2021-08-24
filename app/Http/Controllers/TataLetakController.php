<?php

namespace App\Http\Controllers;

use App\Models\TataLetak;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TataLetakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = [
            'category_name' => 'tata-letak',
            'page_name' => 'input-tata-letak',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];

        $result = TataLetak::latest()->paginate(10);
        return view('pages.tata-letak.index', compact('result'))->with($config);
    }

    public function homeDashboard()
    {
        $data = [
            'category_name' => 'dashboard',
            'page_name' => 'dashboard',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $result = TataLetak::latest()->paginate(10);
        // dump($result);
        return view('dashboard2', compact('result'))->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $config = [
            'category_name' => 'tata-letak',
            'page_name' => 'input-tata-letak',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];
        $result = Transaction::latest()->orderBy('id', 'desc')->get();

        return view('pages.tata-letak.create', compact('result'))->with($config);
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
     * @param  \App\Models\TataLetak  $tataLetak
     * @return \Illuminate\Http\Response
     */
    public function show(TataLetak $tataLetak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TataLetak  $tataLetak
     * @return \Illuminate\Http\Response
     */
    public function edit(TataLetak $tataLetak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TataLetak  $tataLetak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TataLetak $tataLetak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TataLetak  $tataLetak
     * @return \Illuminate\Http\Response
     */
    public function destroy(TataLetak $tataLetak)
    {
        //
    }
}
