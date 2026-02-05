<?php

namespace App\Services;

use App\Models\Good;
use App\Models\Location;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OptimizedDashboardService
{
    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getStatisticsForRole($role)
    {
        return $this->cacheService->getDashboardStats($role, function () use ($role) {
            return match($role) {
                'admin' => $this->getAdminStatistics(),
                'guru' => $this->getGuruStatistics(),
                'teknisi' => $this->getTeknisiStatistics(),
                'lembaga' => $this->getLembagaStatistics(),
                'keuangan' => $this->getKeuanganStatistics(),
                default => $this->getAdminStatistics(),
            };
        });
    }

    protected function getAdminStatistics()
    {
        return [
            'overview' => [
                'total_goods' => $this->cacheService->getCachedCount('goods', fn() => Good::count()),
                'total_locations' => $this->cacheService->getCachedCount('locations', fn() => Location::count()),
                'total_problems' => $this->cacheService->getCachedCount('problems', fn() => Problem::count()),
                'active_problems' => $this->cacheService->remember('active_problems_count', fn() => Problem::whereIn('status', [1, 2, 3])->count(), 300),
                'completed_problems' => $this->cacheService->remember('completed_problems_count', fn() => Problem::where('status', 3)->count(), 300),
                'total_users' => $this->cacheService->getCachedCount('users', fn() => User::count()),
            ],
            'charts' => [
                'problems_by_status' => $this->getProblemsByStatus(),
                'problems_by_location' => $this->getProblemsByLocation(),
                'monthly_trend' => $this->getMonthlyProblemTrend(),
                'top_damaged_goods' => $this->getTopDamagedGoods(),
            ],
            'recent_activities' => $this->getRecentActivities(),
        ];
    }

    protected function getGuruStatistics()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        return [
            'overview' => [
                'my_problems' => $this->cacheService->remember("user_{$userId}_problems_count", fn() => Problem::where('user_id', $userId)->count(), 300),
                'draft_problems' => $this->cacheService->remember("user_{$userId}_draft_count", fn() => Problem::where('user_id', $userId)->where('status', 0)->count(), 300),
                'active_problems' => $this->cacheService->remember("user_{$userId}_active_count", fn() => Problem::where('user_id', $userId)->whereIn('status', [1, 2])->count(), 300),
                'completed_problems' => $this->cacheService->remember("user_{$userId}_completed_count", fn() => Problem::where('user_id', $userId)->whereIn('status', [3, 5, 6, 7])->count(), 300),
                'total_goods' => $this->cacheService->remember('active_goods_count', fn() => Good::where('status', 'AKTIF')->count(), 1800),
            ],
            'charts' => [
                'my_problem_status' => $this->getMyProblemStatus($userId),
                'monthly_my_problems' => $this->getMyMonthlyProblems($userId),
            ],
            'recent_activities' => $this->getMyRecentActivities($userId),
            'quick_actions' => [
                'create_problem' => route('problems.create'),
                'my_problems' => route('problems.index'),
                'inventory' => route('goods.index'),
            ],
        ];
    }

    protected function getTeknisiStatistics()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        return [
            'overview' => [
                'assigned_jobs' => $this->cacheService->remember("technician_{$userId}_assigned_count", fn() => Problem::where('user_technician_id', $userId)->count(), 300),
                'pending_jobs' => $this->cacheService->remember("technician_{$userId}_pending_count", fn() => Problem::where('user_technician_id', $userId)->where('status', 1)->count(), 300),
                'active_jobs' => $this->cacheService->remember("technician_{$userId}_active_count", fn() => Problem::where('user_technician_id', $userId)->where('status', 2)->count(), 300),
                'completed_jobs' => $this->cacheService->remember("technician_{$userId}_completed_count", fn() => Problem::where('user_technician_id', $userId)->where('status', 3)->count(), 300),
                'waiting_approval' => $this->cacheService->remember("technician_{$userId}_waiting_count", fn() => Problem::where('user_technician_id', $userId)->whereIn('status', [5, 6, 7])->count(), 300),
            ],
            'charts' => [
                'job_status' => $this->getTechnicianJobStatus($userId),
                'monthly_jobs' => $this->getTechnicianMonthlyJobs($userId),
                'performance' => $this->getTechnicianPerformance($userId),
            ],
            'recent_activities' => $this->getTechnicianRecentActivities($userId),
            'quick_actions' => [
                'my_jobs' => route('problems.index'),
                'inventory' => route('goods.index'),
                'locations' => route('locations.index'),
            ],
        ];
    }

    protected function getLembagaStatistics()
    {
        return [
            'overview' => [
                'pending_approval' => $this->cacheService->remember('pending_approval_count', fn() => Problem::where('status', 3)->count(), 300),
                'approved_this_month' => $this->cacheService->remember('approved_this_month_count', fn() => Problem::where('status', 5)->whereMonth('updated_at', now()->month)->count(), 600),
                'total_budget_approved' => $this->getTotalBudgetApproved(),
                'active_contracts' => $this->cacheService->remember('active_contracts_count', fn() => Problem::whereIn('status', [2, 3])->count(), 300),
            ],
            'charts' => [
                'approval_trend' => $this->getApprovalTrend(),
                'budget_by_month' => $this->getBudgetByMonth(),
                'problems_by_category' => $this->getProblemsByCategory(),
            ],
            'recent_activities' => $this->getRecentActivities(),
            'pending_approvals' => $this->getPendingApprovals(),
        ];
    }

    protected function getKeuanganStatistics()
    {
        return [
            'overview' => [
                'pending_payments' => $this->cacheService->remember('pending_payments_count', fn() => Problem::where('status', 5)->count(), 300),
                'paid_this_month' => $this->cacheService->remember('paid_this_month_count', fn() => Problem::where('status', 7)->whereMonth('updated_at', now()->month)->count(), 600),
                'total_expenditures' => $this->getTotalExpenditures(),
                'awaiting_payment' => $this->getAwaitingPayment(),
            ],
            'charts' => [
                'monthly_expenses' => $this->getMonthlyExpenses(),
                'payment_status' => $this->getPaymentStatus(),
                'cost_by_category' => $this->getCostByCategory(),
            ],
            'recent_activities' => $this->getRecentActivities(),
            'pending_payments' => $this->getPendingPayments(),
        ];
    }

    // Optimized Chart Data Methods with caching
    protected function getProblemsByStatus()
    {
        return $this->cacheService->getChartData('problems_by_status', function() {
            $statusLabels = [
                0 => 'DRAFT', 1 => 'DIAJUKAN', 2 => 'PROSES', 3 => 'SELESAI',
                4 => 'DIBATALKAN', 5 => 'WAITING MGMT', 6 => 'WAITING ADMIN', 7 => 'WAITING KEUANGAN'
            ];

            $data = Problem::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $result = [];
            foreach ($statusLabels as $key => $label) {
                $result[$label] = $data[$key] ?? 0;
            }

            return $result;
        }, 1800);
    }

    protected function getProblemsByLocation()
    {
        return $this->cacheService->getChartData('problems_by_location', function() {
            $locationData = Problem::select('l.name', DB::raw('COUNT(DISTINCT problems.id) as count'))
                ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
                ->join('goods as g', 'pi.good_id', '=', 'g.id')
                ->join('locations as l', 'g.location_id', '=', 'l.id')
                ->groupBy('l.id', 'l.name')
                ->pluck('count', 'name')
                ->toArray();

            return $locationData ?: [];
        }, 1800);
    }

    protected function getMonthlyProblemTrend()
    {
        return $this->cacheService->getChartData('monthly_problem_trend', function() {
            return Problem::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }, 3600);
    }

    protected function getTopDamagedGoods()
    {
        return $this->cacheService->getChartData('top_damaged_goods', function() {
            $topDamaged = Problem::select('g.name', DB::raw('COUNT(DISTINCT problems.id) as count'))
                ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
                ->join('goods as g', 'pi.good_id', '=', 'g.id')
                ->groupBy('g.id', 'g.name')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'name')
                ->toArray();

            return $topDamaged ?: [];
        }, 3600);
    }

    protected function getRecentActivities()
    {
        return $this->cacheService->remember('recent_activities_admin', function() {
            return Problem::with(['user', 'technician'])
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get()
                ->map(function ($problem) {
                    return [
                        'id' => $problem->id,
                        'code' => $problem->code,
                        'issue' => $problem->issue,
                        'status' => $problem->status,
                        'updated_by' => $problem->technician?->name ?? $problem->user?->name,
                        'updated_at' => $problem->updated_at->diffForHumans(),
                    ];
                })->toArray();
        }, 300);
    }

    // Additional optimized methods...
    protected function getMyProblemStatus($userId)
    {
        return $this->cacheService->remember("user_{$userId}_problem_status", function() use ($userId) {
            $myProblems = Problem::where('user_id', $userId)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $labels = ['DRAFT', 'DIAJUKAN', 'PROSES', 'SELESAI', 'DIBATALKAN'];
            $result = [];
            foreach ($labels as $index => $label) {
                $result[$label] = $myProblems[$index] ?? 0;
            }

            return $result;
        }, 1800);
    }

    protected function getMyMonthlyProblems($userId)
    {
        return $this->cacheService->remember("user_{$userId}_monthly_problems", function() use ($userId) {
            return Problem::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->where('user_id', $userId)
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }, 3600);
    }

    // Include other methods from original DashboardService...
    // For brevity, I'm showing the key optimized methods
    
    protected function getMyRecentActivities($userId)
    {
        return $this->cacheService->remember("user_{$userId}_recent_activities", function() use ($userId) {
            return Problem::where('user_id', $userId)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get()
                ->map(function ($problem) {
                    return [
                        'code' => $problem->code,
                        'issue' => $problem->issue,
                        'status' => $problem->status,
                        'updated_at' => $problem->updated_at->diffForHumans(),
                    ];
                })->toArray();
        }, 300);
    }

    protected function getTechnicianJobStatus($userId)
    {
        return $this->cacheService->remember("technician_{$userId}_job_status", function() use ($userId) {
            $jobs = Problem::where('user_technician_id', $userId)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $labels = ['DIAJUKAN', 'PROSES', 'SELESAI'];
            $result = [];
            foreach ($labels as $index => $label) {
                $result[$label] = $jobs[$index + 1] ?? 0;
            }

            return $result;
        }, 1800);
    }

    protected function getTechnicianMonthlyJobs($userId)
    {
        return $this->cacheService->remember("technician_{$userId}_monthly_jobs", function() use ($userId) {
            return Problem::selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, COUNT(*) as count')
                ->where('user_technician_id', $userId)
                ->where('updated_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }, 3600);
    }

    protected function getTechnicianPerformance($userId)
    {
        return $this->cacheService->remember("technician_{$userId}_performance", function() use ($userId) {
            $completedJobs = Problem::where('user_technician_id', $userId)
                ->where('status', 3)
                ->count();

            $totalJobs = Problem::where('user_technician_id', $userId)->count();
            
            return [
                'completion_rate' => $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100, 2) : 0,
                'completed' => $completedJobs,
                'total' => $totalJobs,
            ];
        }, 1800);
    }

    protected function getTechnicianRecentActivities($userId)
    {
        return $this->cacheService->remember("technician_{$userId}_recent_activities", function() use ($userId) {
            return Problem::where('user_technician_id', $userId)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get()
                ->map(function ($problem) {
                    return [
                        'code' => $problem->code,
                        'issue' => $problem->issue,
                        'status' => $problem->status,
                        'updated_at' => $problem->updated_at->diffForHumans(),
                    ];
                })->toArray();
        }, 300);
    }

    // Additional financial and management methods...
    protected function getTotalBudgetApproved()
    {
        return $this->cacheService->remember('total_budget_approved', function() {
            return Problem::where('status', 5)
                ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
                ->sum('pi.price');
        }, 1800);
    }

    protected function getApprovalTrend()
    {
        return $this->cacheService->getChartData('approval_trend', function() {
            return Problem::selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, COUNT(*) as count')
                ->where('status', 5)
                ->where('updated_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }, 3600);
    }

    protected function getBudgetByMonth()
    {
        return $this->cacheService->getChartData('budget_by_month', function() {
            return Problem::selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, SUM(pi.price) as total')
                ->where('status', 5)
                ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
                ->where('updated_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();
        }, 3600);
    }

    protected function getProblemsByCategory()
    {
        return $this->cacheService->remember('problems_by_category', function() {
            return Problem::selectRaw('COUNT(*) as count')
                ->where('status', 3)
                ->pluck('count')
                ->toArray();
        }, 1800);
    }

    protected function getTotalExpenditures()
    {
        return $this->cacheService->remember('total_expenditures', function() {
            return Problem::where('status', 7)
                ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
                ->sum('pi.price');
        }, 1800);
    }

    protected function getAwaitingPayment()
    {
        return $this->cacheService->remember('awaiting_payment', function() {
            return Problem::where('status', 5)
                ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
                ->sum('pi.price');
        }, 1800);
    }

    protected function getMonthlyExpenses()
    {
        return $this->cacheService->getChartData('monthly_expenses', function() {
            return Problem::selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, SUM(pi.price) as total')
                ->where('status', 7)
                ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
                ->where('updated_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();
        }, 3600);
    }

    protected function getPaymentStatus()
    {
        return $this->cacheService->remember('payment_status', function() {
            $paid = Problem::where('status', 7)->count();
            $pending = Problem::where('status', 5)->count();
            
            return [
                'paid' => $paid,
                'pending' => $pending,
                'total' => $paid + $pending,
            ];
        }, 600);
    }

    protected function getCostByCategory()
    {
        return $this->cacheService->remember('cost_by_category', function() {
            return Problem::selectRaw('SUM(pi.price) as total')
                ->where('status', 7)
                ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
                ->pluck('total')
                ->toArray();
        }, 1800);
    }

    protected function getPendingApprovals()
    {
        return $this->cacheService->remember('pending_approvals_list', function() {
            return Problem::where('status', 3)
                ->with(['user', 'technician'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
                ->map(function ($problem) {
                    $totalCost = $problem->items()->sum('price');
                    return [
                        'code' => $problem->code,
                        'issue' => $problem->issue,
                        'requester' => $problem->user?->name,
                        'technician' => $problem->technician?->name,
                        'estimated_cost' => $totalCost,
                        'created_at' => $problem->created_at->format('d/m/Y'),
                    ];
                })->toArray();
        }, 300);
    }

    protected function getPendingPayments()
    {
        return $this->cacheService->remember('pending_payments_list', function() {
            return Problem::where('status', 5)
                ->with(['user', 'technician'])
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get()
                ->map(function ($problem) {
                    $totalCost = $problem->items()->sum('price');
                    return [
                        'code' => $problem->code,
                        'issue' => $problem->issue,
                        'technician' => $problem->technician?->name,
                        'total_cost' => $totalCost,
                        'approved_date' => $problem->updated_at->format('d/m/Y'),
                    ];
                })->toArray();
        }, 300);
    }
}