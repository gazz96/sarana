<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Problem;
use App\Sarana\Html\Table;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Sarana\Services\ProblemService;
use App\Services\NotificationService;


class ProblemController extends Controller
{

    protected $problem_service;
    protected NotificationService $notificationService;

    public function __construct(ProblemService $problem_service)
    {
        $this->problem_service = $problem_service;
        $this->notificationService = new NotificationService();
    }


    public function tableField()
    {

        $columns = [
            [
                'name' => 'code',
                'label' => 'Kode',
                'callback' => function($row)
                {
                    return "<div class=\"font-medium text-gray-900 dark:text-white\">{$row->code}</div>
                        <div class=\"flex flex-wrap gap-1 mt-1\">
                            <a href=\"". route('problems.show', $row) . "\" class=\"text-xs bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 px-2 py-1 rounded\">Lihat</a> 
                            <a href=\"". route('problems.print', $row) . "\" target=\"_blank\" class=\"text-xs bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 px-2 py-1 rounded\">Cetak</a> 
                            <a href=\"" . route('problems.edit', $row) . "\" class=\"text-xs bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 px-2 py-1 rounded\">Edit</a>
                            <form action=\"" . route('problems.destroy', $row) . "\" method=\"POST\" class=\"inline\">
                                <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\" />
                                <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                                <button class=\"text-xs bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 px-2 py-1 rounded\" onclick=\"return confirm('HAPUS???')\">Hapus</button>
                            </form>
                        </div>";
                }
            ],
            [
                'name' => 'user',
                'label' => 'Permintaan',
                'callback' => function($row) {
                    return $row->user->name ?? '-';
                }
            ],
            
            [
                'name' => 'date',
                'label' => 'Tanggal',
                'callback' => function($row) {
                    return date('d F Y H:i:s', strtotime($row->date));
                }
            ],
            [
                'name' => 'total',
                'label' => 'Total',
                'callback' => function($row) {
                    return number_format($row->items()->sum('price'));
                }
            ],
            [
                'name' => 'status',
                'label' => 'Status',
            ]
        ];

        if(Auth::user()->hasRole('teknisi'))
        {

            return [
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'callback' => function($row)
                    {
                        $submit_html = $edit_html = $delete_html = $show_html = $cancel_html = $accept_html = "";
                        $show_html = "<a href=\"". route('problems.show', $row) . "\" class=\"text-xs bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 px-2 py-1 rounded\">Lihat</a>";

                        if($row->status === 1)
                        {
                            $submit_html = "<a href=\"". route('problems.accept', $row) . "\" target=\"_blank\" class=\"text-xs bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 px-2 py-1 rounded\">Terima</a>";
                        }

                        if($row->status === 2 )
                        {
                            $edit_html = "<a href=\"" . route('problems.edit', $row) . "\" class=\"text-xs bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 px-2 py-1 rounded\">Edit</a>";
                            $delete_html = "<a href=\"". route('problems.cancel', $row) . "\" class=\"text-xs bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 px-2 py-1 rounded\" onclick=\"return confirm('Batalkan???')\">Batal</a>";

                            if(!$row->user_management_id)
                            {
                                $accept_html = "<a href=\"". route('problems.management-approval', $row) . "\" class=\"text-xs bg-purple-100 text-purple-800 hover:bg-purple-200 dark:bg-purple-900 dark:text-purple-300 dark:hover:bg-purple-800 px-2 py-1 rounded\" onclick=\"return confirm('Yakin ingin mengajukan harga ???')\">Ajukan</a>";
                            }

                        }

                        if($row->status == 5)
                        {
                            $accept_html = "<a href=\"". route('problems.finish', $row) . "\" class=\"text-xs bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 px-2 py-1 rounded\" onclick=\"return confirm('Yakin sudah selesai ???')\">Selesai</a>";
                        }

                        $html = $show_html . $submit_html . $edit_html . $accept_html . $delete_html;

                        return "<div class=\"font-medium text-gray-900 dark:text-white\">{$row->code}</div>
                            <div class=\"flex flex-wrap gap-1 mt-1\">
                                {$html}
                            </div>";
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Permintaan',
                    'callback' => function($row) {
                        return $row->user->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Lembaga',
                    'callback' => function($row) {
                        return $row->user_management->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Admin',
                    'callback' => function($row) {
                        return $row->admin->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Keuangan',
                    'callback' => function($row) {
                        return $row->finance->name ?? '-';
                    }
                ],
                [
                    'name' => 'date',
                    'label' => 'Tanggal',
                    'callback' => function($row) {
                        return date('d F Y H:i:s', strtotime($row->date));
                    }
                ],
                [
                    'name' => 'total',
                    'label' => 'Total',
                    'callback' => function($row) {
                        return number_format($row->items()->sum('price'));
                    }
                ],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'callback' => function($row ){
                        if($row->status == 5 && $row->user_management_id) {
                            return "MULAI BEKERJA";
                        }
                        return \App\Models\Problem::$STATUS[$row->status ] ?? '';
                    }
                ]
            ];
        }

        if(Auth::user()->hasRole('guru'))
        {
            return [
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'callback' => function($row)
                    {

                        $submit_html = $edit_html = $delete_html = $show_html = "";

                        if($row->status === 0)
                        {
                            $submit_html = "<a href=\"". route('problems.submit', $row) . "\" target=\"_blank\" class=\"text-xs bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 px-2 py-1 rounded\">Ajukan</a>";
                            $edit_html = "<a href=\"" . route('problems.edit', $row) . "\" class=\"text-xs bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 px-2 py-1 rounded\">Edit</a>";
                            $delete_html = "<form action=\"" . route('problems.destroy', $row) . "\" method=\"POST\" class=\"inline\">
                                <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\" />
                                <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                                <button class=\"text-xs bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 px-2 py-1 rounded\" onclick=\"return confirm('HAPUS???')\">Hapus</button>
                            </form>";
                        }
                        else
                        {
                            $show_html = "<a href=\"". route('problems.show', $row) . "\" target=\"_blank\" class=\"text-xs bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 px-2 py-1 rounded\">Lihat</a>";
                        }



                        $html = $show_html . $submit_html . $edit_html . $delete_html;

                        return "<div class=\"font-medium text-gray-900 dark:text-white\">{$row->code}</div>
                            <div class=\"flex flex-wrap gap-1 mt-1\">
                                {$html}
                            </div>";
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Permintaan',
                    'callback' => function($row) {
                        return $row->user->name ?? '-';
                    }
                ],
                [
                    'name' => 'date',
                    'label' => 'Tanggal',
                    'callback' => function($row) {
                        return date('d F Y H:i:s', strtotime($row->date));
                    }
                ],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'callback' => function($row ){
                        return \App\Models\Problem::$STATUS[$row->status ] ?? '';
                    }
                ]
            ];
        }

        if(Auth::user()->hasRole('admin'))
        {

            return [
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'callback' => function($row)
                    {
                        $submit_html = $edit_html = $delete_html = $show_html = $cancel_html = $accept_html = "";
                        $show_html = "<a href=\"". route('problems.show', $row) . "\" class=\"text-decoration-none me-2\">Lihat</a>";

                        if($row->status === 3 && $row->user_management_id && !$row->admin_id)
                        {
                            $accept_html = "<a href=\"". route('problems.approve', $row) . "\" class=\"text-decoration-none me-2 text-success\" onclick=\"return confirm('Berikan persetujuan ???')\">Approve</a>";
                        }

                        $html = $show_html . $submit_html . $edit_html . $accept_html . $delete_html;
                        
                        return "
                            {$row->code}
                            <div class=\"d-flex align-items-center tr-actions\">
                                {$html}
                            </div>
                        ";
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Permintaan',
                    'callback' => function($row) {
                        return $row->user->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Lembaga',
                    'callback' => function($row) {
                        return $row->user_management->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Admin',
                    'callback' => function($row) {
                        return $row->admin->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Keuangan',
                    'callback' => function($row) {
                        return $row->finance->name ?? '-';
                    }
                ],
                [
                    'name' => 'date',
                    'label' => 'Tanggal',
                    'callback' => function($row) {
                        return date('d F Y H:i:s', strtotime($row->date));
                    }
                ],
                [
                    'name' => 'total',
                    'label' => 'Total',
                    'callback' => function($row) {
                        return number_format($row->items()->sum('price'));
                    }
                ],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'callback' => function($row ){
                        return \App\Models\Problem::$STATUS[$row->status ] ?? '';
                    }
                ]
            ];
        }

        if(Auth::user()->hasRole('lembaga'))
        {

            return [
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'callback' => function($row)
                    {
                        $submit_html = $edit_html = $delete_html = $show_html = $cancel_html = $accept_html = "";
                        $show_html = "
                            <a href=\"". route('problems.show', $row) . "\" class=\"text-decoration-none me-2\">Lihat</a>
                            <a href=\"". route('problems.print', $row) . "\" target=\"_blank\" class=\"text-decoration-none me-2\">Cetak</a>
                        ";

                        if($row->status === 5)
                        {
                            if(!$row->user_management_id)
                            {
                                $accept_html = "<a href=\"". route('problems.approve', $row) . "\" class=\"text-decoration-none me-2 text-success\" onclick=\"return confirm('Yakin sudah selesai???')\">Approve</a>";
                            }
                            
                        }

                        $html = $show_html . $submit_html . $edit_html . $accept_html . $delete_html;
                        
                        return "
                            {$row->code}
                            <div class=\"d-flex align-items-center tr-actions\">
                                {$html}
                            </div>
                        ";
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Permintaan',
                    'callback' => function($row) {
                        return $row->user->name ?? '-';
                    }
                ],
                [
                    'name' => 'date',
                    'label' => 'Tanggal',
                    'callback' => function($row) {
                        return date('d F Y H:i:s', strtotime($row->date));
                    }
                ],
                [
                    'name' => 'total',
                    'label' => 'Total',
                    'callback' => function($row) {
                        return number_format($row->items()->sum('price'));
                    }
                ],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'callback' => function($row ){
                        if($row->user_management_id) {
                            return "MULAI BEKERJA";
                        }
                        return \App\Models\Problem::$STATUS[$row->status ] ?? '';
                    }
                ]
            ];
        }

        if(Auth::user()->hasRole('keuangan'))
        {

            return [
                [
                    'name' => 'code',
                    'label' => 'Kode',
                    'callback' => function($row)
                    {
                        $submit_html = $edit_html = $delete_html = $show_html = $cancel_html = $accept_html = "";
                        $show_html = "
                            <a href=\"". route('problems.show', $row) . "\" class=\"text-decoration-none me-2\">Lihat</a>
                            <a href=\"". route('problems.print', $row) . "\" target=\"_blank\" class=\"text-decoration-none me-2\">Cetak</a>
                        ";

                        if($row->status === 3)
                        {
                            if($row->user_management_id && $row->admin_id && !$row->user_finance_id)
                            {
                                $accept_html = "<a href=\"". route('problems.approve', $row) . "\" class=\"text-decoration-none me-2 text-success\" onclick=\"return confirm('Bayar Invoice ???')\">Approve</a>";
                            }
                            
                        }

                        $html = $show_html . $submit_html . $edit_html . $accept_html . $delete_html;
                        
                        return "
                            {$row->code}
                            <div class=\"d-flex align-items-center tr-actions\">
                                {$html}
                            </div>
                        ";
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Permintaan',
                    'callback' => function($row) {
                        return $row->user->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Lembaga',
                    'callback' => function($row) {
                        return $row->user_management->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Admin',
                    'callback' => function($row) {
                        return $row->admin->name ?? '-';
                    }
                ],
                [
                    'name' => 'user',
                    'label' => 'Keuangan',
                    'callback' => function($row) {
                        return $row->finance->name ?? '-';
                    }
                ],
                [
                    'name' => 'date',
                    'label' => 'Tanggal',
                    'callback' => function($row) {
                        return date('d F Y H:i:s', strtotime($row->date));
                    }
                ],
                [
                    'name' => 'total',
                    'label' => 'Total',
                    'callback' => function($row) {
                        return number_format($row->items()->sum('price'));
                    }
                ],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'callback' => function($row ){
                        if($row->status == 5 && $row->user_management_id) {
                            return "MULAI BEKERJA";
                        }
                        return \App\Models\Problem::$STATUS[$row->status ] ?? '';
                    }
                ]
            ];
        }




        return $columns;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $table = (new Table)
            ->applys([
                'showOnlyForTechincian' => function($query) use($request)
                {
                    if(Auth::user()->hasRole('teknisi'))
                    {
                        return $query->whereNotIn('status', [0]);
                    }
                },
                'filterByUser' => function($query) use($request)
                {
                    // Filter berdasarkan user role - user biasa hanya bisa lihat laporan mereka sendiri
                    if(!Auth::user()->hasRole(['admin', 'super user', 'teknisi', 'lembaga', 'keuangan'])) {
                        return $query->where('user_id', Auth::id());
                    }
                }
            ])
            ->filters([
                's' => function($query, $keyword) {
                    return $query->where('code', 'LIKE', '%' . $keyword . '%');
                }
            ])
            ->setModel(new Problem)
            ->setPostsPerPage(20)
            ->columns($this->tableField())->render();

        return view('problems.index', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $problem = new Problem();
        $goods = Good::orderBy('name', 'ASC')->get();

        if(Auth::user()->hasRole('guru')) {
            return view('problems.form-' . Str::lower(Auth::user()->role->name), compact('problem', 'goods'));
        }

        return view('problems.form', compact('problem', 'goods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->problem_service
            ->fromRequest($request)
            ->scope(Auth::user()->role->name ?? '')
            ->save();

        return redirect(route('problems.index'))
            ->with('status', 'success')
            ->with('message', 'Berhasil menyimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Problem  $problem
     * @return \Illuminate\Http\Response
     */
    public function show(Problem $problem)
    {
        // Get notifications related to this problem for the current user
        $problemNotifications = [];
        if (Auth::check()) {
            $problemNotifications = Auth::user()
                ->notifications()
                ->whereJsonContains('data->problem_id', $problem->id)
                ->latest()
                ->take(10)
                ->get();
        }
        
        return view('problems.show', compact('problem', 'problemNotifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Problem  $problem
     * @return \Illuminate\Http\Response
     */
    public function edit(Problem $problem)
    {
        

        $goods = Good::orderBy('name', 'ASC')->get();
        
        if(Auth::user()->hasRole('teknisi')) 
        {   
            abort_if($problem->status != 2, '403', 'You don\'t have access to this page');
            
            return view('problems.form-' . Str::lower(Auth::user()->role->name), compact('problem', 'goods'));
        }

        return view('problems.form', compact('problem', 'goods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Problem  $problem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Problem $problem)
    {
        $this->problem_service
            ->fromRequest($request)
            ->scope(Auth::user()->role->name ?? '')
            ->save($problem);

        return back()
            ->with('status', 'success')
            ->with('message', 'Berhasil menyimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Problem  $problem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Problem $problem)
    {
        try {
            $problem->delete();
            return back()
                ->with('status', 'success')
                ->with('message', 'Berhasil dihapus');
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function print(Problem $problem)
    {
        return view('problems.print', compact('problem'));
    }

    public function submitProblem(Problem $problem)
    {
        $oldStatus = $problem->status;
        $problem->update([
            'status' => 1
        ]);
        
        // Notify about problem submission
        $this->notificationService->notifyWorkflowChange($problem, 'problem_submitted', [
            'old_status' => $oldStatus,
            'new_status' => 1
        ]);

        return back()
            ->with('status', 'success')
            ->with('message', 'Masalah sudah diajukan ke teknisi');
    }

    public function acceptProblem(Problem $problem)
    {
        $oldStatus = $problem->status;
        $problem->update([
            'status' => 2,
            'user_technician_id' => Auth::id()
        ]);
        
        // Notify about problem acceptance
        $this->notificationService->notifyWorkflowChange($problem, 'problem_accepted', [
            'old_status' => $oldStatus,
            'new_status' => 2,
            'technician_id' => Auth::id()
        ]);

        return back()
            ->with('status', 'success')
            ->with('message', 'Anda akan menangani kendala ini');
    }

    public function cancelProblem(Problem $problem)
    {
        $oldStatus = $problem->status;
        $problem->update([
            'status' => 4 // DIBATALKAN
        ]);
        
        // Notify about problem cancellation
        $this->notificationService->notifyWorkflowChange($problem, 'problem_cancelled', [
            'old_status' => $oldStatus,
            'new_status' => 4,
            'cancelled_by' => Auth::user()->name
        ]);

        return back()
            ->with('status', 'success')
            ->with('message', 'Problem telah dibatalkan');
    }

    public function finishProblem(Problem $problem)
    {
        $oldStatus = $problem->status;
        $problem->update([
            'status' => 3
        ]);
        
        // Notify about problem completion
        $this->notificationService->notifyWorkflowChange($problem, 'problem_finished', [
            'old_status' => $oldStatus,
            'new_status' => 3
        ]);

        return back()
            ->with('status', 'success')
            ->with('message', 'Berhasil disimpan, pekerjaan anda akan di approve setelah dilakukan pemeriksaan');
    }

    public function managementApproval(Problem $problem)
    {
        $oldStatus = $problem->status;
        $problem->update([
            'status' => 5,
            'user_management_id' => Auth::id()
        ]);
        
        // Notify about management approval
        $this->notificationService->notifyWorkflowChange($problem, 'problem_approved_management', [
            'old_status' => $oldStatus,
            'new_status' => 5,
            'approved_by' => Auth::user()->name
        ]);

        return back()
            ->with('status', 'success')
            ->with('message', 'Berhasil disimpan, pekerjaan anda akan di approve setelah dilakukan pemeriksaan');
    }

    public function approveProblem(Problem $problem)
    {
        $oldStatus = $problem->status;
        
        if(Auth::user()->hasRole('lembaga'))
        {
            $problem->update([
                'user_management_id' => Auth::id()
            ]);
            
            // Notify about management approval
            $this->notificationService->notifyWorkflowChange($problem, 'problem_approved_management', [
                'old_status' => $oldStatus,
                'new_status' => $oldStatus, // Status doesn't change, just approval
                'approved_by' => Auth::user()->name
            ]);
        }

        if(Auth::user()->hasRole('keuangan'))
        {
            $problem->update([
                'user_finance_id' => Auth::id()
            ]);
            
            // Notify about finance approval (completion)
            $this->notificationService->notifyWorkflowChange($problem, 'problem_approved_finance', [
                'old_status' => $oldStatus,
                'new_status' => $oldStatus, // Status doesn't change
                'approved_by' => Auth::user()->name
            ]);
        }

        if(Auth::user()->hasRole('admin'))
        {
            $problem->update([
                'admin_id' => Auth::id()
            ]);
            
            // Notify about admin approval
            $this->notificationService->notifyWorkflowChange($problem, 'problem_approved_admin', [
                'old_status' => $oldStatus,
                'new_status' => $oldStatus, // Status doesn't change
                'approved_by' => Auth::user()->name
            ]);
        }
        

        return back()
            ->with('status', 'success')
            ->with('message', 'Anda telah memberikan persetujuan');
    }
}
