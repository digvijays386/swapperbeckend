<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\Intrest;
use App\Models\User;
use App\Models\UserIntrest_pivot;
use DB;
use Illuminate\Support\Facades\Auth;

class IntrestController extends Controller
{
    public function get_intrests(Request $request)
    {
        try {
            $intrest = Intrest::all();
            if (count($intrest) > 0) {

                return response()->json([
                    'data' => $intrest,
                    'message' => 'Intrest Found',
                    'error' => FALSE
                ]);
            } else {
                return response()->json([
                    'data' => $intrest,
                    'message' => 'No Intrest found',
                    'error' => TRUE
                ]);
            }
        } catch (\Exception $e) {
            return withError($e->getMessage());
        }
    }

    public function store_intrest(Request $request)
    {

        $data = User::with('intrests')->where('id', auth()->id())->first();

        if ($data != "" || $data != NULL) {
            if ($request->intrest_id != "" || $request->intrest_id != NULL) {
                $intrest = User::where('id', Auth()->id())->first();

                $intrest->intrests()->detach();

                $intrest->intrests()->attach($request->intrest_id);
            }
            return response()->json([
                'message' => 'Intrest Added Successfully',
                'error' => FALSE
            ]);
        } else {
            return response()->json([
                'message' => 'No record found to update against given id ',
                'error' => TRUE
                // 'code'=>202,
            ]);
        }
    }

    public function authInterests(Request $request)
    {
        $data = User::where('id', auth()->id())
            ->with('intrests')
            ->first();
        // dd(count($data->intrests));
        if (count($data->intrests) > 0) {
            $response = [
                'data' => $data->intrests,
                'message' => 'Interests Found',
                'success' => true
            ];
            return response()->json($response, 200);
        } else {
            return response()->json([
                'message' => 'No Interests found.',
                'error' => TRUE
                // 'code'=>202,
            ]);
        }
    }
}
