<?php

namespace App\Http\Controllers;

use App\Models\HomepageContent;
use App\Models\Notification;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $homepage = HomepageContent::where('section', 'hero')->first();
        $notifications = Notification::active()->latest()->get();
        
        return view('home.index', compact('homepage', 'notifications'));
    }
}
