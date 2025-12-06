<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function show(): \Illuminate\View\View
    {
        return view('widget.feedback');
    }
}
