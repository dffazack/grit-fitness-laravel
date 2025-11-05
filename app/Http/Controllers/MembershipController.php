<?php

namespace App\Http\Controllers;

use App\Models\MembershipPackage;
use App\Models\Facility;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $packages = MembershipPackage::where('is_active', true)
            ->orderBy('price')
            ->get();
            
        $facilities = Facility::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('membership.index', compact('packages', 'facilities'));
    }
}
