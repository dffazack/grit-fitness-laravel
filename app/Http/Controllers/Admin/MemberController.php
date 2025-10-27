<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('membership_status', $request->status);
        }
        
        $members = $query->latest()->paginate(15);
        
        return view('admin.members.index', compact('members'));
    }
    
    public function show($id)
    {
        $member = User::with('transactions')->findOrFail($id);
        return view('admin.members.show', compact('member'));
    }
    
    public function destroy($id)
    {
        $member = User::findOrFail($id);
        $member->delete();
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil dihapus');
    }
}

