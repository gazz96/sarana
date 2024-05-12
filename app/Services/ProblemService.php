<?php 

namespace App\Services;

use App\Models\Problem;
use App\Models\ProblemItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Auth;

class ProblemService
{

    public $request;
    public $problem;
    public $items = [];


    public function deleteItemNotExistsInList(Collection $items)
    {
        
        return $this->problem->items()
            ->where('id', 'NOT IN', $items->pluck('good_ids'))
            ->delete();
    }

    public function addItem($item)
    {
        $this->items[] = $item;
        return $this;
    }


    public function create($data)
    {
        $this->problem = Auth::user()
            ->problems()
            ->create($validated);
        $items = collect($validated['items']);
        $this->deleteItemNotExistsInList($validated);
    }

}