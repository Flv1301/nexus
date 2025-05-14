<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\Gi2\Gi2Controller;
use App\Http\Controllers\Tools\ReverseLocationController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'permission:dpa'])->group(function () {

});

