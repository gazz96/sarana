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
    public $scope;

    public function __construct($data = [])
    {
        $this->problem = new Problem();
        
        if($data)
        {
            $this->problem->create($data);
        }
       
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

    public function scope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    public function saveByScope()
    {
        if($this->scope == "guru")
        {
            
        }
    }

    public function 

    public function validated()
    {

    }

}