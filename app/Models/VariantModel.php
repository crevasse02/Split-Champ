<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VariantModel extends Model
{
    use HasFactory;

    protected $table = "variant_tabel";
    protected $primaryKey = 'variant_id';
    public $incrementing = false; // For non-integer primary keys
    protected $keyType = 'string'; // UUID is stored as a string

    protected $fillable = [
        'eksperimen_id',
        'url_variant',
        'variant_name',
        'conversion_type',
        'button_click_name',
        'submit_form_name',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate UUID for variant_id if not set
        static::creating(function ($model) {
            if (empty($model->variant_id)) {
                $model->variant_id = Str::uuid()->toString();
            }
        });
    }
}
