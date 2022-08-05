<?php

include_once __DIR__."/../Module/PostModule.php";

class PostController extends PostModule
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $data
     * @return false
     */
    public function addPost($data){
        return parent::addPost($data);
    }

    /**
     * @param $title
     * @return bool
     */
    public function checkPost($title){
         $sql = parent::checkPost($title);

        return ($sql) && $sql->num_rows > 0;
    }

    /**
     * @param string $id
     * @param string $category
     * @param bool $not
     * @param string $limit
     * @return array
     */
    public function getPost($id = '',$category ='',$not = false,$limit = ''){
        $user = parent::getPost($id,$category,$not,$limit);
        $data = false;

        if($user->num_rows > 0)
            $data = $user->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    /**
     * @param string $id
     * @param string $category
     * @param bool $not
     * @param string $limit
     * @return array
     */

     public function getFeeds(){

        $user = parent::getPostFeeds();
        $data = false;

        if($user->num_rows > 0)
            $data = $user->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

   

    /**
     * @param $user
     * @param string $limit
     * @return mixed
     */
    public function get_post_by_user($user,$category ='', $not = false, $limit = '')
    {
        $sql = parent::get_post_by_user($user,$category,$not, $limit); // TODO: Change the autogenerated stub

        $data = false;

        if($sql->num_rows > 0)
            $data = $sql->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    /**
     * @param $data
     * @return false|mixed
     */
    public function updatePost($data)
    {
        return parent::updatePost($data);
    }

    /**
     * @param $id
     * @param $user_id
     * @return bool
     */
    public function deletePost($id, $user_id)
    {
        return (parent::deletePost($id,$user_id)->num_rows > 0);
    }

}