<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class ExperimentModel extends Model
{


    protected $table = "eksperimen";

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = [
        'eksperimen_name',
        'domain_name',
        'created_by',

    ];
}
