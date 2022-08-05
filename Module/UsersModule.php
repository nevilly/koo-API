<?php

include_once __DIR__."/Database.php";

class UsersModule extends Database
{
    private $table = "users";

    /**
     * UsersModule constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $data
     * @return null
     */
    public function add($data){
        $data = parent::decode($data);

        $u = $this->con->real_escape_string($data->username);
        $fn = $this->con->real_escape_string($data->firstname);
        $ln = $this->con->real_escape_string($data->lastname);
        $e = $this->con->real_escape_string($data->email);
        $p = $this->con->real_escape_string($data->phoneNo);
        $addr = $this->con->real_escape_string($data->address);
        $bio = $this->con->real_escape_string($data->bio);
        $role = $this->con->real_escape_string($data->role);
        $gender = $this->con->real_escape_string($data->gender);
        $pwd = $this->con->real_escape_string($data->password);

        $sql = parent::insert($this->encode(['table'=>$this->table,
            'data'=>"username='$u', role='$role', firstname='$fn', lastname='$ln', email='$e', phoneNo='$p', gender='$gender', bio='$bio', password='$pwd', address='$addr', createdDate=now()"]));

        return ($sql) ? $this->con->insert_id : $sql;
    }

    public function add_avatar($avatar, $user){
        $status = false;
        if(!empty($avatar) && !empty($user))
            $status = parent::update(parent::encode(array('table'=>$this->table, 'data'=>"avatar='$avatar'", "where"=>"id=$user")));

        return $status;
    }

    public function update_user($data){
        $data = parent::decode($data);
        $data = (array) $data;

        $status = false;
        $d = '';
        if(count($data) > 0){

            foreach ($data as $key=>$value) {
                if($key !== 'id' && !empty($value))
                  $d .= "$key='$value',";
            }

           $d = chop($d,',');

            $id = $data['id'];

            $status = parent::update(parent::encode(array("table"=>$this->table,"data"=>"$d","where"=>"id='$id'")));
        }

        return $status;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function reset_password($data){
        $data = parent::decode($data);
        $p = $this->con->real_escape_string($data->p);
        $u = $this->con->real_escape_string($data->u);

        return parent::update(self::encode(array('table'=>$this->table,'data'=>"password='$p'",'where'=>"id=$u")));
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id = ''){
        $id = !empty($id)? "id=$id" :'';
        return parent::select(parent::encode(['table'=>$this->table,'where'=>$id]));
    }

    /**
     * @param $value
     * @return mixed
     */
    public function get_user_by_email_or_username($value){
        $value = $this->con->real_escape_string($value);
        $value = !empty($value)? "phoneNo='$value' OR username='$value'" :'';
        return parent::select(parent::encode(['table'=>$this->table,'where'=>$value]));
    }

    /**
     * @param $name
     * @param $password
     * @return mixed
     */
    public function check_user($name, $password){
       
  return parent::select(parent::encode(
            [
              'table'=>$this->table,
              'col'=>'id,role',
              'where'=>"username='$name' AND password='$password' OR 
                        email='$name' AND password='$password' OR 
                        phoneNo='$name' AND password='$password'"
            ]));

        // return parent::select(parent::encode(['table'=>$this->table,'col'=>'id,role','where'=>"username='$name' AND password='$password' AND status = '1' OR email='$name' AND password='$password' AND status = '1' OR phone_number='$name' AND password='$password' AND status = '1'"]));
    }

    /**
     *
     * @param $id
     * @return false
     */
    public function delete_user($id){
        $status = false;

        if(!empty($id))
           $status = parent::delete($this->encode([
            'table'=>$this->table, "where"=>"id='$id'"]));

        return $status;
    }
}