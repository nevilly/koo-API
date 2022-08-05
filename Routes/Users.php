<?php

include_once __DIR__ . '/../Controller/UsersController.php';
include_once __DIR__."/../Util/Util.php";

class Users extends UsersController
{
    private $data;
    private $clean;
    private $key;

    /**
     * Users constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->clean = new Util();
        $this->data =parent::decode(file_get_contents("php://input"));

        $this->key = getenv("SECRET");
    }

    
    /**
     * @param string $data
     * @return array
     * @throws Exception
     */
    public function add($data = ''){

        if($this->clean->Method() !== "POST" && !(array) $this->data || !Util::tokenValidate()  && !(array) $this->data)
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = $this->data;
        $fn = isset($data->first_name)? ucfirst($this->clean->clean_input($data->first_name)) : '';
        $ln = isset($data->last_name)?ucfirst($this->clean->clean_input($data->last_name)) : '';
        $un = isset($data->username)?$this->clean->clean_input($data->username) : '';
        $e = isset($data->email)?$data->email : '';
        $addr = isset($data->address)?$this->clean->clean_input($data->address) : '';
        $gn = isset($data->gender)?$this->clean->clean_input($data->gender) : '';
        $ag = isset($data->age)?$this->clean->clean_input($data->age) : '';
        $pwd = isset($data->password)?$this->clean->clean_input($data->password) : '';
        $bio = isset($data->bio)?$this->clean->clean_input($data->bio) : '';
        $role = isset($data->role)?$this->clean->clean_input($data->role) : '';
        //$pwd1 = isset($data->confirm_password)??$this->clean->clean_input($data->confirm_password);
        $pn = isset($data->phone)?$this->clean->clean_input($data->phone) : '';

        $status = 204;
        $data = 'No Content';

        if(!empty($fn) && !empty($ln) && !empty($un) && !empty($e) &&  !empty($pwd) && !empty($pn)){

            $status = 401;

            $data = "Sorry a user with the same information exists on our system. Please login!";


            if(!parent::get_user_by_email_or_username($e) &&
                !parent::get_user_by_email_or_username($un)){

                $data = "Sorry something went wrong. Please try again!";

                $sql =  parent::add($this->encode(array(
                    'first_name'=>$fn,
                    'last_name'=>$ln,
                    'privilege'=>$role,
                    'username'=>$un,
                    'phone_number'=>$pn,
                    'password'=>Util::Hash($pwd),
                    'age'=>$ag,
                    'gender'=>$gn,
                    'address'=>$addr,
                    'email'=>$e,
                    'bio'=>$bio
                )));

                if($sql){
                    $appName = isset($_ENV['mail_user']) ?getenv("mail_user"): '';
                    $from = isset($_ENV['mail']) ? getenv("mail") :'';
                    $host = isset($_ENV['smtp_host']) ?getenv("smtp_host") :'';
                    $port = isset($_ENV['smtp_port']) ?getenv("smtp_port"):'';
                    $url = Util::Url();

                    // get user data for token generation
                    $user = parent::check_user($un, Util::Hash($pwd));

                    //User data
                    // Token
                    $token = Util::encrypt_decrypt(Util::generateToken($user,$this->key));

                    $status = 200;
                    $data = "User #$un was added successful!";
                    // mail
                    $sub = "Add user Registration !";
                    $msg = "Hello <b>$un</b>, <br> Thank you for creating an account with us, 
                            from <b>$appName</b> a while ago!<br> Your username is <b>$un</b> <br> passwords are <b>$pwd</b><br>
                            <a href='$url/activate/$token'>click Here to activate your account</a>";

                    Util::Mailer($from,$e,$sub,$msg,$un);
                }
            }

        }

        return array('status'=>$status, 'payload'=>$data);
    }



    /**
     * @return array
     */
    public function login(){
        if($this->clean->Method() !== "POST" )
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $status = 403;
        $message = "Invalid username or password";
       $data = $this->data;
        if(isset($this->data->username,$this->data->password)
        && !empty($this->data->password) && !empty($this->data->username)){
            $u = $this->clean->clean_input($this->data->username);
            $p = $this->data->password;

            if(strlen($u) >= 5 && strlen($p) >= 5 ) {

                $p = Util::Hash($p);
                $message = "Invalid username or password";
                if ($user = parent::check_user($u, $p)) {
                    $status = 200;
                    // get token
                    $message = Util::generateToken($user, $this->key); /// return token
                }
            }
        }

        return array('status'=>$status,'payload'=>$message);
    }



