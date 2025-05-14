<?php

namespace App\Http\Controllers;

use App\Models\MatchGi2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchGi2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has("imei_search") and $request->imei_search != 0){
            $gi2 = DB::connection("pgsql2")->select("SELECT gi2.gi2.date_time, gi2.gi2.imei, gi2.gi2.latitude, gi2.gi2.longitude from gi2.gi2 inner join sisp.boprel_imei on gi2.gi2.imei = sisp.boprel_imei.imei where gi2.gi2.imei like '%".$request->input("imei_search")."%' limit 25");



        }else{
            $gi2 = DB::connection("pgsql2")->select("SELECT gi2.gi2.date_time, gi2.gi2.imei, gi2.gi2.latitude, gi2.gi2.longitude from gi2.gi2 inner join sisp.boprel_imei on gi2.gi2.imei = sisp.boprel_imei.imei limit 100");


        }

        return view("search.matchgi2.index")->with("gi2",$gi2);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\MatchGi2  $matchGi2
     * @return \Illuminate\Http\Response
     */
    public function show(MatchGi2 $matchGi2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MatchGi2  $matchGi2
     * @return \Illuminate\Http\Response
     */
    public function edit(MatchGi2 $matchGi2)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MatchGi2  $matchGi2
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MatchGi2 $matchGi2)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MatchGi2  $matchGi2
     * @return \Illuminate\Http\Response
     */
    public function destroy(MatchGi2 $matchGi2)
    {
        //
    }
}
