<?php

include_once __DIR__."/../Module/Database.php";

class SubscriptionModule extends \Database
{

    private $table = "subscription";
    private $serviceTable  =  "hostlist, ceremony, busness";
    private $invatationTable  =  "subscription, ceremony, busness ,users userCrm ,users UserBsn";
     private $subscriptionCrmTable  =  "subscription, ceremony ,users userCrm1,users userCrm2";
    private $subscriptionBsnTable  =  "subscription, busness ,users UserBsn";

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
    public function checkPost($id,$subscriptionType){


        return parent::select(parent::encode(array("table"=>$this->table,'cols'=>"hostId",'where'=>"categoryId = '$id' AND subscriptionType = '$subscriptionType'", 
             'orderby' => 'hostId',
        	'limit'=>1)));
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



        if(!empty($id)){

          return parent::select(parent::encode([
           
            'table'=>$this->serviceBusnessTable,

            'where'=>'
                      hostlist.busnessId = busness.bId AND
                      hostlist.ceremonyId = ceremony.cId AND
                      hostlist.hostId ='.$id,
            
            'cols'    =>  'hostlist.*, 
                           ceremony.cId,
                           ceremony.codeNo,
                           ceremony.cName,
                           ceremony.ceremonyType,
                           ceremony.cImage
                          ',

            'orderby' =>  'hostlist.hostId'

             ]));    
   
         }else{

         	  return parent::select(
            parent::encode(
            [
            'table'   =>  $this->serviceBusnessTable,
            
            'where'   =>  'hostlist.busnessId  = busness.bId AND
                           hostlist.ceremonyId = ceremony.cId',
           
            'cols'    =>  'hostlist.*, 
                           ceremony.cId,
                           ceremony.codeNo,
                           ceremony.cName,
                           ceremony.ceremonyType,
                           ceremony.cImage
                          ',

            'orderby' =>  'hostlist.hostId'
            ]
        ));
       
              
         }


        
    }
 /**
     * @param string $id
     * @return mixed
     */
    public function getPostFeeds($id =''){
        $where = !empty($id)? "id='$id'" :'';

        //    return parent::select(parent::encode([
        //     'table'   => $this->postsUsers,
        //     'where'   => 'post.creatorId = users.id',
        //     'cols'    => 'users.username,users.avatar,post.*',
        //     'orderby' => 'post.id'
        // ]));

            return parent::select(parent::encode([
            'table'   => $this->tables,
            'where'   => 'posts.createdBy = users.id AND posts.pId = chats.postId',
            'cols'    => 'posts.* ,users.username,users.avater,COUNT(chats.postId) AS commentNumber',
            'groupby' => 'posts.pId',
            'orderby' => 'posts.pId'

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


 /**
     * @param string $id
     * @return mixed
     */
    public function getPostService($id =''){
         $where = !empty($id)? "hostlist.busnessId = busness.bId AND hostlist.ceremonyId = ceremony.cId AND hostlist.hostId ='$id'" : "hostlist.busnessId = busness.bId AND
	                           hostlist.ceremonyId = ceremony.cId";
       
	        return parent::select(parent::encode([
	           
	            'table'=>$this->serviceTable,

	            'where'=> $where ,
	            
	            'cols'    =>  'hostlist.*, 
	                           ceremony.cId,
	                           ceremony.codeNo,
	                           ceremony.cName,
	                           ceremony.ceremonyType,
	                           ceremony.cImage
	                          ',

	            'orderby' =>  'hostlist.hostId'

	             ]));
        
    }


 /**
     * @param string $id
     * @return mixed
     */
    public function getPostSubscription($id ='', $type = ''){
         // $where = !empty($id)? "hostlist.busnessId = busness.bId AND hostlist.ceremonyId = ceremony.cId AND hostlist.hostId ='$id'" : "hostlist.busnessId = busness.bId AND
	        //                    hostlist.ceremonyId = ceremony.cId";
     

       
       if(!empty($type) && $type == 'busness'){
	        return parent::select(parent::encode([
	        'table'=>$this->subscriptionBsnTable,

	        'where'=> "subscription.categoryId = '$id' AND busness.ceoId = UserBsn.id AND subscription.subscriptionType = '$type'" ,
	        
	        'cols'    =>  'subscription.*, 
	                       
	                       busness.bId,
	                       busness.busnessType,
	                       busness.coProfile,
	                       busness.knownAs,
	                       busness.coProfile,
	                       busness.price,
	                       busness.contact,
	                       busness.location,
	                       busness.ceoId,

	                       UserBsn.avater bsnAvater,
	                       UserBsn.username bsnUname
	                      ',

	        'orderby' =>  'subscription.subId'
	         ]));
       }

  
   
       if(!empty($type) && $type == 'ceremony'){
	        return parent::select(parent::encode([
	        'table'=>$this->subscriptionCrmTable,


	        'where'=> "subscription.categoryId = '$id' AND ceremony.fId = userCrm1.id AND ceremony.sId = userCrm2.id AND subscription.subscriptionType	 = '$type'" ,
	        
	        'cols'    =>  'subscription.*, 
	                       ceremony.cId,
	                       ceremony.codeNo,
	                       ceremony.cName,
	                       ceremony.ceremonyType,
	                       ceremony.cImage,
	                       ceremony.fId,
	                       ceremony.sId,
	                       userCrm1.avater fIdAvater,
	                       userCrm1.username fIdUname,
	                       userCrm2.avater sIdAvater,
	                       userCrm2.username sIdUname
	                      ',

	        'orderby' =>  'subscription.subId'
	         ]));
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