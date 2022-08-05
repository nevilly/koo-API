<?php

include_once __DIR__."/../Module/Database.php";

class BusnessModule extends \Database
{

    private $table = "busness";
    private $busnessTable    =  "busness,users";

    private $busnessMembersTable    =  "busnessstaff,users,busness";
    private $busnessPhotoTable    =  "busnessphoto,busness";

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

          return ($status) ? $this->con->insert_id : $status;
    }

    

    /**
     * @param $title
     * @return mixed
     */
    public function checkPost($title){
        return parent::select(parent::encode(array("table"=>$this->table,'cols'=>"id",'where'=>"    busnessId Like '%$title'", 'limit'=>1)));
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
    public function getPostFeeds($id ='',$type = ''){

        $where = !empty($id)? "busness.createdBy = users.id AND busness.bId='$id'" :'busness.createdBy = users.id';
       
        $where = !empty($type)? "busness.createdBy = users.id  AND busness.busnessType='$type'" : $where;
      
       $where = !empty($type) && $type == 'My_Busness'? "busness.createdBy = users.id  AND busness.busnessType='$type'" : $where;
      
        $orderBy = !empty($id) ? 'busness.bId' : 'RAND()';
        //    return parent::select(parent::encode([
        //     'table'   => $this->postsUsers,
        //     'where'   => 'post.creatorId = users.id',
        //     'cols'    => 'users.username,users.avatar,post.*',
        //     'orderby' => 'post.id'
        // ]));

        return parent::select(parent::encode([
            'table'   => $this->busnessTable,
            'where'   => $where,
            'cols'    => 'users.username,users.avater,busness.*',
            'orderby' => $orderBy
        ]));


    }



     /**
     * @param string $id
     * @return mixed
     */
    public function getBsnType($type =''){
        $where = !empty($type)? "busness.createdBy = users.id AND busness.busnessType='$type'" :'busness.createdBy = users.id';
        $orderBy = !empty($id) ? 'busness.bId' : 'RAND()';

        return parent::select(parent::encode([
            'table'   => $this->busnessTable,
            'where'   => $where,
            'cols'    => 'users.username,users.avater,busness.*',
            'orderby' => $orderBy
        ]));

    }


     /**
     * @param string $id
     * @return mixed
     */
    public function getBsnByCreatorId($id =''){
        $where = !empty($id)? "busness.createdBy = users.id AND busness.createdBy='$id'" :'';
        $orderBy = !empty($id) ? 'busness.bId' : 'RAND()';

        return parent::select(parent::encode([
            'table'   => $this->busnessTable,
            'where'   => $where,
            'cols'    => 'users.username,users.avater,busness.*',
            'orderby' => $orderBy
        ]));

    }



     /**
     * @param string $id
     * @return mixed
     */
    public function getbusnessMember($id =''){
        // $where = !empty($id)? "id='$id'" :'';
        
        if(!empty($id)){
            
            return parent::select(parent::encode([
               
                'table'=>$this->busnessMembersTable,

                'where'=>'busnessstaff.bId = busness.bId AND
                          busnessstaff.userId = users.id AND
                          busnessstaff.bId ='.$id,
                
                'cols'    =>  'busnessstaff.*,users.username,users.avater
                              ',

                'orderby' =>  'busnessstaff.stId'

                 ]));

        }else{
           
            return parent::select(
                parent::encode(
                [
              'table'=>$this->busnessMembersTable,

                'where'=>'
                          busnessstaff.bId = busness.bId AND
                          busnessstaff.userId = users.id',
                
                'cols'    =>  'busnessstaff.*,users.username,users.avater
                              ',

                'orderby' =>  '.busnessstaffstId'
                ]
            ));
        }




        // if(!empty($id)){

        //   return parent::select(parent::encode([
           
        //     'table'=>$this->serviceBusnessTable,

        //     'where'=>'
        //               hostlist.busnessId = busness.bId AND
        //               hostlist.ceremonyId = ceremony.cId AND
        //               hostlist.hostId ='.$id,
            
        //     'cols'    =>  'hostlist.*, 
        //                    ceremony.cId,
        //                    ceremony.codeNo,
        //                    ceremony.cName,
        //                    ceremony.ceremonyType,
        //                    ceremony.cImage
        //                   ',

        //     'orderby' =>  'hostlist.hostId'

        //      ]));    
   
        //  }else{
        //     return parent::select(parent::encode([
        //     'table'   => $this->busnessTable,
        //     'where'   => 'busness.createdBy = users.id',
        //     'cols'    => 'users.username,users.avater,busness.*',
        //     'orderby' => 'RAND()'
        // ]));
        // }
    
    }



    /**
     * @param string $id
     * @return mixed
    */
    public function getbusnessPhoto($id =''){
        // $where = !empty($id)? "id='$id'" :'';
        
        if(!empty($id)){
        
        return parent::select(parent::encode([
           
            'table'=>$this->busnessPhotoTable,

            'where'=>'
                      busnessphoto.bId = busness.bId AND
                      busness.bId ='.$id,
            
            'cols'    =>  'busnessphoto.*,busness.bId
                          ',

            'orderby' =>  'busnessphoto.bPhotoId'

             ]));

        }else{
        return parent::select(
            parent::encode(
            [
            'table'   =>  $this->busnessPhotoTable,
            
            'where'   =>  'busnessphoto.bId = busness.bId',
           
            'cols'    =>  'busnessphoto.*,busness.bId',

            'orderby' =>  'busnessphoto.bPhotoId'
            ]
        ));
        }

        
    
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
                if(!empty($value) && $key != 'bId')
                    $d .= "$key='$value',";
            }

            $d = chop($d,',');
            $id = $data['bId'];

            $status = parent::update(parent::encode(array("table"=>$this->table,"data"=>"$d",'where'=>"bId='$id'")));
        }

        return $status;
    }

    public function deletePost($id, $user)
    {
        $where = !empty($id) && !empty($user) ? "id='$id' AND user='$user'" :'';

        return parent::delete(parent::encode(['table'=>$this->table,'where'=>$where]));
    }
}