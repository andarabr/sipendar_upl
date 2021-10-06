<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewLDBName extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'lookup_data_by_name';
}