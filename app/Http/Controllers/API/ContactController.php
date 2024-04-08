<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function store_contact(Request $request)
    {
        $this->validate(
            $request,
            ['name' => 'required'],
            ['email' => 'required'],
            ['description' => 'description'],

        );

        $contact = Contact::create($request->all());
        if ($contact) {
            return response()->json([
                'data' => $contact,
                // 'total'=>$count,
                'message' => 'Contacts Added SuccessFully !',
                'error' => FALSE
            ]);
        } else {
            return response()->json([
                'data' => $contact,
                'message' => 'No Contacts found',
                'error' => TRUE
            ]);
        }
    }
}
