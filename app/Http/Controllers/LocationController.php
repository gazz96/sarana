<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Sarana\Html\Table;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $table = (new Table)
            ->setModel(Location::class)
            ->filters([
                's' => function($query, $keyword){
                    return $query->where('name', 'LIKE', "%{$keyword}%");
                }
            ])
            ->columns([
                [
                    'label' => 'Nama',
                    'name' => 'name',
                    'callback' => function($row) {
                        return "{$row->name}
                        <div class=\"d-flex align-items-center tr-actions\">
                            <a href=\"" . route('locations.edit', $row) . "\" class=\"text-decoration-none\">Edit</a>
                            <form action=\"" . route('locations.destroy', $row) . "\" method=\"POST\" class=\"ms-2\">
                                <input type=\"hidden\" name=\"_token\" value=\"" . csrf_token() . "\">
                                <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                                <button class=\"btn p-0 text-danger\" onclick=\"return confirm('HAPUS???')\">Hapus</button>
                            </form>
                        </div>";
                    }
                ]
            ])
            ->render();

        return view('locations.index', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $location = new Location();
        return view('locations.form', compact('location'));
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
        ]);

        $location = Location::create($validated);

        return redirect(route('locations.index'))
            ->with('status', 'success')
            ->with('message', 'Berhasil menyimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('locations.form', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        
        try {
            $location->update($validated);

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
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        //
    }
}
