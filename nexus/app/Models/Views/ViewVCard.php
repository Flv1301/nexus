<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewVCard extends Model
{
    use HasFactory;
    /** @var string  */
    protected $table = 'vcard_view';
}
