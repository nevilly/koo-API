
<?php
include_once __DIR__ . "/../Controller/CeremonyController.php";
include_once __DIR__."/../Util/Util.php";


class Ceremony extends CeremonyController
{
    private $data;
    private $clean;
    private $key;

    /**
     * Posts constructor.
     */
    public function __construct()  {
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
        $userId   = isset($data->userId) ? $this->clean->clean_input($data->userId)      : '';
        $sId = isset($data->sId)  ? $this->clean->clean_input($data->sId)       : '';
        $name  = isset($data->name)  ? $this->clean->clean_input($data->name)  : '';
        $image    = isset($data->image)    ? $this->clean->clean_input($data->image)    : '';
        $codeNo  = isset($data->codeNo)  ? $this->clean->clean_input($data->codeNo)  : '';
        $cdate    = isset($data->date)  ? $this->clean->clean_input($data->date)    : '';
        $cType  = isset($data->cType)  ? $this->clean->clean_input($data->cType)  : '';
        $goLiveId  = isset($data->goLiveId)  ? $this->clean->clean_input($data->goLiveId)  : '';


        $status = 401;
        $data = 'All fields are required!';

        //Validate usermedi
        $user = new UsersController();
        $username = ($user->get($user_id))->username;
        if(!empty($cType) OR !empty($codeNo) ){
            $status = 401;
            $data = "Sorry a user with the same content exists on our system. Please try another content!";
          $img = Util::base64_to_PNG($image, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/ceremony/");
            $date = date("Y-m-d H:i:s");
        
            // if(!parent::checkPost($codeNo)){

               $sql = parent::addPost(parent::encode(array(
                "codeNo"=>$codeNo,
                "cName"=>$name,
                "ceremonyType"=>$cType,
                'fId'=>$user_id,
                "sId"=>$sId,
                "cImage"=> $img,
                "ceremonyDate"=>$cdate,
                "goLiveId"=>$goLiveId,
                "createdDate"=>  $date
            )
           )
           );

               $data = 'Sorry something went wrong, Please try again!';
                if($sql){
                    $status = 200;
                    $data = 'Ceremony added successful!';
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
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

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
     * Get All CeremonyBy Id 
     * @param $params
     * @return array
     */
    public function getById($params){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

          $data = $this->data;
        $cId = isset($data->cId) ? $this->clean->clean_input($data->cId) :'';

        $data = 'No content';
        $status = 401;

         $sql = parent::getFeeds($cId);
      
        if($sql) {
            $status = 200;
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }


          /**
     * Get All CeremonyBy UserId 
     * @param $params
     * @return array
     */
    public function getByUserId($params){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

          $data = $this->data;
        $userId = isset($data->userId) ? $this->clean->clean_input($data->userId) :'';

        $data = 'No content';
        $status = 401;

         $sql = parent::getCeremonyUserIdFeeds($userId);
      
        if($sql) {
            $status = 200;
            $data = $sql;
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }


    /**
     * Get All Ceremony By Day 
     * @param $params
     * @return array
     */
    public function getCeremony(){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

        $data = $this->data;
        $day = isset($data->day) ? $this->clean->clean_input($data->day) :'';
    


        $data = 'No content';
        $status = 401;

         $sql = parent::getCeremonies($day);
      
        if($sql) {
            $status = 200;
            $data = $sql;
          
        }
          // print_r($data);
        return array('status'=>$status, 'payload'=>$data);
    }


    /**
     * Get All Ceremony By Type 
     * @param $params
     * @return array
     */
    public function getCeremonyByType(){
         
        if($this->clean->Method() !== "POST")
            return array('status'=>403,'payload'=>"Unauthorized Accessss");

        $data = $this->data;
        $d = isset($data->ceremonyType) ? $this->clean->clean_input($data->ceremonyType) :'';
    
        // print_r($d);

        $data = 'No content';
        $status = 401;

         $sql = parent::getCeremoniesByTyp($d);
      
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
        
        if($this->clean->Method() !== "POST" || !(array) $this->data )
            return array('status'=>403,'payload'=>"Unauthorized Access");
        $user_id = Util::tokenValidate();

        $data     = $this->data;
        $cId   = isset($data->cId) ? $this->clean->clean_input($data->cId)      : '';
        $userId   = isset($data->userId) ? $this->clean->clean_input($data->userId)      : '';
        $sId = isset($data->sId)  ? $this->clean->clean_input($data->sId)       : '';
        $name  = isset($data->name)  ? $this->clean->clean_input($data->name)  : '';
        $newCrmCover    = isset($data->newCrmCover)    ? $this->clean->clean_input($data->newCrmCover)    : '';
         $oldCrmCover    = isset($data->oldCrmCover)    ? $this->clean->clean_input($data->oldCrmCover)    : '';
        $codeNo  = isset($data->codeNo)  ? $this->clean->clean_input($data->codeNo)  : '';
        $cdate    = isset($data->date)  ? $this->clean->clean_input($data->date)    : '';
        $cType  = isset($data->cType)  ? $this->clean->clean_input($data->cType)  : '';
        $goLiveId  = isset($data->goLiveId)  ? $this->clean->clean_input($data->goLiveId)  : '';
        // $date = date("Y-m-d H:i:s");

        $status = 401;
        $data = 'All fields are required!';

        if(!empty($cId) && !empty($codeNo)
            && !empty($cType) ){
            $status = 401;

            //Validate usermedi
            $user = new UsersController();
            $username = ($user->get($user_id))->username;


           if(!empty($newCrmCover)){
               $img = Util::base64_to_PNG($newCrmCover, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/ceremony/ ");
           }else{
             $img = $oldCrmCover;
           }

            $sql = parent::updatePost(parent::encode(
                 array(
                "cId"=>$cId,
                "codeNo"=>$codeNo,
                "cName"=>$name,
                "ceremonyType"=>$cType,
                'fId'=>$user_id,
                "sId"=>$sId,
                "cImage"=> $img,
                "ceremonyDate"=>$cdate,
                "goLiveId"=>$goLiveId
                // "createdDate"=>  $date
            )

            ));


            $data = 'Sorry something went wrong, Please try again!';
            if($sql){
                $status = 200;
                $data = 'Ceremony update successful!';
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

$class = new Ceremony();