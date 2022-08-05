
<?php
include_once __DIR__ . "/../Controller/SubscriptionController.php";
include_once __DIR__."/../Util/Util.php";


class Subscription extends SubscriptionController
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

        $user_id = Util::tokenValidate();
        if($this->clean->Method() !== "POST" || !(array) $this->data || !$user_id)
            return array('status'=>403,'payload'=>"Unauthorized Access");
            
        
        $data     = $this->data;
        $subscriptionType   = isset($data->subscriptionType) ? $this->clean->clean_input($data->subscriptionType) : '';
        $categoryId = isset($data->categoryId)  ? $this->clean->clean_input($data->categoryId)       : '';
        $level      = isset($data->level)      ? $this->clean->clean_input($data->level)  : '';
        $activeted  = isset($data->activeted)  ? $this->clean->clean_input($data->activeted)  : '';
        $activeted  = isset($data->activeted)  ? $this->clean->clean_input($data->activeted)  : '';
        $createdBy  = isset($data->createdBy)  ? $this->clean->clean_input($data->createdBy)  : '';


        $status = 401;
        $data = 'All fields are required!';

        // Validate usermedi
        $user = new UsersController();
        $username = ($user->get($user_id))->username;
      
        if(!empty($ceremonyId) && !empty($busnessId)){

            $status = 401;
            $data = 'You Already Hire this Service. Try another Servie pls.. !';
            
            if(!parent::checkPost($id,$subscriptionType)){

              $date = date("Y-m-d H:i:s");
 
               $sql = parent::addPost(parent::encode(array(
               
                "subscriptionType"=>$subscriptionType,
                "categoryId"=>$categoryId,
                'level'=> $level,
                'activeted'=> '0',
                "created_date"=>  $date

            )));

                 if($sql){
                    $status = 200;
                    $data = 'Post added successful!';
                }
   
            }
              

           
              
             // }
        }
      


        return array('status'=>$status, 'payload'=>$data);
    }


    /**
     * Get All Busness 
     * @param $params
     * @return array
     */
    public function get($params){
         
        if($this->clean->Method() !== "GET")
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = parent::decode($params);

        $data = 'No content';
        $status = 401;

         $sql = parent::getFeeds();
      
        if($sql) {
            $status = 200;
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }

     /**
     * Get All Service 
     * @param $params
     * @return array
     */
    
    public function getById(){
         
       if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Access");
            $data     = $this->data;
        $postId   = isset($data->id) ? $this->clean->clean_input($data->id)  : '';

 

        $data = 'No content';
        $status = 401;

         $sql = parent::getService($postId);
      
        if($sql) {
            $status = 200;
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }
  
   /**
     * Get All get Subscription By ID and Type 
     * @param $params
     * @return array
     */
    public function getSubscriptions(){
         
     if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Access");
            $data     = $this->data;
        $id   = isset($data->id) ? $this->clean->clean_input($data->id)  : '';
        $type   = isset($data->type) ? $this->clean->clean_input($data->type)  : '';
 

        $data = 'No content';
        $status = 401;

         $sql = parent::getSubscription($id,$type);
      
        if($sql) {
            $status = 200;
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
        $title = isset($data->title) ? $this->clean->clean_input($data->title) :'';
        $desc = isset($data->description) ? $this->clean->clean_input($data->description) :'';
        $category = isset($data->category) ? $this->clean->clean_input($data->category) : '';
        $phone = isset($data->phone) ? $this->clean->clean_input($data->phone) :'';
        $email = isset($data->email) ? $this->clean->clean_input($data->email) :'';
        $address = isset($data->address) ? $this->clean->clean_input($data->address) :'';
        $media = isset($data->media) ? $this->clean->clean_input($data->media) :'';
        $pos = isset($data->position) ? $this->clean->clean_input($data->position) :'';
        $min = isset($data->min) ? $this->clean->clean_input($data->min) :'';
        $id = isset($data->id) ? $this->clean->clean_input($data->id) :'';
        $max = isset($data->max) ? $this->clean->clean_input($data->max) :'';
        $deadline = isset($data->deadline) && !empty($data->deadline) ? date("Y-m-d", strtotime(str_replace("/", '-',$this->clean->clean_input($data->deadline))))  :'';

        $status = 401;
        $data = 'All fields are required!';

        if(!empty($title) && !empty($category)
            && !empty($address) && !empty($desc) && !empty($media) && !empty($id)){
            $status = 401;

            $featured = '';
            $images = $media;
            if(!empty($images)){
                $featured = explode(',', $images);
                $featured = $featured[0];
            }

            // get category id
            include_once __DIR__."/../Controller/CategoriesController.php";
            $cat = (new CategoriesController())->getLike($category);
            $category = $cat ? $cat->id : '';

            $sql = parent::updatePost(parent::encode(array("title"=>$title,"position"=>$pos,"description"=>$desc, "category"=>$category, "media"=>$images,
                "phone"=>$phone, "email"=>$email,'featured'=>$featured,'address'=>$address,'id'=>$id,'min_amount'=>$min,"max_amount"=>$max,"deadline"=>$deadline)));

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

$class = new Subscription();