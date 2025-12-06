<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;

/**
 * Controller for the public feedback widget.
 * 
 * Serves the embeddable feedback form for external sites.
 */
class FeedbackController extends Controller
{
    /**
     * Display the feedback widget page.
     * 
     * This page can be embedded via iframe on external sites.
     */
    public function show(): \Illuminate\View\View
    {
        return view('widget.feedback');
    }
}
