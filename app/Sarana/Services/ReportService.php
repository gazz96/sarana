<?php 

namespace App\Sarana\Services;

use App\Models\ProblemItem;

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
                return $query->where('status', 2);
            })
            ->when($date, function($query, $date){
                $date = str_replace('-', '|', $date);
                $date = str_replace('/', '-', $date);
                $date = explode(" | ", $date);
                
                $query->whereHas('problem', function($query) use($date){
                    return $query->whereBetween('date', $date);
                });
                
                
            });

        return $problem_items->sum('price');
    }

    public function getTotalGoodFixed($date = null)
    {

        if($date) {

        }

        return ProblemItem::with('problem')
            ->where('status', 2)
            ->count('id');
    }

}
