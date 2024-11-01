<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackerModel extends Model
{
    use HasFactory;

    protected $table = 'variant_tabel';
    protected $primaryKey = 'variant_id'; // Set the primary key if different

    // Specify fillable fields to allow mass assignment
    protected $fillable = [
        'token', 
        'button_click_name', 
        'button_click', 
        'submit_form_name', 
        'form_submit',
        'url_variant',  // Add url_variant if needed
        'views'         // Add views field for tracking view counts
    ];

    // Method to increment button click count
    public static function updateButtonClickCount($buttonClickName)
    {
        return self::where('button_click_name', $buttonClickName)
                   ->increment('button_click');
    }

    // Method to increment form submit count
    public static function updateFormSubmitCount($submitFormName)
    {
        return self::where('submit_form_name', $submitFormName)
                   ->increment('form_submit');
    }

    // Method to increment views count based on eksperimen_id and url_variant
    public static function updateViewCount($eksperimenId, $urlVariant)
    {
        return self::where('eksperimen_id', $eksperimenId)
                   ->where('url_variant', $urlVariant)
                   ->increment('views');
    }
}
