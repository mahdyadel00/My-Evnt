<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Contacts\StoreRequestContact;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        $setting     = Setting::first();

        return view('Frontend.contacts.index', compact('setting'));
    }


    public function store(StoreRequestContact $request)
    {
            $contact = Contact::create($request->safe()->all());
            if (!$contact) {
                return response()->json(['message' => 'failed']);
            }else{
                return response()->json(['message' => 'success']);
            }

    }
}
