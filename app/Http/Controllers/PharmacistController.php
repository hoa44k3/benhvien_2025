<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PharmacistController extends Controller
{
     public function index()
    {
        return view('pharmacist.index'); // Tạo view doctor/dashboard.blade.php
    }
}
