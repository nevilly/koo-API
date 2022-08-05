<?php
//error_reporting(1);
//error_reporting(E_ERROR);
class http{
    public $allow = array();
    public $content_type = 'application/json';
    public $request = array();
    private $method = '';
    private $code = 200;

    /**
     * http constructor.
     */
    public function __construct()
    {
        self::Methods();
    }

    /**
     * @param $req
     * @return false|mixed
     */
    public function REQUEST($req){
        return isset($_REQUEST["$req"]) ? $_REQUEST["$req"] : false;
    }

    /**
     * @return string
     */
    public function Status(){
        $status = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'Ok',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authorize Information',
            204 => 'No Content',
            205 => 'Content Reset',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Move Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request Url Too Long',
            415 => 'Unsupported Media Type',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP-version Not Supported'
        );
        return ($status[$this->code] ? $status[$this->code] : $status[500]);
    }


    /**
     * @param $data
     * @return false|string
     */
    public function encode($data){
        // code...
        return json_encode($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function decode($data) {
        // code...
        return json_decode($data);
    }

    /**
     * @return mixed
     */
    public function Method(){
        return $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     *
     */
    public function Methods(){
        switch ($this->Method()){
            case 'POST':
                $this->request = $_POST;
                break;
            case 'GET':
                $this->request = $_GET;
                break;
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents('php://input'),$this->request);
                break;
            case 'OPTIONS':
                parse_str(file_get_contents('php://input'),$this->request);
                break;
            case 'PATCH':
                parse_str(file_get_contents('php://input'),$this->request);
                break;
            default:
                $this->response(406,'text/plain','');
                break;
        }
    }

    /**
     *
     */
    private function Headers(){
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST,PUT,DELETE, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }

        header('X-Powered-By: OWESIS');
        header('HTTP/2 '.$this->code .' '.self::Status());
        header('Content-Type:'.$this->content_type.";charset=UTF-8");
        header('Server: OWESIS/0.1');
    }

    /**
     * @param array $data[status,payload]
     * @return false|string
     */
    public function response($data){
        $this->code = ($data['status']) ? $data['status'] : 200;
        self::Headers();
        return self::encode(array(
            "status"=>$this->code   ,
            "payload"=> $data['payload']
        ))  ;
    }
}