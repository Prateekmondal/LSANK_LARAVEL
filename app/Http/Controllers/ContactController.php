<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Contact;
use Symfony\Component\Mime\Message;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|digits:10|numeric',
            'message' => 'required'
        ]);
        try {
            //code...
            DB::beginTransaction();
            Contact::create($request->all());
            DB::commit();
            return redirect()->back()->with('success', 'Thank you for contact us. we will contact you shortly.');
        } catch (\Exception $e) {
            dd($e);
        }

    }
}