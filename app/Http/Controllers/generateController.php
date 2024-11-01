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
