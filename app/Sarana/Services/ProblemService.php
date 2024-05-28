<?php 

namespace App\Sarana\Services;

use App\Models\Problem;
use App\Models\ProblemItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProblemService
{



    public $problem;
    public $items = [];
    public $request;
    public $scope;

    public function __construct(Problem $problem)
    {
        $this->problem = $problem;
    }

    public function add($item)
    {
        $this->items[] = $this->problem->items()->create($item);
        return $this;
    }

    public function fromRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function scope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    public function save(Problem $problem = null)
    {
        if($problem) 
        {
            $this->problem = $problem;
        }
       
        if($this->scope == "guru")
        {
            $this->saveAsGuru();    
        }

        if($this->scope == 'teknisi')
        {
            $this->saveAsTechnician();
        }
    }

    public function saveAsGuru()
    {
        $problem = $this->problem;
        if($problem->id)
        {

            $validated = $this->request->validate([
                'items' => 'required'
            ]);

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

            return;
        }


        $validated = $this->request->validate([
            'code' => 'nullable',
            'items' => 'required'
        ]);

        if(!$validated['code'])
        {
            $validated['code'] = Problem::generateLetterNumber('PRB');
        }
        
        $problem = Auth::user()
            ->problems()
            ->create($validated + [
                'date' => date('Y-m-d H:i:s'),
                'status' => 0
            ]);

        $items = collect($validated['items']);

        $items->map(function($item, $key) use($problem) {
            $item['status'] = 0;
            $problem
                ->items()
                ->create([
                    'good_id' => $item['good_id'],
                    'issue' => $item['issue'],
                ]);
        });

        $problem->items()
            ->whereNotIn('good_id', $items->pluck('good_id'))
            ->delete();
    }

    public function saveAsTechnician()
    {
        $validated = $this->request->validate([
            'items' => 'required'
        ]);
        
        $this->problem->update([
            'user_technician_id' => Auth::id()
        ]);

        $items = collect($validated['items']);
        $problem = $this->problem;
        $items->map(function($item, $key) use($problem) {
            $item['status'] = 0;
            $problem
                ->items()
                ->update([
                    'note' => $item['note'],
                    'price' => $item['price']
                ]);
        });

    }

}