<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ExperimentModel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = "eksperimen";
    protected $primaryKey = 'eksperimen_id';
    public $incrementing = false; // Important for non-integer primary keys
    protected $keyType = 'string'; // Specify UUID as string

    protected $fillable = [
        'eksperimen_name',
        'domain_name',
        'created_by',
        'id_user'
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate UUID for eksperimen_id if not set
        static::creating(function ($model) {
            if (empty($model->eksperimen_id)) {
                $model->eksperimen_id = Str::uuid()->toString();
            }
        });
    }
}
