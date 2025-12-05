<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function show()
    {
        return view('widget.feedback');
    }
}
