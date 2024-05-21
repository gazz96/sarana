<?php 

namespace App\Sarana\Html;

class Table
{
    public $model = null;
    public $columns = [];
    public $filters = [];
    public $applys = [];
    public $posts_per_page = 20;
    public static $_instance;

    public function __construct($model = null) {
        $this->model = $model;
    }

    public static function getInstance()
    {
        if(!self::$_instance)
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function columns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function addColumn($columns)
    {
        $this->columns[] = $columns;
        return $this;
    }

    

    public function filters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function setPostsPerPage($posts_per_page = 20)
    {
        $this->posts_per_page = $posts_per_page;
        return $this;
    }

    public function applys($applys)
    {
        $this->applys = $applys;
        return $this;
    }


    public function getPostsPerPage()
    {
        return $this->posts_per_page;
    }

    public function getData()
    {
        if(!$this->model) {
            return [];
        }

        foreach($this->columns as $column)
        {
            if($column['searchable'] ?? '')
            {
                $this->model = $this->model->when(request($column['name']), function($query, $value) use($column) {
                    return $query->where($column['name'], 'like', "%" . request($column['name']) . "%");
                });
            }
        }

        foreach($this->applys as $key => $apply)
        {
           
            $this->model = $this->model->when($key, $apply);
        }

        foreach($this->filters as $key => $filter)
        {
            $this->model = $this->model->when(request($key), $filter);
        }

        return $this->model->when(request('sort'), function($query, $sort){
            return $query->orderBy($sort, request('sortBy'));
        }, function($query){
            $query->orderBy('created_at', 'DESC');
        })->paginate($this->posts_per_page) ?? [];
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function render()
    {
        if(!$this->model){
            return '';
        }

        return view('components.table', [
            'columns' => $this->columns,
            'filters' => $this->filters,
            'models' => $this->getData()
        ])->render();
    }
}