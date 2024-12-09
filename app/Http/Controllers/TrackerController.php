<?php

namespace App\Http\Controllers;

use App\Models\ExperimentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TrackerModel;

class TrackerController extends Controller
{
    public function tracker(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'selector' => 'required|string|max:255',
            'token' => 'required|string|max:255',
            'variant' => 'string|max:255', // Validate each item in the 'variant' array

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'messages' => $validator->errors()], 422);
        }

        // Extract and clean selector
        $selector = ltrim($request->input('selector'), '.#');
        $token = $request->input('token');
        $variant = $request->input('variant');

        // Find eksperimen_id using the token
        $eksperimen = TrackerModel::where('eksperimen_id', $token)->first();

        if (!$eksperimen) {
            return response()->json(['status' => 'error', 'message' => 'No variant found for this token.'], 404);
        }

        // Get variant using eksperimen_id and matching selector
        $variantData = TrackerModel::where('eksperimen_id', $eksperimen->eksperimen_id)
            ->where('variant_name', $variant) // Add condition to check variant_name
            ->where(function ($query) use ($selector) {
                $query->where('button_click_name', $selector)
                    ->orWhere('submit_form_name', $selector);
            })
            ->first();

        if (!$variantData) {
            return response()->json(['status' => 'error', 'message' => 'No variant found for the selector.'], 404);
        }

        // Increment appropriate counter
        if ($variantData->button_click_name === $selector) {
            $variantData->button_click = ($variantData->button_click ?? 0) + 1;
        } elseif ($variantData->submit_form_name === $selector) {
            $variantData->form_submit = ($variantData->form_submit ?? 0) + 1;
        }

        // Save and respond
        $variantData->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function view(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|max:255',
            'token' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'messages' => $validator->errors()], 422);
        }

        // Extract and clean slug
        $slug = basename($request->input('slug'));  // Get only the last part of the slug
        $token = $request->input('token');

        // Find eksperimen_id using the token
        $eksperimen = TrackerModel::where('eksperimen_id', $token)->first();

        if (!$eksperimen) {
            return response()->json(['status' => 'error', 'message' => 'No Experiment available'], 404);
        }

        // Get variant using eksperimen_id and matching slug
        $viewsDataCountVariant = TrackerModel::where('eksperimen_id', $eksperimen->eksperimen_id)
            ->where('url_variant', $slug)
            ->count();


        if ($viewsDataCountVariant === 0) {
            return response()->json(['status' => 'error', 'message' => 'There are no matching URL Var.'], 404);
        }

        // Increment the view count for all matching records
        TrackerModel::where('eksperimen_id', $eksperimen->eksperimen_id)
            ->where('url_variant', $slug)
            ->increment('view');


        // Respond with success
        return response()->json(['status' => 'success'], 200);
    }

    public function viewBaseUrl(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|max:255',
            'token' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'messages' => $validator->errors()], 422);
        }

        // Extract and clean slug
        $slug = $request->input('slug');
        $token = $request->input('token');

        // Find eksperimen_id using the token
        $eksperimen = ExperimentModel::where('eksperimen_id', $token)->first();

        if (!$eksperimen) {
            return response()->json(['status' => 'error', 'message' => 'No Experiment available'], 404);
        }

        $viewsDataCountExperiment = ExperimentModel::where('eksperimen_id', $eksperimen->eksperimen_id)
            ->where('domain_name', $slug)
            ->count();

        if ($viewsDataCountExperiment === 0) {
            return response()->json(['status' => 'error', 'message' => 'There are no matching URL Exp.'], 404);
        }

        ExperimentModel::where('eksperimen_id', $eksperimen->eksperimen_id)
            ->increment('view');

        // Respond with success
        return response()->json(['status' => 'success'], 200);
    }

    public function getDomainUrl($eksperimenId)
    {
        // Retrieve the view count from the ExperimentModel
        $experiment = ExperimentModel::where('eksperimen_id', $eksperimenId)->first(['domain_name']);

        // Structure the response to include both variant data and experiment view count\
        // dd($experiment);
        $response = [
            'domain_name' => $experiment ? $experiment->domain_name : 0,  // Default to 0 if experiment not found
        ];

        return response()->json($response);
    }
}