     /**
     * @return array
     * @throws Exception
     */
    public function user(){
        if($this->clean->Method() !== "GET" || !Util::tokenValidate())
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $id = Util::$user_id;
        $status = 204;
        $data = "";
        if(!empty($id))
        {
            $data =  parent::get($id);//returns object|array
            $data->password = '';
            // @$data->status = (int) $data->status;
            // $data->created_at = date("Y, M d", strtotime($data->created_at));
            $status = 200;
            if(!$data) {
                $status = 204;
                $data = array();
            }
        }

        return array('status'=>$status, 'payload'=>$data);
    }

    

    /**
     * @return array
     * @throws Exception
     */
    public function userById(){
        if($this->clean->Method() !== "POST" || !Util::tokenValidate())
            return array('status'=>403,'payload'=>"Unauthorized Access");


        $data = $this->data;
        $id  = isset($data->id) ? $this->clean->clean_input($data->id) :'';

        // $id = Util::$user_id;
        $status = 204;
        $data = "";
        if(!empty($id))
        {
            $data =  parent::get($id);//returns object|array
            $data->password = '';
            // @$data->status = (int) $data->status;
            // $data->created_at = date("Y, M d", strtotime($data->created_at));
            $status = 200;
            if(!$data) {
                $status = 204;
                $data = array();
            }
        }

        return array('status'=>$status, 'payload'=>$data);
    }

    public function getUserProfileById(){
        if($this->clean->Method() !== "POST" || !Util::tokenValidate())
            return array('status'=>403,'payload'=>"Unauthorized Accessz");
        
        $Sid = '';
        $data = $this->data;
         
        $resever_id  = isset($data->id) ? $this->clean->clean_input($data->id) : '';
        
        if(!empty($resever_id)){
            $Sid = $resever_id;
        }else{
            $Sid = Util::$user_id;
        }

    
        
        $status = 204;
        $data = "";
        if(!empty($Sid))
        {
            $data =  parent::get($Sid);//returns object|array
            $data->password = '';
            // @$data->status = (int) $data->status;
            // $data->created_at = date("Y, M d", strtotime($data->created_at));
            $status = 200;
            if(!$data) {
                $status = 204;
                $data = array();
            }
        }

        return array('status'=>$status, 'payload'=>$data);
    } 

    /**
     * @return array
     */
    public function users(){

        if($this->clean->Method() !== "GET" || !Util::tokenValidate())
            return array('status'=>403,'payload'=>"Unauthorized Access");

       $data =  parent::users();
       $status = 204;
       if($data) {
           $status = 200;
           foreach ($data as $key => $datum) {
               $data[$key]['password'] = '';
               //$data[$key]['status'] = (int) $data[$key]['status'];
           }
       }
       
        return array('status'=>$status, 'payload'=>$data);
    }

 
    /**
     * @return array
     */
    public function pass(){
        if($this->clean->Method() !== "POST" || !is_object($this->data))
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $status = 403;
        $message = "Something went wrong, please try again!";

        if(isset($this->data->username)
            && !empty($this->data->username)) {
            $u = $this->clean->clean_input($this->data->username);
            if($user = parent::get_user_by_email_or_username($u)){
                $to = $user->email;
                $username = $user->username;
                $id = $user->id;

                try {
                    $new_password = substr(Util::generate_key(),0,8);
                    $appName = isset($_ENV['mail_user']) ??getenv("mail_user");
                    $from = isset($_ENV['mail']) ??getenv("mail");
                    $host = isset($_ENV['smtp_host']) ??getenv("smtp_host");
                    $port = isset($_ENV['smtp_port']) ??getenv("smtp_port");
                    $message = "Sorry user with provided details does not exists!";

                    if(parent::reset_password(parent::encode(array('p'=>$this->clean->Hash($new_password),'u'=>$id)))){
                        // mail
                        $sub = "Password reset request!";
                        $msg = "Hello <b>$username</b>, <br> Here the new password you've request 
                               from <b>$appName</b> a while ago!<br> <b>$new_password</b>";
                        $status = 200;
                        $message = "Password rest was successful!";
                        Util::Mailer($from,$to,$sub,$msg,$appName);
                    }

                } catch (Exception $e) {
                    $message = $e;
                }
            }
        }


        return array('status'=>$status,'payload'=>$message);
    }


