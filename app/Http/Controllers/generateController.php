<?php

namespace App\Http\Controllers;

use App\Models\ExperimentModel;
use App\Models\VariantModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class generateController extends Controller
{
    //
    public function index()
    {
        $dataVariant = VariantModel::all();
        $dataExperiment = ExperimentModel::paginate(10);
        return view('generate-code')->with([
            'dataVariant' => $dataVariant,
            'dataExperiment' => $dataExperiment,
        ]);
    }

    public function update(Request $request, $id)
    {
        dd($request->all());
        // try {
        //     // Validate the incoming request data
        //     $validatedData = $request->validate([
        //         '*.variant_id' => 'required|string|max:40', // Ensure variant_id is required
        //         '*.url_variant' => 'required|string|max:255',
        //         '*.variant_name' => 'required|string|max:255',
        //         '*.conversion_type' => 'required|string|max:255',
        //         '*.button_click_name' => 'nullable|string|max:255',
        //         '*.submit_form_name' => 'nullable|string|max:255',
        //     ], [
        //         '*.url_variant.required' => 'URL wajib diisi.',
        //         '*.variant_name.required' => 'Nama variant wajib diisi.',
        //     ]);

        //     // Store each variant in the database
        //     foreach ($validatedData as $variantData) {
        //         // Find the existing variant using variant_id
        //         $variant = VariantModel::find($variantData['variant_id']);

        //         if ($variant) {
        //             // Update only the fields that can change
        //             $variant->url_variant = $variantData['url_variant'];
        //             $variant->variant_name = $variantData['variant_name'];
        //             $variant->conversion_type = $variantData['conversion_type'];
        //             $variant->button_click_name = $variantData['button_click_name'];
        //             $variant->submit_form_name = $variantData['submit_form_name'];

        //             // Save changes back to the database
        //             $variant->save();
        //         }
        //     }

        //     return response()->json(['message' => "Variants updated successfully", "code" => "200"], 200);
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     // Return a response with error messages
        //     return response()->json([
        //         'message' => 'Validasi gagal',
        //         'errors' => $e->validator->errors(),
        //     ], 422); // 422 Unprocessable Entity
        // }
    }

    public function destroy($id)
    {
        $experiment = ExperimentModel::findOrFail($id);
        VariantModel::where('eksperimen_id', $experiment->eksperimen_id)->delete();

        $experiment->delete();

        // Alternatively, you can use:
        // $experiment->delete(); // This will soft delete if SoftDeletes is used

        return Redirect::back()->with('success', 'Experiment deleted successfully!');
    }
}
