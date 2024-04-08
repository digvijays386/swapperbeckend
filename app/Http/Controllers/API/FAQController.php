<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\Faq;

class FAQController extends Controller
{

    public function get_faqs()
    {

        $faqs = Faq::all();
        if ($faqs) {
            return response()->json([
                'data' => [
                    'faqs' => $faqs
                ],
                'message' => 'faqs Found',
                'error' => FALSE
            ]);
        } else {
            return response()->json([
                'data' => null,
                'message' => 'No faqs found',
                'error' => TRUE
            ]);
        }
    }
}
