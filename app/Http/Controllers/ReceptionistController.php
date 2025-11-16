<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
     public function index()
    {
        return view('receptionist.index'); // Tạo view doctor/dashboard.blade.php
    }
}
