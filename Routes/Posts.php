<?php
include_once __DIR__ . "/../Controller/PostController.php";
include_once __DIR__."/../Util/Util.php";
//use PostsController\PostsController;

class Posts extends PostController
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
     * @return array
     * @throws Exception
     */
    public function add(){
       // $user_id = Util::tokenValidate();
        
       //  if($this->clean->Method() !== "POST" || !(array) $this->data || !$user_id)
       //      return array('status'=>403,'payload'=>"Unauthorized Access");

       //  $data = $this->data;
       //  $title = isset($data->title) ? $this->clean->clean_input($data->title) :'';
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

       //  // Validate user
       //  $user = new UsersController();

       //  if(!empty($title) && !empty($category)
       //      && !empty($address) && !empty($desc) && count($_FILES["media"]['name']) > 0){
       //      $status = 401;
       //      $data = "Sorry a user with the same content exists on our system. Please try another content!";

       //      // process images
       //      // extract featured image
       //      // export list images for media column
       //      $featured = '';
       //      $images = '';
       //      $username = ($user->get($user_id))->username;
       //      $files = Util::Uploader("media","/public/uploads/$username/data/")['file'];
       //      if(!empty($files)){
       //          $featured = explode(',', $files);
       //          $featured = $featured[0];
       //          $images = $files;
       //      }

       //      // get category id
       //      include_once __DIR__."/../Controller/CategoriesController.php";
       //      $cat = (new CategoriesController())->getLike($category);
       //      $category = $cat ? $cat->id : '';

       //      if(!parent::checkPost($title) && !empty($files) && !empty($category)){
       //          $sql = parent::addPost(parent::encode(array("title"=>$title,"description"=>$desc, "category"=>$category, "media"=>$images,
       //              "phone"=>$phone, "email"=>$email,'featured'=>$featured,"position"=>$pos,'address'=>$address,'min_amount'=>$min,"max_amount"=>$max,"user"=>$user_id, "deadline"=>$deadline)));

       //          $data = 'Sorry something went wrong, Please try again!';
       //          if($sql){
       //              $status = 200;
       //              $data = 'Post added successful!';
       //          }
       //      }
       //  }

        return array('status'=>$status, 'payload'=>$data);
    }


    /**
     * @return array
     * @throws Exception
     */
    public function addPosts(){
        
        print_r('ninafikaa');
        // if($this->clean->Method() !== "PUT" || !(array) $this->data )
        //     return array('status'=>403,'payload'=>"Unauthorized Accessss");
        //      $user_id = Util::tokenValidate();

        // $data  = $this->data;
        // $title = isset($data->title) ? $this->clean->clean_input($data->title) : '';
        // $body  = isset($data->body)  ? $this->clean->clean_input($data->body)  : '';
        // $role  = isset($data->role)  ? $this->clean->clean_input($data->role)  : '';
        // $media  = isset($data->media)  ? $this->clean->clean_input($data->media)  : '';
        // $creatorId = isset($data->creatorId) ? $this->clean->clean_input($data->creatorId) :'';


        $status = 401;
        $data = 'All fields are required!';

        // Validate usermedi
        // $user = new UsersController();
        // $username = ($user->get($user_id))->username;
        // if(!empty($body) OR !empty($media) ){
        //     $status = 401;
        //     $data = "Sorry a user with the same content exists on our system. Please try another content!";
        //     $image = Util::base64_to_PNG($media, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/post/");
        //     $date = date("Y-m-d H:i:s");
        
        //    if(!parent::checkPost($body)){

        //        $sql = parent::addPost(parent::encode(array("title"=>$title,"body"=>$body, "img"=>$image,'role'=>$role,"creatorId"=>$creatorId,"createdDate"=>  $date)));

        //        $data = 'Sorry something went wrong, Please try again!';
        //         if($sql){
        //             $status = 200;
        //             $data = 'Post added successful!';
        //         }
        //     }
        // }
        return array('status'=>$status, 'payload'=>$data);
    }


     /**
     * Add Ibada/Events 
     * @return array
     * @throws Exception
     */
    public function addIbada(){

        if($this->clean->Method() !== "PUT" || !(array) $this->data )
            return array('status'=>403,'payload'=>"Unauthorized Accessss");
             $user_id = Util::tokenValidate();

        $data       = $this->data;
        $title      = isset($data->title)      ? $this->clean->clean_input($data->title)      : '';
        $body       = isset($data->body)       ? $this->clean->clean_input($data->body)       : '';
        $startDate  = isset($data->startDate)  ? $this->clean->clean_input($data->startDate)  : '';
        $endDate    = isset($data->endDate)    ? $this->clean->clean_input($data->endDate)    : '';
        $startTime  = isset($data->startTime)  ? $this->clean->clean_input($data->startTime)  : '';
        $endTime    = isset($data->endTime)    ? $this->clean->clean_input($data->endTime)    : '';
        $ibadaType  = isset($data->ibadaType)  ? $this->clean->clean_input($data->ibadaType)  : '';
        $image      = isset($data->image)      ? $this->clean->clean_input($data->image)      : '';
        $youtubeId  = isset($data->youtubeId)  ? $this->clean->clean_input($data->youtubeId)  : '';
        $createdBy  = isset($data->createdBy)  ? $this->clean->clean_input($data->createdBy)  :'';


        $status = 401;
        $data = 'All fields are required!';

        // Validate usermedi
        $user = new UsersController();
        $username = ($user->get($user_id))->username;
        if(!empty($body) OR !empty($image) ){
            $status = 401;
            $data = "Sorry a user with the same content exists on our system. Please try another content!";
            $image = Util::base64_to_PNG($media, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/ibada/");
            $date = date("Y-m-d H:i:s");
        
           if(!parent::checkPost($body)){

               $sql = parent::addPost(parent::encode(array("title"=>$title,"body"=>$body, "image"=>$image,'youtubeId'=>$youtubeId,"createdBy"=>$createdBy,
"startDate"=>$startDate,
                "endDate"=>$endDate,
                "startTime"=>$startTime,
                "endTime"=>$endTime,
                "ibadaType"=>$ibadaType,

                "createdDate"=>  $date)));

               $data = 'Sorry something went wrong, Please try again!';
                if($sql){
                    $status = 200;
                    $data = 'Post added successful!';
                }
            }
        }
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
    public function post($params){

        if($this->clean->Method() !== "GET")
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = parent::decode($params);

        $id = isset($data->page) && !empty($data->page) ? $data->page :'';
         $page = isset($data->page2) && $data->page2 !== '' ? $data->page2 :'';
        $limit = isset($data->page3) && !empty($data->page3) ? $data->page3 :'';
        $category = isset($data->page1) && !empty($data->page1) ? $data->page1 :'';
        $not = false;
        if(!empty($category)){
            $not = preg_match( '/!/', $category );
        }

        // get category id
        include_once __DIR__."/../Controller/UsersController.php";
        include_once __DIR__."/../Controller/CategoriesController.php";
        $cat = (new CategoriesController())->getLike(str_replace('!','',$category));
        $category = $cat->id;

        $data = 'No content';
        $status = 401;

        $sql = parent::getPost($id,$category,$not,!empty($page) ? "$page,$limit" : "$limit");
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

    /**
     * @param $params
     * @return array
     */
    public function postFeed($params){
        print_r('Nataka nkuoe');
        if($this->clean->Method() !== "GET")
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = parent::decode($params);

        // $id = isset($data->page) && !empty($data->page) ? $data->page :'';
        //  $page = isset($data->page2) && $data->page2 !== '' ? $data->page2 :'';
        // $limit = isset($data->page3) && !empty($data->page3) ? $data->page3 :'';
        // $category = isset($data->page1) && !empty($data->page1) ? $data->page1 :'';
        // $not = false;
        // if(!empty($category)){
        //     $not = preg_match( '/!/', $category );
        // }

        // get category id
        // include_once __DIR__."/../Controller/UsersController.php";
        // include_once __DIR__."/../Controller/CategoriesController.php";
        // $cat = (new CategoriesController())->getLike(str_replace('!','',$category));
        $category = $cat->id;

        $data = 'No content';
        $status = 401;

        $sql = parent::getFeeds();
        
        print_r($sql);
      
        if($sql) {

            $status = 200;
            // $userd = new UsersController();
            // foreach ($sql as $key=>$value){
            //     // $cat = (new CategoriesController())->getLike($value['category']);
            //     // $sql[$key]['category'] = $cat->name;
            //     // $user = ($userd->get($sql[$key]['user']));
            //     $username = $user->username;
            //     $avatar = $user->avatar;
            //     $sql[$key]['user'] = $username;
            //     $sql[$key]['avatar'] = $avatar;
            //     $sql[$key]['description'] = nl2br($sql[$key]['description']);
            //     $sql[$key]['created_at'] = Util::ago($sql[$key]['created_at']);
            //     $sql[$key]['deadline'] = date("M, d Y", strtotime($sql[$key]['deadline']));
            //     $sql[$key]['min_amount'] = (int) $sql[$key]['min_amount'];
            //     $sql[$key]['max_amount'] = (int) $sql[$key]['max_amount'];
            //      $total = Util::short_number($sql[$key]['max_amount'] + $sql[$key]['min_amount']) ;
            //     $sql[$key]['total'] = $total;
            //     $sql[$key]['featured'] = "public/uploads/$username/data/".$sql[$key]['featured'];
            //     $m = explode(',',$sql[$key]['media']);
            //     $md = '';
            //     if(count($m) > 0){
            //         for ($x = 0; $x <= count($m)-1; $x++) {
            //             if($m[$x] !== '')
            //                 $md .= "public/uploads/$username/data/".$m[$x].',';
            //         }
            //         $sql[$key]['media'] = chop($md,',');
            //     }
            // }

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

$class = new Posts();