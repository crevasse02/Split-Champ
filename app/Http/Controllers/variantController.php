<?php

namespace App\Http\Controllers;

use App\Models\ExperimentModel;
use App\Models\VariantModel;
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
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                '*.eksperimen_id' => 'required|string|max:40',
                '*.url_variant' => 'required|string|max:255',
                '*.variant_name' => 'required|string|max:255',
                '*.conversion_type' => 'required|string|max:255',
                '*.button_click_name' => 'nullable|string|max:255',
                '*.submit_form_name' => 'nullable|string|max:255',
            ], [
                '*.url_variant.required' => 'URL wajib diisi.',
                '*.variant_name.required' => 'Nama variant wajib diisi.',
            ]);

            // Store each variant in the database
            foreach ($validatedData as $variant) {
                VariantModel::create($variant);
            }

            return response()->json(['message' => "Variants created successfully", "code" => "200"], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return a response with error messages
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }
    }

    public function checkVariantCount(Request $request)
    {
        $eksperimenId = $request->input('eksperimen_id');
        $variantCount = VariantModel::where('eksperimen_id', $eksperimenId)->count();

        return response()->json([
            'count' => $variantCount,
            'limitReached' => $variantCount >= 4
        ]);
    }
}
