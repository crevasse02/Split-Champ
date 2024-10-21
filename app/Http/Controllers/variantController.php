<?php

namespace App\Http\Controllers;

use App\Models\ExperimentModel;



use Illuminate\Http\Request;

class variantController extends Controller
{
    //

    public function index()
    {
        $data = ExperimentModel::all()->toArray();
        return view('form-variant', compact('data'));
        // var_dump($data);
    }
}
