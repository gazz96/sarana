<?php 

namespace App\Sarana\Services;

use App\Models\ProblemItem;
use App\Models\Problem;
use Illuminate\Support\Facades\DB;

class ReportService
{

    public static $instance = null;

    public static function new()
    {
        if(!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function getTotalPengeluaran($date = null)
    {
        $problem_items = ProblemItem::with('problem')
            ->whereHas('problem', function($query){
                return $query->where('status', 3); // Selesai
            })
            ->when($date, function($query, $date){
                $date = str_replace('-', '|', $date);
                $date = str_replace('/', '-', $date);
                $date = explode(" | ", $date);
                
                if(count($date) >= 2) {
                    $query->whereHas('problem', function($query) use($date){
                        return $query->whereBetween('date', $date);
                    });
                }
            });

        return $problem_items->sum('price');
    }

    public function getTotalGoodFixed($date = null)
    {
        return ProblemItem::with('problem')
            ->whereHas('problem', function($query) use ($date){
                $query->where('status', 3); // Selesai
                if($date) {
                    $date = str_replace('-', '|', $date);
                    $date = str_replace('/', '-', $date);
                    $date = explode(" | ", $date);
                    
                    if(count($date) >= 2) {
                        $query->whereBetween('date', $date);
                    }
                }
            })
            ->count('id');
    }

    /**
     * Get comprehensive financial statistics
     */
    public function getFinancialStatistics($date = null)
    {
        $query = ProblemItem::with(['problem', 'good'])
            ->whereHas('problem', function($query){
                $query->where('status', 3); // Selesai
            });

        // Apply date filter
        if($date) {
            $date = str_replace('-', '|', $date);
            $date = str_replace('/', '-', $date);
            $date = explode(" | ", $date);
            
            if(count($date) >= 2) {
                $query->whereHas('problem', function($query) use($date){
                    $query->whereBetween('date', $date);
                });
            }
        }

        $items = $query->get();

        return [
            'total_expenses' => $items->sum('price'),
            'total_items' => $items->count(),
            'average_cost' => $items->count() > 0 ? $items->sum('price') / $items->count() : 0,
            'highest_cost' => $items->max('price') ?? 0,
            'lowest_cost' => $items->min('price') ?? 0,
            'paid_count' => $items->filter(function($item) {
                return $item->problem && $item->problem->user_finance_id;
            })->count(),
            'pending_count' => $items->filter(function($item) {
                return $item->problem && !$item->problem->user_finance_id;
            })->count(),
        ];
    }

    /**
     * Get monthly financial trends
     */
    public function getMonthlyFinancialTrends($date = null)
    {
        $query = ProblemItem::select([
                DB::raw('SUM(price) as total_cost'),
                DB::raw('COUNT(*) as total_items'),
                DB::raw('YEAR(problem_items.created_at) as year'),
                DB::raw('MONTH(problem_items.created_at) as month'),
                DB::raw('MONTHNAME(problem_items.created_at) as month_name')
            ])
            ->join('problems', 'problem_items.problem_id', '=', 'problems.id')
            ->where('problems.status', 3) // Selesai
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12);

        // Apply date filter
        if($date) {
            $date = str_replace('-', '|', $date);
            $date = str_replace('/', '-', $date);
            $date = explode(" | ", $date);
            
            if(count($date) >= 2) {
                $query->whereBetween('problems.date', $date);
            }
        }

        return $query->get()->reverse()->values(); // Reverse to get chronological order
    }

    /**
     * Get cost breakdown by category (location)
     */
    public function getCategoryCostBreakdown($date = null)
    {
        $query = ProblemItem::select([
                DB::raw('goods.location_id'),
                DB::raw('locations.name as location_name'),
                DB::raw('SUM(problem_items.price) as total_cost'),
                DB::raw('COUNT(*) as total_items'),
                DB::raw('AVG(problem_items.price) as average_cost')
            ])
            ->join('goods', 'problem_items.good_id', '=', 'goods.id')
            ->join('locations', 'goods.location_id', '=', 'locations.id')
            ->join('problems', 'problem_items.problem_id', '=', 'problems.id')
            ->where('problems.status', 3) // Selesai
            ->groupBy('goods.location_id', 'locations.name')
            ->orderBy('total_cost', 'desc');

        // Apply date filter
        if($date) {
            $date = str_replace('-', '|', $date);
            $date = str_replace('/', '-', $date);
            $date = explode(" | ", $date);
            
            if(count($date) >= 2) {
                $query->whereBetween('problems.date', $date);
            }
        }

        return $query->get();
    }

    /**
     * Get payment tracking information
     */
    public function getPaymentTracking($date = null)
    {
        $query = Problem::select([
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN user_finance_id IS NOT NULL THEN 1 ELSE 0 END) as paid'),
                DB::raw('SUM(CASE WHEN user_finance_id IS NULL AND admin_id IS NOT NULL AND user_management_id IS NOT NULL THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN admin_id IS NULL OR user_management_id IS NULL THEN 1 ELSE 0 END) as waiting')
            ])
            ->where('status', 3); // Selesai

        // Apply date filter
        if($date) {
            $date = str_replace('-', '|', $date);
            $date = str_replace('/', '-', $date);
            $date = explode(" | ", $date);
            
            if(count($date) >= 2) {
                $query->whereBetween('date', $date);
            }
        }

        $result = $query->first();

        return [
            'total_invoices' => $result->total ?? 0,
            'paid_invoices' => $result->paid ?? 0,
            'pending_invoices' => $result->pending ?? 0,
            'waiting_invoices' => $result->waiting ?? 0,
            'payment_rate' => $result->total > 0 ? round(($result->paid / $result->total) * 100, 1) : 0,
        ];
    }

}
