
<?php
include_once __DIR__ . "/../Controller/MyPostController.php";
include_once __DIR__."/../Util/Util.php";


class MyPost extends MyPostController
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
        $ceremonyId   = isset($data->ceremonyId) ? $this->clean->clean_input($data->ceremonyId) : '';
        $vedeo = isset($data->vedeo)  ? $this->clean->clean_input($data->vedeo)       : '';
        $createdBy  = isset($data->createdBy)  ? $this->clean->clean_input($data->createdBy)  : '';
        $body  = isset($data->body)  ? $this->clean->clean_input($data->body)  : '';


        $status = 401;
        $data = 'All fields are required!';

        // Validate usermedi
        $user = new UsersController();
        $username = ($user->get($user_id))->username;
      
        if(!empty($vedeo) ){

            $status = 401;
            $data = 'Sorry something went wrong, Please try again!';

            $vdeo = Util::base64_to_PNG($vedeo, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/posts/");
            $date = date("Y-m-d H:i:s");

               $sql = parent::addPost(parent::encode(array(
                "createdBy"=>$user_id,
                "body"=>$body,
                "vedeo"=>$vdeo,
                'ceremonyId'=>$ceremonyId,
                "createdDate"=>  $date

            )));

           
                if($sql){
                    $status = 200;
                    $data = 'Post added successful!';
                }
             // }
        }
      


        return array('status'=>$status, 'payload'=>$data);
    }


     /**
     * @return array
     * @throws Exception
     */
    public function addVedeo(){
        $user_id = Util::tokenValidate();
        
        if($this->clean->Method() !== "POST" || !(array) $this->data || !$user_id)
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = $this->data;
        print_r($_FILES["media"]);
        //$body = isset($data->body) ? $this->clean->clean_input($data->body) :'';
     
       //  $desc = isset($data->description) ? $this->clean->clean_input($data->description) :'';
       //  $category = isset($data->category) ? $this->clean->clean_input($data->category) : '';
       //  $phone = isset($data->phone) ? $this->clean->clean_input($data->phone) :'';
       //  $email = isset($data->email) ? $this->clean->clean_input($data->email) :'';
       //  $pos = isset($data->position) ? $this->clean->clean_input($data->position) :'';
       //  $address = isset($data->address) ? $this->clean->clean_input($data->address) :'';
       //  $min = isset($data->min) ? $this->clean->clean_input($data->min) :'';
       //  $max = isset($data->max) ? $this->clean->clean_input($data->max) :'';
       //  $deadline = isset($data->deadline) && !empty($data->deadline) ? date("Y-m-d", strtotime(str_replace("/", '-',$this->clean->clean_input($data->deadline))))  :'';

        $status = 401;
        $data = 'All fields are required!';
        
        print($_FILES["media"]['name']);
        // Validate user
        // $user = new UsersController();

        // if(!empty($body)  && count($_FILES["media"]['name']) > 0){
        //     $status = 401;
        //     $data = "Sorry a user with the same content exists on our system. Please try another content!";

        //     // process images
        //     // extract featured image
        //     // export list images for media column
        //     $featured = '';
        //     $vedeoDir = '';
        //     $username = ($user->get($user_id))->username;
        //     $files = Util::Uploader("media","/public/uploads/$username/vedeos/")['file'];
        //     if(!empty($files)){
        //         $featured = explode(',', $files);
        //         $featured = $featured[0];
        //         $vedeoDir = $files;
        //     }

        //     // get category id
        //     // include_once __DIR__."/../Controller/CategoriesController.php";
        //     // $cat = (new CategoriesController())->getLike($category);
        //     // $category = $cat ? $cat->id : '';

        //     // if(!parent::checkPost($body) && !empty($files)){
        //         // $sql = parent::addPost(parent::encode(array("title"=>$title,"description"=>$desc, "category"=>$category, "media"=>$images,
        //         //     "phone"=>$phone, "email"=>$email,'featured'=>$featured,"position"=>$pos,'address'=>$address,'min_amount'=>$min,"max_amount"=>$max,"user"=>$user_id, "deadline"=>$deadline)));

        //            $date = date("Y-m-d H:i:s");

        //        $sql = parent::addPost(parent::encode(array(
        //         "createdBy"=>$user_id,
        //         "body"=>'',
        //         "vedeo"=>$vedeoDir,
        //         'ceremonyId'=>'',
        //         "createdDate"=>  $date

        //     )));


        //         $data = 'Sorry something went wrong, Please try again!';
        //         if($sql){
        //             $status = 200;
        //             $data = 'Post added successful!';
        //         }
        //     // }
        // }

        return array('status'=>$status, 'payload'=>$data);
    }


    /**
     * Get All Post 
     * @param $params
     * @return array
     */
    public function get($params){
         
        if($this->clean->Method() !== "GET")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

        $data = parent::decode($params);

        $data = 'No content';
        $status = 401;

         $sql = parent::getFeeds();
      
          include_once __DIR__."/../Controller/CeremonyController.php";
           include_once __DIR__."/../Controller/UsersController.php";
      
      
        if($sql) {

            $status = 200;
            foreach($sql as $key=>$v) {
                if($sql[$key]['ceremonyId'] != '0'){
                
                $CeremonyInfo =  (new CeremonyController())->getFeeds($sql[$key]['ceremonyId']);
                 @$sql[$key]['cId']   = $CeremonyInfo ? $CeremonyInfo->cId      : "";
                 @$sql[$key]['cImage']   = $CeremonyInfo ? $CeremonyInfo->cImage      : "";
                 @$sql[$key]['fId']   = $CeremonyInfo ? $CeremonyInfo->fId      : "";
               
                $userInfo =  (new UsersController())->get($sql[$key]['fId']);
                @$sql[$key]['crmUsername']   = $userInfo ? $userInfo->username      : "";


               }
            }
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }

    
    /**
     * Get All Post By CeremonyId 
     * @param $params
     * @return array
     */
    public function getPostByCrmId($params){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

            $data = $this->data;
        $ceremonyId  = isset($data->ceremonyId) ? $this->clean->clean_input($data->ceremonyId) :'';

        $data = parent::decode($params);

        $data = 'No content';
        $status = 401;

        $sql = parent::getPostCeremonyFeed($ceremonyId);
      
      
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
     * Get All Post By UserId 
     * @param $params
     * @return array
     */
    public function getPostByUid($params){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

        $data = $this->data;
        $userId  = isset($data->userId) ? $this->clean->clean_input($data->userId) :'';

        $data = parent::decode($params);

        $data = 'No content';
        $status = 401;

        $sql = parent::getPostUserIdFeed($userId);
      
      
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
     * Get All Post By UserId 
     * @param $params
     * @return array
     */
    public function getProfileByUid(){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");
        $Sid = '';
        $data = $this->data;
        $userId  = isset($data->userId) ? $this->clean->clean_input($data->userId) :'';

        // $data = parent::decode($params);
         if(!empty($userId)){
            $Sid = $userId;
        }else{
            $Sid =  Util::tokenValidate();;
        }

     

        $data = 'No content';
        $status = 401;

        $sql = parent::getProfileUserIdFeed($Sid);
      
      
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

$class = new MyPost();