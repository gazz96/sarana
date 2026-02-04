<?php

namespace App\Services;

use App\Models\Good;
use App\Models\Location;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStatisticsForRole($role)
    {
        return match($role) {
            'admin' => $this->getAdminStatistics(),
            'guru' => $this->getGuruStatistics(),
            'teknisi' => $this->getTeknisiStatistics(),
            'lembaga' => $this->getLembagaStatistics(),
            'keuangan' => $this->getKeuanganStatistics(),
            default => $this->getAdminStatistics(),
        };
    }

    protected function getAdminStatistics()
    {
        return [
            'overview' => [
                'total_goods' => Good::count(),
                'total_locations' => Location::count(),
                'total_problems' => Problem::count(),
                'active_problems' => Problem::whereIn('status', [1, 2, 3])->count(),
                'completed_problems' => Problem::where('status', 3)->count(),
                'total_users' => User::count(),
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
        
        return [
            'overview' => [
                'my_problems' => Problem::where('user_id', $user->id)->count(),
                'draft_problems' => Problem::where('user_id', $user->id)->where('status', 0)->count(),
                'active_problems' => Problem::where('user_id', $user->id)
                    ->whereIn('status', [1, 2])->count(),
                'completed_problems' => Problem::where('user_id', $user->id)
                    ->whereIn('status', [3, 5, 6, 7])->count(),
                'total_goods' => Good::where('status', 'AKTIF')->count(),
            ],
            'charts' => [
                'my_problem_status' => $this->getMyProblemStatus($user->id),
                'monthly_my_problems' => $this->getMyMonthlyProblems($user->id),
            ],
            'recent_activities' => $this->getMyRecentActivities($user->id),
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
        
        return [
            'overview' => [
                'assigned_jobs' => Problem::where('user_technician_id', $user->id)->count(),
                'pending_jobs' => Problem::where('user_technician_id', $user->id)->where('status', 1)->count(),
                'active_jobs' => Problem::where('user_technician_id', $user->id)->where('status', 2)->count(),
                'completed_jobs' => Problem::where('user_technician_id', $user->id)->where('status', 3)->count(),
                'waiting_approval' => Problem::where('user_technician_id', $user->id)
                    ->whereIn('status', [5, 6, 7])->count(),
            ],
            'charts' => [
                'job_status' => $this->getTechnicianJobStatus($user->id),
                'monthly_jobs' => $this->getTechnicianMonthlyJobs($user->id),
                'performance' => $this->getTechnicianPerformance($user->id),
            ],
            'recent_activities' => $this->getTechnicianRecentActivities($user->id),
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
                'pending_approval' => Problem::where('status', 3)->count(),
                'approved_this_month' => Problem::where('status', 5)
                    ->whereMonth('updated_at', now()->month)->count(),
                'total_budget_approved' => $this->getTotalBudgetApproved(),
                'active_contracts' => Problem::whereIn('status', [2, 3])->count(),
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
                'pending_payments' => Problem::where('status', 5)->count(),
                'paid_this_month' => Problem::where('status', 7)
                    ->whereMonth('updated_at', now()->month)->count(),
                'total expenditures' => $this->getTotalExpenditures(),
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

    // Chart Data Methods
    protected function getProblemsByStatus()
    {
        $statusLabels = [
            0 => 'DRAFT',
            1 => 'DIAJUKAN', 
            2 => 'PROSES',
            3 => 'SELESAI',
            4 => 'DIBATALKAN',
            5 => 'WAITING MGMT',
            6 => 'WAITING ADMIN',
            7 => 'WAITING KEUANGAN'
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
    }

    protected function getProblemsByLocation()
    {
        // Get location data from problem items instead
        $locationData = Problem::select('l.name', DB::raw('COUNT(DISTINCT problems.id) as count'))
            ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
            ->join('goods as g', 'pi.good_id', '=', 'g.id')
            ->join('locations as l', 'g.location_id', '=', 'l.id')
            ->groupBy('l.id', 'l.name')
            ->pluck('count', 'name')
            ->toArray();

        return $locationData ?: [];
    }

    protected function getMonthlyProblemTrend()
    {
        $data = Problem::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        return $data;
    }

    protected function getTopDamagedGoods()
    {
        $topDamaged = Problem::select('g.name', DB::raw('COUNT(DISTINCT problems.id) as count'))
            ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
            ->join('goods as g', 'pi.good_id', '=', 'g.id')
            ->groupBy('g.id', 'g.name')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'name')
            ->toArray();

        return $topDamaged ?: [];
    }

    protected function getMyProblemStatus($userId)
    {
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
    }

    protected function getMyMonthlyProblems($userId)
    {
        return Problem::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }

    protected function getTechnicianJobStatus($userId)
    {
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
    }

    protected function getTechnicianMonthlyJobs($userId)
    {
        return Problem::selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('user_technician_id', $userId)
            ->where('updated_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }

    protected function getTechnicianPerformance($userId)
    {
        $completedJobs = Problem::where('user_technician_id', $userId)
            ->where('status', 3)
            ->count();

        $totalJobs = Problem::where('user_technician_id', $userId)->count();
        
        return [
            'completion_rate' => $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100, 2) : 0,
            'completed' => $completedJobs,
            'total' => $totalJobs,
        ];
    }

    protected function getRecentActivities()
    {
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
    }

    protected function getMyRecentActivities($userId)
    {
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
    }

    protected function getTechnicianRecentActivities($userId)
    {
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
    }

    protected function getTotalBudgetApproved()
    {
        return Problem::where('status', 5)
            ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
            ->sum('pi.price');
    }

    protected function getApprovalTrend()
    {
        return Problem::selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('status', 5)
            ->where('updated_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }

    protected function getBudgetByMonth()
    {
        return Problem::selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, SUM(pi.price) as total')
            ->where('status', 5)
            ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
            ->where('updated_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    protected function getProblemsByCategory()
    {
        // Simplified category based on goods
        return Problem::selectRaw('COUNT(*) as count')
            ->where('status', 3)
            ->pluck('count')
            ->toArray();
    }

    protected function getTotalExpenditures()
    {
        return Problem::where('status', 7)
            ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
            ->sum('pi.price');
    }

    protected function getAwaitingPayment()
    {
        return Problem::where('status', 5)
            ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
            ->sum('pi.price');
    }

    protected function getMonthlyExpenses()
    {
        return Problem::selectRaw('DATE_FORMAT(updated_at, "%Y-%m") as month, SUM(pi.price) as total')
            ->where('status', 7)
            ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
            ->where('updated_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    protected function getPaymentStatus()
    {
        $paid = Problem::where('status', 7)->count();
        $pending = Problem::where('status', 5)->count();
        
        return [
            'paid' => $paid,
            'pending' => $pending,
            'total' => $paid + $pending,
        ];
    }

    protected function getCostByCategory()
    {
        return Problem::selectRaw('SUM(pi.price) as total')
            ->where('status', 7)
            ->join('problem_items as pi', 'problems.id', '=', 'pi.problem_id')
            ->pluck('total')
            ->toArray();
    }

    protected function getPendingApprovals()
    {
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
    }

    protected function getPendingPayments()
    {
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
    }
}