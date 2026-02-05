<?php

namespace App\Http\Controllers;

use App\Services\MonitoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    protected $monitoringService;

    public function __construct(MonitoringService $monitoringService)
    {
        $this->monitoringService = $monitoringService;
        $this->middleware('auth');
        $this->middleware('role:admin,lembaga');
    }

    /**
     * Show monitoring dashboard
     */
    public function dashboard()
    {
        $health = $this->monitoringService->getSystemHealth();
        $errorStats = $this->monitoringService->getErrorStats(7);
        $performanceMetrics = $this->monitoringService->getPerformanceMetrics(24);

        return view('monitoring.dashboard', compact('health', 'errorStats', 'performanceMetrics'));
    }

    /**
     * Get system health status
     */
    public function health()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->monitoringService->getSystemHealth()
        ]);
    }

    /**
     * Get recent errors
     */
    public function errors(Request $request)
    {
        $limit = $request->get('limit', 50);
        $errors = $this->monitoringService->getRecentErrors($limit);

        return response()->json([
            'status' => 'success',
            'data' => $errors
        ]);
    }

    /**
     * Get error statistics
     */
    public function errorStats(Request $request)
    {
        $days = $request->get('days', 7);
        $stats = $this->monitoringService->getErrorStats($days);

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Get recent user activities
     */
    public function activities(Request $request)
    {
        $limit = $request->get('limit', 100);
        $activities = $this->monitoringService->getRecentActivities($limit);

        return response()->json([
            'status' => 'success',
            'data' => $activities
        ]);
    }

    /**
     * Get performance metrics
     */
    public function performance(Request $request)
    {
        $hours = $request->get('hours', 24);
        $metrics = $this->monitoringService->getPerformanceMetrics($hours);

        return response()->json([
            'status' => 'success',
            'data' => $metrics
        ]);
    }

    /**
     * Run system health check
     */
    public function runHealthCheck()
    {
        $this->monitoringService->checkAndSendAlerts();

        return response()->json([
            'status' => 'success',
            'message' => 'Health check completed'
        ]);
    }
}