    /**
     * @return array
     * @throws Exception
     */
    public function delete($id = ''){

        $id = !empty($this->data->user_id) ? $this->data->user_id : '';
        $message = "User with #$id ID. does not exists!";
        $status = false;

        if($this->clean->Method() !== "POST" || !Util::tokenValidate() || empty($id))
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $username = parent::get($id);
        if(is_object($username))
            $username = $username->name;

        $data =  parent::delete_user($id); //returns bool
        if($data){
            $status = ($data);
            $message = "User was #$username deleted!";
        }

        return array('status'=>200, 'payload'=>array('status'=>$status,'message'=>$message));
    }


     /**
     * @param string $params
     * @return array
     */
    public function activate($params = ''){
        $params = parent::decode($params);
        $token = isset($params->page) && !empty($params->page) ? Util::encrypt_decrypt($params->page,'decrypt') : '';

        return array('status'=>200, 'payload'=>$token);
    }

    /**
     * @param string $data
     * @return array
     * @throws Exception
     */
    public function addAccount($data = ''){

        if($this->clean->Method() !== "POST" && !(array) $this->data || !Util::tokenValidate()  && !(array) $this->data)
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = $this->data;
        $un = isset($data->username)?$this->clean->clean_input($data->username) : '';
        $fn = isset($data->first_name)?$this->clean->clean_input($data->firstname) : '';
        $ln = isset($data->last_name)?$this->clean->clean_input($data->lastname) : '';
        $e = isset($data->email)?$this->clean->clean_input($data->email) : '';
        $addr = isset($data->address)?$this->clean->clean_input($data->address) : '';
        $gn = isset($data->gender)?$this->clean->clean_input($data->gender) : '';
        $pwd = isset($data->password)?$this->clean->clean_input($data->password) : '';
        $role = isset($data->role)?$this->clean->clean_input($data->role) : '';
        $pn = isset($data->phone)?$this->clean->clean_input($data->phone) : '';
        $bio = isset($data->bio)?$this->clean->clean_input($data->bio) : '';

      

        $date = date("Y-m-d H:i:s");

        $status = 204;
        $data = 'No Content';

        if( !empty($un) &&  !empty($pwd) && !empty($pn)){

            $status = 401;

            $data = "Sorry a user with the same information exists on our system. Please login!";


            if(!parent::get_user_by_email_or_username($pn) &&
                !parent::get_user_by_email_or_username($un)){

                $data = "Sorry something went wrong. Please try again!";

                $sql =  parent::add($this->encode(array(
                    'username'=>$un,
                    'lastname'=>$ln,
                    'firstname'=>$fn,
                    'role'=>$role,
                    'username'=>$un,
                    'phoneNo'=>$pn,
                    'password'=>Util::Hash($pwd),
                    'gender'=>$gn,
                    'address'=>$addr,
                    'email'=>$e,
                    'bio'=>$bio
                    
                )));

                if($sql){

                    // get user data for token generation
                    $user = parent::check_user($un, Util::Hash($pwd));
                    // Token
                    //$token = Util::encrypt_decrypt(Util::generateToken($user,$this->key));

                    // get token 2
                    $token = Util::generateToken($user, $this->key); /// return token

                    $status = 200;
                    // $data = "User #$un was added successful!";
                    $data = $token ;
                }
            }

        }

        return array('status'=>$status, 'payload'=>$data);
    }

