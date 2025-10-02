<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PersonController extends Controller {

    /**
     * Load page search person
     * @return View
     */
    public function index(){
        return view('person.index');
    }
}
