<?php

include_once __DIR__."/../Module/Database.php";
class BookmarksModal extends Database
{
    private $table = "favorite";

    public function __construct()
    {
        parent::__construct();
    }

    public function addFavorite($data){
        $data = $this->decode($data);
        $user = $this->con->real_escape_string($data->user);
        $post = $this->con->real_escape_string($data->post);

        return parent::insert($this->encode(['table' => $this->table,
            'data' => "
              post ='$post',
              user ='$user',
              date = NOW()"]));
    }

    public function getFavorite($user = ''){
        $where = !empty($id)? "user='$user'" :'';

        return parent::select(parent::encode(['table'=>$this->table,'where'=>$where]));
    }

    public function deleteMark($post ='', $user = ''){
        $where = !empty($post) && !empty($user) ? "post='$post' AND user='$user'" :'';

        return parent::delete(parent::encode(['table'=>$this->table,'where'=>$where]));
    }

    public function exist($id, $user){
        $id = $this->con->real_escape_string($id);
        $user = $this->con->real_escape_string($user);
        $where = !empty($id) && is_numeric($id) ? "post='$id' AND user='$user'" : "";
        return parent::select($this->encode(['table'=>$this->table,"cols"=>"id", "where"=>$where,"limit"=>1])) ;
    }
}