    /**
     * @param string $data
     * @return array
     * @throws Exception
     */
    public function update($data = ''){

        $user = Util::tokenValidate();
        if($this->clean->Method() !== "PUT" && !(array) $this->data && !$user )
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = $this->data;
        $fn = isset($data->first_name)? ucfirst($this->clean->clean_input($data->first_name)) : '';
        $ln = isset($data->last_name)? ucfirst($this->clean->clean_input($data->last_name)) : '';
        $un = isset($data->username)?$this->clean->clean_input($data->username) : '';
        $e = isset($data->email)?$data->email : '';
        $addr = isset($data->address)?$this->clean->clean_input($data->address) : '';
        $gn = isset($data->gender)?$this->clean->clean_input($data->gender) : '';
        $ag = isset($data->age)? date("Y-m-d", strtotime($data->age)): '';
        $pwd = isset($data->password)?$this->clean->clean_input($data->password) : '';
        $bio = isset($data->bio)?$this->clean->clean_input($data->bio) : '';
        $role = isset($data->position)?$this->clean->clean_input($data->position) : '';
        //$pwd1 = isset($data->confirm_password)??$this->clean->clean_input($data->confirm_password);
        $pn = isset($data->phone)?$this->clean->clean_input($data->phone) : '';

        $status = 403;

        $data = "Sorry something went wrong. Please try again!";

        $sql =  parent::update_user($this->encode(array(
            'first_name'=>$fn,
            'last_name'=>$ln,
            'position'=>$role,
            'username'=>$un,
            'phoneNo'=>$pn,
            'password'=>Util::Hash($pwd),
            'age'=>$ag,
            'gender'=>$gn,
            'address'=>$addr,
            'email'=>$e,
            'bio'=>$bio,
            'id'=>$user
        )));

        if($sql){
            $appName = isset($_ENV['mail_user']) ?getenv("mail_user"): '';
            $from = isset($_ENV['mail']) ? getenv("mail") :'';
            $host = isset($_ENV['smtp_host']) ?getenv("smtp_host") :'';
            $port = isset($_ENV['smtp_port']) ?getenv("smtp_port"):'';
            $url = Util::Url();

            //User data
            // Token
            $token = Util::encrypt_decrypt(Util::generateToken($user,$this->key));

            $status = 200;
            $data = "User #$un was updated successful!";
            // mail
            $sub = "Account updating!";
            $msg = "Hello <b>$un</b>, <br> Thank you for updating your account with us, 
                   from <b>$appName</b> a while ago!";

            Util::Mailer($from,$e,$sub,$msg,$appName);
        }

        return array('status'=>$status, 'payload'=>$data);
    }


     /**
     * Upadate Account 
     * @param string $data
     * @return array
     * @throws Exception
     */
    public function updateAccount($data = ''){

        $user = Util::tokenValidate();
        if($this->clean->Method() !== "PUT" && !(array) $this->data && !$user )
            return array('status'=>403,'payload'=>"Unauthorized Access");

        $data = $this->data;
        
        $fn = isset($data->firstname)? ucfirst($this->clean->clean_input($data->firstname)) : '';
        $ln = isset($data->lastname)? ucfirst($this->clean->clean_input($data->lastname)) : '';
        $addr = isset($data->address)?$this->clean->clean_input($data->address) : '';
        $mrt = isset($data->meritalStatus)?$this->clean->clean_input($data->meritalStatus) : '';
        $image = isset($data->avater)? ucfirst($this->clean->clean_input($data->avater)) : '';

        $status = 403;

        $data = "Sorry something went wrong. Please try again!";
        $img = '';
        $username = (self::get($user))->username;
        if($image != ''){
        $img = Util::base64_to_PNG($image, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/profile/");
        }
        
        $sql =  parent::update_user($this->encode(array(
            'firstname'=>$fn,
            'lastname'=>$ln,
            'address'=>$addr,
            'avater'=>$img,
            'merital_status'=>$mrt,
            'id'=>$user
        )));

        if($sql){
            $status = 200;
            $data = "User  #$username  was updated successful!";
        }

        return array('status'=>$status, 'payload'=>$data);
    }

   
    
    /**
     * @return array
     * @throws Exception
     */
    
    public function update_avatar(){
        $validate = Util::tokenValidate();
        if($this->clean->Method() !== "PUT" && !(array) $this->data || !$validate  && !(array) $this->data)
            return array('status'=>403,'payload'=>"Unauthorized Accesxxs");
       
         $status = 201;
        $message = 'File is required!';

        if(isset($this->data->avatar) && !empty($this->data->avatar)){
            $avatar = $this->data->avatar;
            $username = (self::get($validate))->username;
            
            $message = Util::base64_to_PNG($avatar, $_SERVER['DOCUMENT_ROOT']."/public/uploads/$username/images/");
           
            if($message && parent::add_avatar($message,$validate))
                $status = 200;
        }

        return array('status'=>$status, 'payload'=>$message);
    }


   

}

$class = new Users();



