<?php

namespace App\Models;

use App\Mail\LetterMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LetterCompany extends Model
{
    use HasFactory;
    protected $table = "empresas";
    protected $connection = "bds";



}
