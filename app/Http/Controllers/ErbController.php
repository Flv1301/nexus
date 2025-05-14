<?php

namespace App\Http\Controllers;

use App\Models\Erb;
use Illuminate\Http\Request;

class ErbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $municipios = Erb::getCity();


        return view('erb.index',compact('municipios'));
    }

    public function honeycomb(Request $request){

        $array_erbs = Erb::getErbs($request->input('city'),$request->input('operator'));
        return view('erb.show',compact('array_erbs'));
    }
    public function honeycombGoogle(Request $request){
       // dd($request);
        $arraymarkers = explode("\n", $request->input('markers'));
        //guarda o cabeçalho
        $strheader = array_shift($arraymarkers);
        //tranforma o cabeçalho em array
        $header = str_getcsv($strheader, ',', '"',);

        $markers = array();

        foreach ($arraymarkers as $rows) {

            $arrayrow = str_getcsv($rows, ',', '"',);
            if (count($header) == count($arrayrow) and count($arrayrow) != 0) {


                array_push($markers,array_combine($header, $arrayrow));
            }

        }





        return view('erb.show',compact('markers'));
    }

    public function dualmap(Request $request){


        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');

        return view('erb.dualmaps', compact('latitude', 'longitude'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
