<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\ProblemItem;
use App\Sarana\Html\Table;
use App\Sarana\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    
    public function problem()
    {
        $table = (new Table())
            ->setModel(Problem::class)
            ->applys([
                'addCount' => function($query) {
                    return $query->select([
                        DB::raw('count(id) as total_per_bulan'),
                        DB::raw('YEAR(date) as tahun'),
                        DB::raw('MONTH(date) as bulan'),
                        DB::raw('MONTHNAME(date) AS nama_bulan')
                    ]);
                },
                'groupByYear' => function($query){
                    return $query->groupBy(DB::raw('YEAR(date)', 'ASC'));
                },
                'groupByMonth' => function($query){
                    return $query->groupBy(DB::raw('MONTH(date)', 'ASC'));
                }
            ])
            ->columns([
                [
                    'label' => 'BULAN',
                    'name' => 'date',
                    'callback' => function($row)
                    {
                        return $row->nama_bulan;
                    } 
                ],
                [
                    'label' => 'TOTAL',
                    'name' => 'date',
                    'callback' => function($row)
                    {
                        return number_format($row->total_per_bulan);
                    } 
                ],
            ])
            ->render();

        $years = Problem::select([
            DB::raw('YEAR(date) as tahun'),
        ])
        ->groupBy(DB::raw('YEAR(date)'))
        ->orderBy(DB::raw('YEAR(date)', 'ASC'))
        ->get();

        return view('reports.problem', compact('table', 'years'));
    }

    public function finance(Request $request)
    {
        // Enhanced financial reporting with comprehensive statistics
        $reportService = new ReportService;
        
        // Get statistics with filters
        $statistics = $reportService->getFinancialStatistics($request->date);
        $monthlyTrends = $reportService->getMonthlyFinancialTrends($request->date);
        $categoryBreakdown = $reportService->getCategoryCostBreakdown($request->date);
        $paymentTracking = $reportService->getPaymentTracking($request->date);
        
        // Get table data
        $table = (new Table())
            ->setModel(ProblemItem::class)
            ->applys([
                'loadRelation' => function($query) {
                    return $query->with(['problem', 'good']);
                },
                'finishedOnly' => function($query) {
                    return $query->whereHas('problem', function($query){
                        return $query->where('status', 3); // Selesai
                    });
                }
            ])
            ->filters([
                'date' => function($query, $date) {
                    if($date) {
                        $date = str_replace('-', '|', $date);
                        $date = str_replace('/', '-', $date);
                        $date = explode(" | ", $date);
                        
                        if(count($date) >= 2) {
                            $query->whereHas('problem', function($query) use($date){
                                return $query->whereBetween('date', $date);
                            });
                        }
                    }
                }
            ])
            ->columns([
                [
                    'label' => 'Kode Laporan',
                    'name' => 'code',
                    'callback' => function($row)
                    {
                        return '<span class="badge badge-neutral">' . ($row->problem->code ?? '-') . '</span>';
                    } 
                ],
                [
                    'label' => 'Tanggal',
                    'name' => 'date',
                    'callback' => function($row)
                    {
                        return date('d M Y', strtotime($row->problem->date ?? 'now'));
                    } 
                ],
                [
                    'label' => 'Barang',
                    'name' => 'good',
                    'callback' => function($row)
                    {
                        return implode('', [
                            '<small class="text-xs opacity-70">' . ($row->good->code ?? '') . '</small>',
                            '<div class="font-medium">' . ($row->good->name ?? '') . '</div>',
                            '<div class="text-xs opacity-70">' . ($row->issue ?? '') . '</div>'
                        ]);
                    } 
                ],
                [
                    'label' => 'Biaya Perbaikan',
                    'name' => 'price',
                    'callback' => function($row)
                    {
                        return '<span class="font-semibold text-success">Rp ' . number_format($row->price) . '</span>';
                    } 
                ],
                [
                    'label' => 'Status Pembayaran',
                    'name' => 'payment_status',
                    'callback' => function($row)
                    {
                        $problem = $row->problem;
                        $status = 'pending';
                        $badgeClass = 'warning';
                        
                        if($problem->user_finance_id) {
                            $status = 'approved';
                            $badgeClass = 'success';
                        } elseif($problem->admin_id && $problem->user_management_id) {
                            $status = 'processing';
                            $badgeClass = 'info';
                        }
                        
                        return '<span class="badge badge-' . $badgeClass . '">' . strtoupper($status) . '</span>';
                    } 
                ],
            ])
            ->render();

        return view('reports.finance', compact(
            'table', 
            'statistics', 
            'monthlyTrends', 
            'categoryBreakdown',
            'paymentTracking'
        ));
    }
}
