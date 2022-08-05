
<?php
include_once __DIR__ . "/../Controller/BusnessController.php";
include_once __DIR__."/../Util/Util.php";
//use BusnessController\BusnessController;

class Busness extends BusnessController
{
    private $data;
    private $clean;
    private $key;

    /**
     * Posts constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->clean = new Util();
        $this->data = isset($_POST) && count($_POST) > 0 ? parent::decode(parent::encode($_POST)) : parent::decode(file_get_contents("php://input"));

        $this->key = getenv("SECRET");
    }


     /**
     * Add Busness
     * @return array
     * @throws Exception
     */
    public function add(){
  
        if($this->clean->Method() !== "POST" || !(array) $this->data )
            return array('status'=>403,'payload'=>"Unauthorized Access");
             $user_id = Util::tokenValidate();

        $data     = $this->data;
        $busnessType   = isset($data->busnessType) ? $this->clean->clean_input($data->busnessType)      : '';
        $coProfile = isset($data->coProfile)  ? $this->clean->clean_input($data->coProfile)       : '';
        $knownAs  = isset($data->knownAs)  ? $this->clean->clean_input($data->knownAs)  : '';
        $price    = isset($data->price)    ? $this->clean->clean_input($data->price)    : '';
        $contact  = isset($data->contact)  ? $this->clean->clean_input($data->contact)  : '';
        $companyName    = isset($data->companyName)  ? $this->clean->clean_input($data->companyName)    : '';
        $ceoId  = isset($data->ceoId)  ? $this->clean->clean_input($data->ceoId)  : '';
        $aboutCEO      = isset($data->aboutCEO)      ? $this->clean->clean_input($data->aboutCEO)      : '';
        $aboutCompany  = isset($data->aboutCompany)  ? $this->clean->clean_input($data->aboutCompany)  : '';
        $location  = isset($data->createdBy)  ? $this->clean->clean_input($data->location)  :'';
        $createdBy  = isset($data->createdBy)  ? $this->clean->clean_input($data->createdBy)  :'';
        
        $hotStatus  = isset($data->hotStatus)  ? $this->clean->clean_input($data->hotStatus)  :'';

        //Subscription Details
        $subscrlevel  = isset($data->subscrlevel)  ? $this->clean->clean_input($data->subscrlevel)  :'';




        $status = 401;
        $data = 'All fields are required!';

        // Validate usermedi
        $user = new UsersController();
        $username = ($user->get($user_id))->username;

        // Add Subscription id
        include_once __DIR__."/../Controller/SubscriptionController.php";
        $subscr = new SubscriptionController();

        if(!empty($busnessType) OR !empty($price) ){
            $status = 401;
            $data = "Sorry a user with the same content exists on our system. Please try another content!";
            $image = Util::base64_to_PNG($coProfile, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/busness/");
            $date = date("Y-m-d H:i:s");

               $sql = parent::addPost(parent::encode(array(
                "busnessType"=>$busnessType,
                "coProfile"=>$image,
                "knownAs"=>$knownAs,
                'price'=>$price,
                "contact"=>$contact,
                "location"=>$location,
                "companyName"=>$companyName,
                "ceoId"=>$ceoId,
                "aboutCEO"=>$aboutCEO,
                "aboutCompany"=>$aboutCompany,
                "createdBy"=>$createdBy,
                "hotStatus"=>$hotStatus,
                "createdDate"=>  $date
            )));

             
          
               $lastId = $sql;

               $data = 'Sorry something went wrong, Please try again!';
                
                if($sql){
                    $status = 200;
                      $sub =  $subscr->addPost(parent::encode(array(
               
                "subscriptionType"=>'busness',
                "categoryId"=>$lastId,
                'level'=> $subscrlevel,
                'activeted'=> '0',
                "created_date"=>  $date

            )));
                    $data = 'Your Busness added successful!';
                }
            
        }
        return array('status'=>$status, 'payload'=>$data);
    } 

    /**
     * Get All Busness 
     * @param $params
     * @return array
     */
    public function getBusness($params){
         
        if($this->clean->Method() !== "GET")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

        $data = parent::decode($params);

        $data = 'No content';
        $status = 401;

         $sql = parent::getFeeds();
      
       // get Subscription id
        include_once __DIR__."/../Controller/SubscriptionController.php";
        $subscr = new SubscriptionController();
        if($sql) {
            $status = 200;

            foreach($sql as $key=>$v){
             $busnessId = $sql[$key]['bId'];
             $sub = $subscr->getSubscription( $busnessId,'busness');
             @$sql[$key]['subcrlevel'] =  $sub->level;
            }
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }

     /**
     * Get Busness By busness Type 
     * @param $params
     * @return array
     */
    public function getByBusnessType(){
         
       if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Access");
            $data     = $this->data;
        $btype   = isset($data->type) ? $this->clean->clean_input($data->type)  : '';
        $id   = isset($data->id) ? $this->clean->clean_input($data->id)  : '';

 
       // print_r($busnessType );
        $data = 'No content';
        $status = 401;

        $sql = parent::getBusnessType($type = $btype);
      
        // get Subscription id
        include_once __DIR__."/../Controller/SubscriptionController.php";
        $subscr = new SubscriptionController();
        if($sql) {
            $status = 200;

            foreach($sql as $key=>$v){
             $busnessId = $sql[$key]['bId'];
             $sub = $subscr->getSubscription( $busnessId,'busness');
             @$sql[$key]['subcrlevel'] =  $sub->level;
            }
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }
  
    /**
     * Get Busness By CeoId  
     * @param $params
     * @return array
     */
    public function getByBusnessCeoId(){
         
       if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Access");
            $data     = $this->data;
         $ceoId   = isset($data->ceoId) ? $this->clean->clean_input($data->ceoId)  : '';

 
       // print_r($busnessType );
        $data = 'No content';
        $status = 401;

         $sql = parent::getFeeds($id = $ceoId,$type = $busnessType);
      
         // get Subscription id
        include_once __DIR__."/../Controller/SubscriptionController.php";
        $subscr = new SubscriptionController();
        if($sql) {
            $status = 200;

            foreach($sql as $key=>$v){
             $busnessId = $sql[$key]['bId'];
             $sub = $subscr->getSubscription( $busnessId,'busness');
             @$sql[$key]['subcrlevel'] =  $sub->level;
            }
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }

    /**
     * Get Busness By CreatorId  
     * @param $params
     * @return array
     */
    public function bsnByCreatorId(){
         
       if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Access");
            $data     = $this->data;
         $id   = isset($data->id) ? $this->clean->clean_input($data->id)  : '';

 
       // print_r($busnessType );
        $data = 'No content';
        $status = 401;

         $sql = parent::getBsnByCritorId($id = $id);
      
         // get Subscription id
        include_once __DIR__."/../Controller/SubscriptionController.php";
        $subscr = new SubscriptionController();
        if($sql) {
            $status = 200;

            foreach($sql as $key=>$v){
             $busnessId = $sql[$key]['bId'];
             $sub = $subscr->getSubscription( $busnessId,'busness');
             @$sql[$key]['subcrlevel'] =  $sub->level;
            }
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }
  

    /**
     * Get All Busness 
     * @param $params
     * @return array
     */
    public function getMembers(){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

        $data     = $this->data;
        $postId   = isset($data->id) ? $this->clean->clean_input($data->id)  : '';



        $data = 'No content';
        $status = 401;
       
         $sql = parent::getMember($postId);
      
        if($sql) {
            $status = 200;
            foreach($sql as $key=>$v)
                $sql[$key]["createdDate"] = Util::ago($v["createdDate"]);
            $data = $sql;
        }
    
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }
    /**
     * Get All Busness 
     * @param $params
     * @return array
     */
    public function getPhoto(){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

        $data     = $this->data;
        $postId   = isset($data->id) ? $this->clean->clean_input($data->id)  : '';



        $data = 'No content';
        $status = 401;

         $sql = parent::getPhotos($postId);
      
        if($sql) {
            $status = 200;
            foreach($sql as $key=>$v)
                $sql[$key]["createdDate"] = Util::ago($v["createdDate"]);
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }

    /**
     * @param string $a
     * @return array
     * @throws Exception
     */
    public function update($a ='')
    {
        $user_id = Util::tokenValidate();
        if($this->clean->Method() !== "POST" || !(array) $this->data || !$user_id)
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = $this->data;
          $bId   = isset($data->bId) ? $this->clean->clean_input($data->bId)      : '';
        $busnessType   = isset($data->busnessType) ? $this->clean->clean_input($data->busnessType)      : '';
        
        $coProfile = isset($data->coProfile)  ? $this->clean->clean_input($data->coProfile)       : '';
        $oldCoProfile = isset($data->oldCoProfile)  ? $this->clean->clean_input($data->oldCoProfile)       : '';
       
        $knownAs  = isset($data->knownAs)  ? $this->clean->clean_input($data->knownAs)  : '';
        $price    = isset($data->price)    ? $this->clean->clean_input($data->price)    : '';
        $contact  = isset($data->contact)  ? $this->clean->clean_input($data->contact)  : '';
        $companyName    = isset($data->companyName)  ? $this->clean->clean_input($data->companyName)    : '';
        $ceoId  = isset($data->ceoId)  ? $this->clean->clean_input($data->ceoId)  : '';
        $aboutCEO      = isset($data->aboutCEO)      ? $this->clean->clean_input($data->aboutCEO)      : '';
        $aboutCompany  = isset($data->aboutCompany)  ? $this->clean->clean_input($data->aboutCompany)  : '';
        $location  = isset($data->createdBy)  ? $this->clean->clean_input($data->location)  :'';
        $createdBy  = isset($data->createdBy)  ? $this->clean->clean_input($data->createdBy)  :'';
        
        $hotStatus  = isset($data->hotStatus)  ? $this->clean->clean_input($data->hotStatus)  :'';

        //Subscription Details
        $subscrlevel  = isset($data->subscrlevel)  ? $this->clean->clean_input($data->subscrlevel)  :'';

        $status = 401;
        $data = 'All fields are required!';

        if(!empty($bId) ){
            $status = 401;


        if(!empty($coProfile)){
               $img = Util::base64_to_PNG($coProfile, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/busness/ ");
           }else{
             $img = $oldCoProfile;
           }


            $sql = parent::updatePost(parent::encode(array(
               
                "bId"=>$bId,
                "busnessType"=>$busnessType,
                "coProfile"=>$img,
                "knownAs"=>$knownAs,
                'price'=>$price,
                "contact"=>$contact,
                "location"=>$location,
                "companyName"=>$companyName,
                "ceoId"=>$ceoId,
                "aboutCEO"=>$aboutCEO,
                "aboutCompany"=>$aboutCompany,
                "createdBy"=>$createdBy,
                "hotStatus"=>$hotStatus,
                "createdDate"=>  $date

            )));

            $data = 'Sorry something went wrong, Please try again!';
            if($sql){
                $status = 200;
                $data = 'Post update successful!';
            }
        }

        return array('status'=>$status, 'payload'=>$data);
    }

   
    /**
     * @param $params
     * @return array
     */
    public function postMy($params){

        if($this->clean->Method() !== "GET")
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = parent::decode($params);
        $category = $cat->id;

        $data = 'No content';
        $status = 401;

        $sql = parent::getFeeds();

        print_r($sql);
        if($sql) {

            $status = 200;

            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }


    /**
     * @param $params
     * @return array
     * @throws Exception
     */
    public function remove($params){
        $user_id = Util::tokenValidate();
        if($this->clean->Method() !== "POST"  && $user_id === null && !empty($user_id))
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $status = 403;
        $data = 'Unauthorized Access';
        if(!empty($user_id) && !empty($this->data->id)) {
            $sql = parent::deletePost($this->data->id, $user_id);

            if($sql){
                $status = 200;
                $data = "";
            }
        }
        return array('status'=>$status, 'payload'=>$data);
    }

    /**
     * @param $params
     */
    public function my_post($params){
        $user_id = Util::tokenValidate();

        if($this->clean->Method() !== "GET" || !$user_id)
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $num = '';
        $page ='';
        $category ='';
        if(is_array((array) $this->data)){
            $data = parent::decode($params);

            $category = isset($data->page) && !empty($data->page) ? $data->page :'';
            $num = isset($data->page1) && !empty($data->page1) ? $data->page1 :'';
            $page = isset($data->page2) && $data->page2 !== '' ? $data->page2 :'';
        }

        $not = false;
        if(!empty($category)){
            $not = preg_match( '/!/', $category);
        }

        // get category id
        include_once __DIR__."/../Controller/UsersController.php";
        include_once __DIR__."/../Controller/CategoriesController.php";
        $cat = (new CategoriesController())->getLike(str_replace('!','',$category));
        $category = $cat->id;

        $limit ="$page,$num";

        $sql = parent::get_post_by_user($user_id,$category,$not, $limit);

        $data = "No content!";
        $data = $category;
        if($sql) {

            $status = 200;
            $userd = new UsersController();
            foreach ($sql as $key=>$value){
                $cat = (new CategoriesController())->getLike($value['category']);
                $sql[$key]['category'] = $cat->name;
                $user = ($userd->get($sql[$key]['user']));
                $username = $user->username;
                $avatar = $user->avatar;
                $sql[$key]['user'] = $username;
                $sql[$key]['avatar'] = $avatar;
                $sql[$key]['description'] = nl2br($sql[$key]['description']);
                $sql[$key]['created_at'] = Util::ago($sql[$key]['created_at']);
                $sql[$key]['deadline'] = date("M, d Y", strtotime($sql[$key]['deadline']));
                $sql[$key]['min_amount'] = (int) $sql[$key]['min_amount'];
                $sql[$key]['max_amount'] = (int) $sql[$key]['max_amount'];
                $total = Util::short_number($sql[$key]['max_amount'] + $sql[$key]['min_amount']) ;
                $sql[$key]['total'] = $total;
                $sql[$key]['featured'] = "public/uploads/$username/data/".$sql[$key]['featured'];
                $m = explode(',',$sql[$key]['media']);
                $md = '';
                if(count($m) > 0){
                    for ($x = 0; $x <= count($m)-1; $x++) {
                        if($m[$x] !== '')
                            $md .= "public/uploads/$username/data/".$m[$x].',';
                    }
                    $sql[$key]['media'] = chop($md,',');
                }
            }

            $data = $sql;
        }

        return array('status'=>$status, 'payload'=>$data);
    }

    
}

$class = new Busness();