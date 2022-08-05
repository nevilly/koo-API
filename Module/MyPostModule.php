<?php

include_once __DIR__."/../Module/Database.php";

class MyPostModule extends \Database
{

    private $table = "posts";
    private $postsUsers = "posts,users";
    private $ptables = "posts,users,chats";
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

            $status = parent::insert_id(parent::encode(array("table"=>$this->table,"data"=>"$d")));
        }

        return $status;
    }

    

    /**
     * @param $title
     * @return mixed
     */
    public function checkPost($title){
        return parent::select(parent::encode(array("table"=>$this->table,'cols'=>"id",'where'=>"codeNo Like '%$title'", 'limit'=>1)));
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
        $where = !empty($id)? "posts.createdBy = users.id AND posts.pId = chats.postId AND pId='$id'" :'posts.createdBy = users.id AND posts.pId = chats.postId';


            return parent::select(parent::encode([
            'table'   => $this->ptables,
            'where'   => $where,
            'cols'    => 'posts.* ,users.username,users.avater,COUNT(chats.postId) AS commentNumber',
            'groupby' => 'posts.pId',
            'orderby' => 'posts.pId'

        ]));
    }
    
    /**
     * @param string $id
     * @return mixed
     */
    public function getPostFeedsByCeremonyId($id =''){
        $where = !empty($id) ? "posts.createdBy = users.id AND posts.pId = chats.postId AND ceremonyId='$id'" : 'posts.createdBy = users.id AND posts.pId = chats.postId';
      

            return parent::select(parent::encode([
            'table'   => $this->ptables,
            'where'   => $where,
            'cols'    => 'posts.* ,users.username,users.avater,COUNT(chats.postId) AS commentNumber',
            'groupby' => 'posts.pId',
            'orderby' => 'posts.pId'

        ]));
    }

    

    
    /**
     * @param string $id
     * @return mixed
     */
    public function getPostFeedsByUserId($id =''){
        $where = !empty($id) ? "posts.createdBy = users.id AND posts.pId = chats.postId AND createdBy='$id'" : 'posts.createdBy = users.id AND posts.pId = chats.postId';
      

            return parent::select(parent::encode([
            'table'   => $this->ptables,
            'where'   => $where,
            'cols'    => 'posts.* ,users.username,users.avater,COUNT(chats.postId) AS commentNumber',
            'groupby' => 'posts.pId',
            'orderby' => 'posts.pId'

        ]));
    }


    /**
     * @param string $id
     * @return mixed
     */
    public function getPostProfileByUserId($id =''){
        $where = !empty($id) ? "posts.createdBy = users.id AND posts.pId = chats.postId AND createdBy='$id'" : '';
      

            return parent::select(parent::encode([
            'table'   => $this->ptables,
            'where'   => $where,
            'cols'    => 'posts.* ,users.username,users.avater,COUNT(chats.postId) AS commentNumber',
            'groupby' => 'posts.pId',
            'orderby' => 'posts.pId'

        ]));
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