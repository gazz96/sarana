<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $role = $user->role->name ?? 'admin';
            
            // Create option object for layouts
            $option = new class {
                public function getByKey($key) {
                    return match($key) {
                        'app_name' => config('app.name', 'SARANAS'),
                        'app_logo' => null,
                        default => null
                    };
                }
            };
            
            // Try to get statistics
            $statistics = $this->dashboardService->getStatisticsForRole($role);
            
            return view('dashboard', compact('statistics', 'role', 'option'));
            
        } catch (\Exception $e) {
            // Return error details for debugging
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }

}