<?php
class Response
{
    private $status = null;
    private $message = "success";
    private $data = null;

    public function __construct()
    {
    }


    private function data(){
        $response = array(
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data
        );
        return json_encode($response);
    }

    public function response($status,$message,$data){
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        return $this->data();
	}

    public function success($data){
        $this->status = 200;
        $this->data = $data;
        return $this->data();
	}


    public function fail($message){
        $this->status = 500;
        $this->message = $message;
        return $this->data();
	}
}
$Response = new Response();