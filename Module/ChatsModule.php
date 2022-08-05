<?php

include_once __DIR__."/../Module/Database.php";

class ChatsModule extends \Database
{

    private $table = "chats";
    private $tableUsers = "chats,users";
    private $ceremoniestable = "posts crm, users u1, users u2";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $data
     * @return false
     */
    public function addPost($data){
        $data = (array) parent::decode($data);
        $status = false;
        $d = '';

        if(count($data) > 0){

            foreach ($data as $key=>$value) {
                if(!empty($value))
                    $d .= "$key='$value',";
            }

            $d = chop($d,',');

            $status = parent::insert(parent::encode(array("table"=>$this->table,"data"=>"$d")));
        }

        return $status;
    }

    

    /**
     * @param $title
     * @return mixed
     */
    public function checkPost($title){
        return parent::select(parent::encode(array("table"=>$this->table,'cols'=>"id",'where'=>"body Like '%$title'", 'limit'=>1)));
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getPost($id = '',$category = '',$not = false,$limit =''){
        $where = !empty($id)? "id='$id'" :'';
        $where = !empty($category) && !empty($id)&& !$not ? "category='$category' AND $id" :$where;
        $where = !empty($category) && !empty($id)&& $not ? "category !='$category' AND $id" :$where;

        $where = !empty($category) && empty($id) && $not ? "category !='$category'" :$where;

        $where = !empty($category) && empty($id) && !$not ? "category='$category'" :$where;

        return parent::select(parent::encode(['table'=>$this->table,'where'=>$where,'limit'=>chop($limit,',')]));
    }
 /**
     * @param string $id
     * @return mixed
     */
    public function getPostFeeds($id =''){
        $where = !empty($id)? "chats.userId = users.id AND chats.postId = '$id'" :'';
  
                 return parent::select(parent::encode([
            'table'   => $this->tableUsers,
            'where'  => $where,
            'cols'    => 'users.username,users.avater,chats.*',
            'orderby' => 'chats.id'
        ]));

        //              return parent::select(parent::encode([
        //     'table'   => $this->ceremoniestable,
        //     'where'   => 'crm.fId = u1.id AND crm.sId = u2.id',
        //     'cols'    => 'crm.*,u1.username u1,
        //                   u1.avater u1Avt,
        //                   u1.firstname u1Fname,
        //                   u1.lastname u1Lname,
        //                   u1.gender u1g,
        //                   u2.username u2,
        //                   u2.avater u2Avt,
        //                   u2.firstname u2Fname,
        //                   u2.lastname u2Lname,
        //                   u2.gender u2g',
        //     'orderby' => 'crm.cid'
        // ]));
    }

    public function get_post_by_user($user, $category ='',$not = false,$limit =''){
        $where = !empty($user)? "user='$user'" :'';
        $where = !empty($category) && $not ? "$where AND category!='$category'": $where;
        $where = !empty($category) && !$not ? "user='$user' AND category ='$category'": $where;

        $sql = ['table'=>$this->table,'where'=>$where,'limit'=>chop($limit,',')];
//        print_r($sql);
        return parent::select(parent::encode($sql));
    }

    public function updatePost($data)
    {
        $data = (array) parent::decode($data);
        $status = false;
        $d = '';

        if(count($data) > 0){

            foreach ($data as $key=>$value) {
                if(!empty($value) && $key != 'id')
                    $d .= "$key='$value',";
            }

            $d = chop($d,',');
            $id = $data['id'];

            $status = parent::update(parent::encode(array("table"=>$this->table,"data"=>"$d",'where'=>"id='$id'")));
        }

        return $status;
    }

    public function deletePost($id, $user)
    {
        $where = !empty($id) && !empty($user) ? "id='$id' AND user='$user'" :'';

        return parent::delete(parent::encode(['table'=>$this->table,'where'=>$where]));
    }
}