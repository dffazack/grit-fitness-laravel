<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display membership packages page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all active packages grouped by type
        $packages = MembershipPackage::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get()
            ->groupBy('type');

        // Separate packages for better view organization
        $regularPackages = $packages->get('regular', collect());
        $studentPackages = $packages->get('student', collect());

        // Get active facilities ordered by display order
        $facilities = Facility::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        return view('membership.index', compact('regularPackages', 'studentPackages', 'facilities'));
    }

    /**
     * Show specific package details (Optional - untuk future use)
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $package = MembershipPackage::where('is_active', true)
            ->findOrFail($id);

        // Get related/similar packages (same type, different duration)
        $relatedPackages = MembershipPackage::where('is_active', true)
            ->where('type', $package->type)
            ->where('id', '!=', $package->id)
            ->orderBy('price', 'asc')
            ->limit(3)
            ->get();

        return view('membership.show', compact('package', 'relatedPackages'));
    }

    /**
     * Get packages by type via AJAX (Optional - untuk filter dinamis)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterByType(Request $request)
    {
        $type = $request->input('type', 'all');

        $query = MembershipPackage::where('is_active', true);

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $packages = $query->orderBy('price', 'asc')->get();

        return response()->json([
            'success' => true,
            'packages' => $packages,
        ]);
    }

    /**
     * Compare packages (Optional - untuk future feature)
     *
     * @return \Illuminate\View\View
     */
    public function compare(Request $request)
    {
        $packageIds = $request->input('packages', []);

        if (count($packageIds) < 2) {
            return redirect()->route('membership.index')
                ->with('error', 'Pilih minimal 2 paket untuk membandingkan');
        }

        $packages = MembershipPackage::where('is_active', true)
            ->whereIn('id', $packageIds)
            ->get();

        return view('membership.compare', compact('packages'));
    }
}
