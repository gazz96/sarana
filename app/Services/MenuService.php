<?php 

namespace App\Services;

class MenuService 
{

    protected $items = [];

    public function add($location, $key, $item)
    {
        $this->items[$location][$key] = $item;
        return $this;
    }

    public function get($key)
    {
        return collect(isset($this->items[$key]) ? $this->items[$key] : []);
    }

}