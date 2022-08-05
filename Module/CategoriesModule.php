<?php

include_once __DIR__."/../Module/Database.php";
class CategoriesModule extends Database
{
    private $table = "categories";

    public function __construct()
    {
        parent::__construct();
    }

    public function addCategory($data)
    {
        $data = $this->decode($data);
        $name = $this->con->real_escape_string($data->name);

        return parent::insert($this->encode(['table' => $this->table,
            'data' => "
              name ='$name',
              created_at = NOW()"]));
    }

    public function updateCategory($data){
        $data = $this->decode($data);
        $name = $this->con->real_escape_string($data->name);
        $id = $this->con->real_escape_string($data->id);

        return parent::update($this->encode(['table'=>$this->table,
            'data'=>"
              name ='$name',
              ",'where'=>"id='$id'"]));
    }

    public function get($id = '',$limit = ''){
        $id = $this->con->real_escape_string($id);
        $where = !empty($id) && is_numeric($id) ? "id='$id'" : "";
        $where = !empty($id) && !is_numeric($id) ? "name='$id'" : $where;

        return parent::select($this->encode(['table'=>$this->table, "cols"=>"*", "where"=>$where,"limit"=>$limit])) ;
    }

    public function getLike($id){
        $id = $this->con->real_escape_string($id);
        $where = !empty($id) && is_numeric($id) ? "id='$id'" : "";
        $where = !empty($id) && !is_numeric($id) ? "name LIKE '%$id'" : $where;

        return parent::select($this->encode(['table'=>$this->table, "cols"=>"*", "where"=>$where,"limit"=>1])) ;
    }

    public function exist($id){
        $id = $this->con->real_escape_string($id);
        $where = !empty($id) && is_numeric($id) ? "id='$id'" : "";
        $where = !empty($id) && !is_numeric($id) ? "name='$id'" : $where;
        return parent::select($this->encode(['table'=>$this->table,"cols"=>"id", "where"=>$where,"limit"=>1])) ;
    }
}