<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Good;
use App\Models\Location;
use Exception;
use PhpParser\Node\Expr\Throw_;

class GoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $goods = Good::when($request->s, function($query, $keyword){
            return $query->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('detail' , 'LIKE' , "%{$keyword}%")
                ->orWhere('merk', 'LIKE' , "%{$keyword}%");
        })->paginate(20);
        return view('goods.index', compact('goods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $good = new Good;
        $locations = Location::orderBy('name', 'ASC')->get();
        return view('goods.form', compact('good', 'locations'));
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
            'name' => 'required',
            'location_id' => 'required',
            'merk' => 'required',
            'detail' => 'required',
            'status' => 'required'
        ]);

        $good = Good::create($validated);

        return redirect(route('goods.index'))
            ->with('status', 'success')
            ->with('message', 'Berhasil menyimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Good $good)
    {
        $locations = Location::orderBy('name', 'ASC')->get();
        return view('goods.form', compact('good', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Good $good)
    {
        $validated = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'merk' => 'required',
            'detail' => 'required',
            'status' => 'required'
        ]);
        
        try {
            $good->update($validated);

            return back()
                ->with('status', 'success')
                ->with('message', 'Berhasil menyimpan');
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Good $good)
    {
        try {
            $good->delete();
            return back()
                ->with('status', 'success')
                ->with('message', 'Berhasil menghapus');
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
        
    }

    /**
     * Search goods via AJAX
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $goods = Good::query()
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('code', 'like', '%' . $query . '%')
            ->orderBy('name', 'ASC')
            ->limit(20)
            ->get(['id', 'name', 'code']);
        
        return response()->json([
            'results' => $goods->map(function($good) {
                return [
                    'id' => $good->id,
                    'text' => $good->name . ' (' . $good->code . ')'
                ];
            })
        ]);
    }
}
