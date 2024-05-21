<?php 

namespace App\Sarana\Services;

use App\Models\Problem;
use App\Models\ProblemItem;
use Illuminate\Support\Facades\Request;

class ProblemService
{

    public $problem;
    public $items = [];
    public $request;

    public function __construct($data)
    {
        $this->problem = new Problem();
        $this->problem->create($data);
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

    public function create()
    {
        
    }

    public function rules()
    {

    }

    public function validated()
    {

    }


}