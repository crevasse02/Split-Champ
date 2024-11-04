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
            'variant' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'messages' => $validator->errors()], 422);
        }

        // Clean input
        $selector = ltrim($request->input('selector'), '.#');
        $token = $request->input('token');
        $variant = $request->input('variant');

        // Find the experiment by token
        $eksperimen = TrackerModel::where('eksperimen_id', $token)->first();
        if (!$eksperimen) {
            return response()->json(['status' => 'error', 'message' => 'No variant found for this token.'], 404);
        }

        // Get variant data
        $variantData = TrackerModel::where('eksperimen_id', $eksperimen->eksperimen_id)
            ->where('variant_name', $variant)
            ->where(function ($query) use ($selector) {
                $query->where('button_click_name', $selector)
                      ->orWhere('submit_form_name', $selector);
            })
            ->first();

        if (!$variantData) {
            return response()->json(['status' => 'error', 'message' => 'No variant found for the selector.'], 404);
        }

        // Increment the appropriate counter
        if ($variantData->button_click_name === $selector) {
            $variantData->increment('button_click');
        } elseif ($variantData->submit_form_name === $selector) {
            $variantData->increment('form_submit');
        }

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

        // Clean slug
        $slug = basename($request->input('slug'));
        $token = $request->input('token');

        // Find the experiment by token
        $eksperimen = TrackerModel::where('eksperimen_id', $token)->first();
        if (!$eksperimen) {
            return response()->json(['status' => 'error', 'message' => 'No Experiment available'], 404);
        }

        // Check for matching URL in ExperimentModel
        if (ExperimentModel::where('eksperimen_id', $eksperimen->eksperimen_id)->where('domain_name', $slug)->doesntExist()) {
            return response()->json(['status' => 'error', 'message' => 'There are no matching URL.'], 404);
        }

        // Increment view count in ExperimentModel
        ExperimentModel::where('eksperimen_id', $eksperimen->eksperimen_id)->increment('view');

        // Increment view count in TrackerModel
        if (TrackerModel::where('eksperimen_id', $eksperimen->eksperimen_id)->where('url_variant', $slug)->doesntExist()) {
            return response()->json(['status' => 'error', 'message' => 'There are no matching URL.'], 404);
        }

        TrackerModel::where('eksperimen_id', $eksperimen->eksperimen_id)->where('url_variant', $slug)->increment('view');

        return response()->json(['status' => 'success'], 200);
    }
}
