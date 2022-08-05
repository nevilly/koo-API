<?php

include_once __DIR__."/Util/DotRD.php";

include_once __DIR__."/HTTP/http.php";

date_default_timezone_set("Africa/Dar_es_salaam");

use DotRD\DotRD;
(new DotRD(__DIR__ . '/.rd'))->load();


//require __DIR__.'/Util/Util.php';

class Config extends http {
    public $routes = array(); // number of pages captured on the url
    public $route = '';
    public $route1 = '';
    public $route2 = '';
    public $route3 = '';
    public $route4 = '';
    public $route5 = '';
    //public static $key = 'c50fd491dfa9842a07975f9c9ea4465a0be4b9de95d6d0db393759d0ce1cdd6a';

    public function __construct()
    {
        parent::__construct();

        $routes = explode('/',$_SERVER["REQUEST_URI"]);
        $this->routes = $routes;

        // print_r($routes);


        // routes
        $this->route = ucfirst($routes[count($routes) - (count($routes) - 1)]);
        $this->route1 = $routes[count($routes) - (count($routes) - 2)];
        $this->route2 = @$routes[count($routes) - (count($routes) - 3)];
        $this->route3 = @$routes[count($routes) - (count($routes) - 4)];
        $this->route4 = @$routes[count($routes) - (count($routes) - 5)];
        $this->route5 = @$routes[count($routes) - (count($routes) - 6)];
        //print_r($this->route);

        if(preg_match("/$this->route/i", "login")){
            $this->route1 = $this->route;
            $this->route = "Users";
        }

        if(preg_match("/$this->route/i", "password")){
            $this->route1 = $this->route;
            $this->route = "Users";
        }

        if(preg_match("/$this->route/i", "install")){
            $this->route1 = $this->route;
            $this->route = "Table";
        }

        if(preg_match("/$this->route/i", "activate")){
            $this->route2 = $this->route1;
            $this->route1 = $this->route;
            $this->route = "Users";
        }
       
        if(file_exists(__DIR__."/Routes/$this->route.php")) {
          

            include_once __DIR__ . "/Routes/$this->route.php";
             
             $r = str_replace("-","_", $this->route1);
               
             $params = parent::encode(array(
                 'page'=>$this->route2,
                 'page1'=>$this->route3,
                 'page2'=>$this->route4,
                 'page3'=>$this->route5
             ));

          
            
             if (method_exists($class,$r)) {
                return print_r(parent::response($class->$r($params))); /// This method should only return array data
            }

            return print_r(parent::response(["status"=>403,"payload"=>"Unauthorised Acc"]));
        }elseif (file_exists(__DIR__."/$this->route.php")){
            return include_once __DIR__ . "/$this->route.php";
        }

        return print_r(parent::response(["status"=>403,"payload"=>"Unauthorised Accessxxx"]));
    }

}

new Config();
