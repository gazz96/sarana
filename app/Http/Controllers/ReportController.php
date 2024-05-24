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
        $table = (new Table())
            ->setModel(ProblemItem::class)
            ->applys([
                'loadRelation' => function($query) {
                    return $query->with('problem');
                },
                'finishOnly' => function($query) {
                    return $query->whereHas('problem', function($query){
                        return $query->where('status', 2);
                    });
                }
            ])
            ->filters([
                'date' => function($query, $date) {
                    $date = str_replace('-', '|', $date);
                    $date = str_replace('/', '-', $date);
                    $date = explode(" | ", $date);


                    $query->whereHas('problem', function($query) use($date){
                        return $query->whereBetween('date', $date);
                    });
                }
            ])
            ->columns([
                [
                    'label' => 'Kode',
                    'name' => 'code',
                    'callback' => function($row)
                    {
                        return $row->problem->code ?? '-';
                    } 
                ],

                [
                    'label' => 'Tanggal',
                    'name' => 'date',
                    'callback' => function($row)
                    {
                        return $row->problem->date;
                    } 
                ],

                [
                    'label' => 'Nama',
                    'name' => 'date',
                    'callback' => function($row)
                    {
                        return implode('', [
                            "<small class='fw-bold'>" . ($row->good->code ?? '') . "</small>",
                            "<div>" . ($row->good->name ?? '') . "</div>"
                        ]);
                    } 
                ],
                [
                    'label' => 'Harga',
                    'name' => 'price',
                    'callback' => function($row)
                    {
                        return number_format($row->price);
                    } 
                ],
            ])
            ->render();

            $report_service = new ReportService;
            $total_pengeluaran = $report_service->getTotalPengeluaran($request->date);
            $total_good_fixed = $report_service->getTotalGoodFixed($request->date);

        return view('reports.finance', compact('table', 'total_pengeluaran', 'total_good_fixed'));
    }
}
