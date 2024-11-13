<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contact(ContactRequest $request)
    {
        return $request->validated();
    }
}
