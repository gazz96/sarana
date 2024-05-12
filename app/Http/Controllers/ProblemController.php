<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Problem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $problems = Problem::when($request->s, function($query, $keyword){
            return $query->where('code', 'LIKE', "%{$keyword}%");
        })->paginate(20);
        return view('problems.index', compact('problems'));
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

        $validated = $request->validate([
            'date' => 'required',
            'code' => 'nullable',
            'items' => 'required'
        ]);

        if(!$validated['code'])
        {
            $validated['code'] = Problem::generateLetterNumber('PRB');
        }

        $validated['status'] = 0;

        $problem = Auth::user()
            ->problems()
            ->create($validated);

        $items = collect($validated['items']);

        $items->map(function($item, $key) use($problem) {
            $item['status'] = 0;
            $problem
                ->items()
                ->create($item);
        });

        $problem->items()
            ->whereNotIn('good_id', $items->pluck('good_id'))
            ->delete();

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
        //
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
        $validated = $request->validate([
            'date' => 'required',
            'code' => 'nullable',
            'items' => 'required'
        ]);

        if(!$validated['code'])
        {
            $validated['code'] = Problem::generateLetterNumber('PRB');
        }

        $validated['status'] = 0;

        $problem->update($validated);

        $items = collect($validated['items']);

        $items->map(function($item, $key) use($problem) {
            $item['status'] = 0;
            $problem
                ->items()
                ->updateOrCreate(
                    [
                        'problem_id' => $problem->id,
                        'good_id' => $item['good_id']
                    ],
                    $item
                );
        });

        $problem->items()
            ->whereNotIn('good_id', $items->pluck('good_id'))
            ->delete();

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
}
