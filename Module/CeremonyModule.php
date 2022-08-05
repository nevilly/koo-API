<?php

include_once __DIR__."/../Module/Database.php";

class CeremonyModule extends \Database
{

    private $table = "ceremony";
    private $ceremonyUsers = "ceremony,users";
    private $ceremoniestable = "ceremony crm, users u1, users u2";

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
        $where = !empty($id)? " crm.fId = u1.id AND crm.sId = u2.id AND cId='$id'" :'crm.fId = u1.id AND crm.sId = u2.id';

        //    return parent::select(parent::encode([
        //     'table'   => $this->postsUsers,
        //     'where'   => 'post.creatorId = users.id',
        //     'cols'    => 'users.username,users.avatar,post.*',
        //     'orderby' => 'post.id'
        // ]));

        //          return parent::select(parent::encode([
        //     'table'   => $this->busnessTable,
        //     'where'   => 'busness.createdBy = users.id',
        //     'cols'    => 'users.username,users.avater,busness.*',
        //     'orderby' => 'busness.bid'
        // ]));
        
       
        return parent::select(parent::encode(
            [
            'table'   => $this->ceremoniestable,
            'where'   => $where,
            'cols'    => 'crm.*,
            u1.username u1,
                          u1.avater u1Avt,
                          u1.firstname u1Fname,
                          u1.lastname u1Lname,
                          u1.gender u1g,

                          u2.username u2,
                          u2.avater u2Avt,
                          u2.firstname u2Fname,
                          u2.lastname u2Lname,
                          u2.gender u2g',
            'orderby' => 'crm.cid'
        ]
    )
    );
  
    }

 /**
     * @param string $id
     * @return mixed
     */
    public function geCeremonyByUserId($id =''){
        $where = !empty($id)? " crm.fId = u1.id AND crm.sId = u2.id AND fId='$id' OR sId ='$id'" :'crm.fId = u1.id AND crm.sId = u2.id';
        return parent::select(parent::encode(
            [
            'table'   => $this->ceremoniestable,
            'where'   => $where,
            'cols'    => 'crm.*,
            u1.username u1,
                          u1.avater u1Avt,
                          u1.firstname u1Fname,
                          u1.lastname u1Lname,
                          u1.gender u1g,

                          u2.username u2,
                          u2.avater u2Avt,
                          u2.firstname u2Fname,
                          u2.lastname u2Lname,
                          u2.gender u2g',
            'orderby' => 'crm.cid'
        ]
    )
    );
  
    }


     public function getCeremoniesByType($d =''){
        $where = !empty($d) && $d != 'all' ? " crm.fId = u1.id AND crm.sId = u2.id AND ceremonyType='$d'" :'crm.fId = u1.id AND crm.sId = u2.id';

       
        return parent::select(parent::encode(
            [
            'table'   => $this->ceremoniestable,
            'where'   => $where,
            'cols'    => 'crm.*,
            u1.username u1,
                          u1.avater u1Avt,
                          u1.firstname u1Fname,
                          u1.lastname u1Lname,
                          u1.gender u1g,

                          u2.username u2,
                          u2.avater u2Avt,
                          u2.firstname u2Fname,
                          u2.lastname u2Lname,
                          u2.gender u2g',
            'orderby' => 'RAND()'
        ]
    )
    );
  
    }


  public function getPostCeremonies($day =''){

      
      if(!empty($day) && $day == 'Upcoming') {
        $where = "crm.fId = u1.id AND crm.sId = u2.id AND ceremonyDate >= CURDATE()";
      }
     
        if(!empty($day) && $day == 'Today') {
        $where =  "crm.fId = u1.id AND crm.sId = u2.id AND ceremonyDate = CURDATE()";
      }

     if(!empty($day) && $day == 'Past') {
        $where =  "crm.fId = u1.id AND crm.sId = u2.id AND ceremonyDate < CURDATE()";
      }

        return parent::select(parent::encode(
            [
            'table'   => $this->ceremoniestable,
            'where'   => $where,
            'cols'    => 'crm.*,
            u1.username u1,
                          u1.avater u1Avt,
                          u1.firstname u1Fname,
                          u1.lastname u1Lname,
                          u1.gender u1g,

                          u2.username u2,
                          u2.avater u2Avt,
                          u2.firstname u2Fname,
                          u2.lastname u2Lname,
                          u2.gender u2g',
            'orderby' => 'crm.cid'
        ]
    )
    );
  
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
                if(!empty($value) && $key != 'cId')
                    $d .= "$key='$value',";
            }

            $d = chop($d,',');
            $id = $data['cId'];

            $status = parent::update(parent::encode(array("table"=>$this->table,"data"=>"$d",'where'=>"cId='$id'")));
        }

        return $status;
    }

    public function deletePost($id, $user)
    {
        $where = !empty($id) && !empty($user) ? "id='$id' AND user='$user'" :'';

        return parent::delete(parent::encode(['table'=>$this->table,'where'=>$where]));
    }
}