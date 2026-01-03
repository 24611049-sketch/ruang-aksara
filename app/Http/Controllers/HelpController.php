<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        return view('help.index');
    }
    
    public function faq()
    {
        return view('help.faq');
    }
    
    public function contact()
    {
        return view('help.contact');
    }
}