<?php


include_once __DIR__."/../Controller/CategoriesController.php";
include_once __DIR__."/../Util/Util.php";

class Categories extends CategoriesController
{
    private $data;
    private $clean;
    private $key;

    public function __construct()
    {
        parent::__construct();

        $this->clean = new Util();
        $this->data = isset($_POST) && count($_POST) > 0 ? parent::decode(parent::encode($_POST)) : parent::decode(file_get_contents("php://input"));

        $this->key = getenv("SECRET");
    }

    public function all(){
        if($this->clean->Method() !== "GET")
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $status = 201;
        $data = $this->data;

        $c = parent::get();
        if($c){
            $status = 200;
            $data = $c;
        }


        return array('status'=>$status, 'payload'=>$data);
    }
}

$class = new Categories();