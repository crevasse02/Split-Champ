<?php

namespace App\Http\Controllers;

use App\Models\ExperimentModel;
use App\Models\VariantModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index()
    {
        $dataExperiment = ExperimentModel::paginate(10);
        return view('dashboard')->with([
            'dataExperiment' => $dataExperiment,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // This will log the user out

        // Optionally, you can invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/login'); // Redirect to homepage or login page after logout
    }

    public function getVariantData($eksperimenId)
    {
        // Retrieve each variant data for the given eksperimen_id
        $variantData = VariantModel::where('eksperimen_id', $eksperimenId)
            ->get(['variant_name', 'button_click', 'form_submit', 'view']);

        // Retrieve the view count from the ExperimentModel
        $experiment = ExperimentModel::where('eksperimen_id', $eksperimenId)->first(['view']);

        // Structure the response to include both variant data and experiment view count
        $response = [
            'experiment_view_count' => $experiment ? $experiment->view : 0,  // Default to 0 if experiment not found
            'variants' => $variantData,
        ];

        return response()->json($response);
    }
}
