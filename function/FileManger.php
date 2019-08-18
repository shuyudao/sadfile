<?php
/**
 * 各类文件签名/操作
 */

date_default_timezone_set('Asia/Shanghai');
include 'Aes.php';
require '../qiniu/autoload.php';
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class FileManger
{
	
	private $filename = "";
	private $data; ##策略信息

	function __construct($filename,$data)
	{
		$this->filename = $filename;
		$this->data = $data;
	}

	public function getUploadToken(){
		$tokenArr = "";
		if ($this->data['type']=='qiniu') {
				 // 用于签名的公钥和私钥
		    $accessKey = $this->data['ak']; 
		    $secretKey = $this->data['sk']; 
		    //空间名
		    $bucket = $this->data['bucket'];
		    // 初始化签权对象
		    $auth = new Auth($accessKey, $secretKey);
		    //token过期时间
		    $expires = 3600; #1小时
		    //自定义上传回复的凭证 返回的数据
		    $returnBody = '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"name":"$(x:name)"}';
		    $policy = array(
		        'returnBody' => $returnBody,
		        'callbackBodyType' => 'application/json'
		    );

		    function randomkeys($length)
		    {
		       $pattern = '1234567890abcdefghijklmnopqrstuvwxyz
		                   ABCDEFGHIJKLOMNOPQRSTUVWXYZ';
                $key = "";
		        for($i=0;$i<$length;$i++)
		        {
		            $key .= $pattern{mt_rand(0,35)};    //生成php随机数
		        }
		        return $key;
		    }
		    $str = randomkeys(4);

		    // 生成上传Token
		    $UPtoken = $auth->uploadToken($bucket, null, $expires, $policy, true);

		    $tokenArr = array('token' =>  $UPtoken, 'domain' => $this->data['domain']);

		}else if($this->data['type']=='remote'){
			$aes = new Aes($this->data['ak']);
		    $result = $aes->encrypt("upload");
		    $token = md5($this->data['ak'].date("Y-m-d H")).":".$result;
		    $token = base64_encode($token);
		    $tokenArr = array('token' => $token ,'domain' => $this->data['domain'].'/' );
		}
		return $tokenArr;
	}

	public function preview(){
        $type = $this->data['type'];
        if ($type=='qiniu') {
            $accessKey = $this->data['ak'];
            $secretKey = $this->data['sk'];
            // 构建Auth对象
            $auth = new Auth($accessKey, $secretKey);
            // 私有空间中的外链 http://<domain>/<file_key>
            $baseUrl = $this->data['domain'].'/'.$this->filename;
            // 对链接进行签名
            $signedUrl = $auth->privateDownloadUrl($baseUrl);
            return $signedUrl;
        }else if($type=='remote'){
            $aes = new Aes($this->data['ak']);
            $str = "preview::".$this->filename;
            $result = $aes->encrypt($str);
            $token = md5($this->data['ak'].date("Y-m-d H")).":".$result;
            return $this->data['domain'].'/'."index.php?token=".base64_encode($token);
        }


	}

	public function download($downloadFilename){
		$filename = $this->filename;
		$downloadUrl = "";
		if ($this->data['type']=='qiniu') {
			// 用于签名的公钥和私钥
			$accessKey = $this->data['ak']; #公
			$secretKey = $this->data['sk']; #私
			//空间名
			$bucket = $this->data['bucket'];
			// 初始化签权对象
			$auth = new Auth($accessKey, $secretKey);
			//token过期时间
			$expires = 3600; #1小时
			// 私有空间中的外链 http://<domain>/<file_key>
				#对其处理attname
			$baseUrl = $this->data['domain'].'/'.$filename."?attname=".urlencode($downloadFilename);
			// 对链接进行签名
			$downloadUrl = $auth->privateDownloadUrl($baseUrl); #签名意味着授权该下载连接
		}else if($this->data['type']=='remote'){
			$aes = new Aes($this->data['ak']);
			$str = "download::".$filename."::".$downloadFilename;
			$result = $aes->encrypt($str);
			$token = md5($this->data['ak'].date("Y-m-d H")).":".$result;
			$downloadUrl =  $this->data['domain'].'/'."index.php?token=".base64_encode($token);
		}
		return $downloadUrl;
	}

	public function delete(){
		if ($this->data['type']=='qiniu') {
			$accessKey = $this->data['ak'];
		    $secretKey = $this->data['sk'];
		  	$bucket = $this->data['bucket'];
		  	$key = $this->filename;
		  	$auth = new Auth($accessKey, $secretKey);
		  	$config = new \Qiniu\Config();
		  	$bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
		  	$err = $bucketManager->delete($bucket, $key);
		  	if ($err) {
		      	return $err;
		  	}
		}else if ($this->data['type']=='remote') {
			$aes = new Aes($this->data['ak']);
		    $result = $aes->encrypt("delete::".$this->filename);
		    $token = md5($this->data['ak'].date("Y-m-d H")).":".$result;
		    $token = base64_encode($token);
		    $geturl = $this->data['domain'].'/index.php?token='.$token;

		    $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $geturl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
		}
		return true;
	}


}


  ?>