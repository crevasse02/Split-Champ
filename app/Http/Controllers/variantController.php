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
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                '*.eksperimen_id' => 'required|string|max:40',
                '*.url_variant' => 'required|string|max:255',
                '*.variant_name' => 'required|string|max:255|unique:variant_tabel,variant_name,NULL,NULL,eksperimen_id,' . $request->input('eksperimen_id'),
                '*.conversion_type' => 'required|string|max:255',
                '*.button_click_name' => 'nullable|string|max:255',
                '*.submit_form_name' => 'nullable|string|max:255',
            ], [
                '*.url_variant.required' => 'URL wajib diisi.',
                '*.variant_name.required' => 'Nama variant wajib diisi.',
                '*.variant_name.unique' => 'Nama variant sudah ada dalam database untuk eksperimen ini.',
            ]);


            $variantNames = collect($validatedData)->pluck('variant_name');
            if ($variantNames->duplicates()->isNotEmpty()) {
                return response()->json([
                    'message' => 'Duplicate variant names found in the request.',
                    'errors' => ['variant_name' => ['Nama variant tidak boleh duplikat dalam satu request.']],
                ], 422);
            }
            // Store each variant in the database
            foreach ($validatedData as $variant) {
                VariantModel::create($variant);
            }

            return response()->json(['message' => "Variants created successfully", "code" => "200"], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'message' => 'Nama variant sudah ada dalam database untuk eksperimen ini.',
                    'errors' => ['variant_name' => ['Nama variant sudah ada dalam database untuk eksperimen ini.']],
                ], 422);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan pada penyimpanan data.',
                'error' => $e->getMessage(),
            ], 500);
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
?>
