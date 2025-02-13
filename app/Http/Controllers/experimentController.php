<?php

namespace App\Http\Controllers;

use App\Models\ExperimentModel;
use Illuminate\Http\Request;

class experimentController extends Controller
{
    //

    public function index()
    {
        return view('create-experiment');
        // return csrf_token();
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'eksperimen_name' => 'required|string|max:255',
                'domain_name' => 'required|string|max:255',
                'created_by' => 'required|string|max:255',
            ], [
                'eksperimen_name.required' => 'Nama eksperimen wajib diisi.',
                'domain_name.required' => 'Nama domain wajib diisi.',
                'created_by.required' => 'Nama pembuat wajib diisi.',
            ]);

            // Simpan data ke database
            ExperimentModel::create($validated);

            return response()->json(['message' => "Experimen created successfully", "code" => "200"], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mengembalikan respons dengan pesan kesalahan
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }
    }
}
