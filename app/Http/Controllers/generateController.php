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

    // public function update(Request $request)
    // {
    //     // Validate incoming request data
    //     $validated = $request->validate([
    //         'variantId' => 'required|integer', // Validate variant ID
    //         'updatedData.variant_name' => 'required|string|max:255',
    //         'updatedData.url_variant' => 'required|string|max:255',
    //         'updatedData.conversion_type' => 'required|string',
    //         'updatedData.button_click_name' => 'nullable|string',
    //         'updatedData.submit_form_name' => 'nullable|string',
    //     ]);

    //     // Find the variant by ID and update it
    //     $variant = VariantModel::findOrFail($validated['variantId']);
    //     $variant->update($validated['updatedData']);

    //     // Return success response
    //     return response()->json(['success' => 'Variant updated successfully!']);
    // }


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
