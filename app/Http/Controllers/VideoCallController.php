<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VideoCall;
use Illuminate\Http\Request;

class VideoCallController extends Controller
{
   public function index()
    {
        $calls = VideoCall::with(['doctor', 'patient', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('video_calls.index', compact('calls'));
    }
}
