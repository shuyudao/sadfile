<?php
header('Access-Control-Allow-Origin: *');
error_reporting(0);
/**
 * Created by PhpStorm.
 * User: shuyudao
 * Date: 2019.5.9
 * Time: 18:18
 *
 * 文件远程/本地 上传策略
 */
    include 'upload.php';
    include 'FileManger.php';
    include 'Aes.php';
    include 'auth.php';
    function start()
    {

        $token = getParameter("token"); #获取传入的Token
        $token = base64_decode($token);
        $tokenarr = explode(":", $token); #分割字符串
        $authToken = $tokenarr[0];#获取权限验证的token
        #传递的参数顺序如下：[method、file_path、file_name]，解密后采用::分割
        $aes = new Aes((Config::getAk()));
        $message = $aes->decrypt($tokenarr[1]);
        $messageArr = explode("::",$message);
        $method = $messageArr[0]; #获取方法类型
        $auth = new auth($authToken);
        // echo $message;
        // die;
        if(!$auth->auth()&&!$auth->tempAuth()){
            echo "<h2>SadFile remote server</h2>";
            die;
        }
        switch ($method) {
            case 'upload':
                if (!$auth->auth()) {
                    die;
                }
                if ($_FILES['file']['error'] == 0) {
                    // $caseName = trim($_POST['caseName']);//获取参数
                    $file_path = './upload/';//设置文件路径
                    $blob_num = $_POST['chunk'];//当前片数
                    $total_num = $_POST['chunks'];//总片数
                    $file_name = $_POST['name'];//文件名称
                    $temp_name = $_FILES['file']['tmp_name'];//零时文件名称
                    $uploadClass = new Upload($file_path, $blob_num, $total_num, $file_name, $temp_name);//实例化upload类，并传入相关参数
                    $data = $uploadClass->apiReturn();
                    return $data;

                } else {
                    $data['code'] = 0;
                    $data['msg'] = 'file_error_code:' . $_FILES['file']['error'];
                    $data['file_path'] = '';
                    return $data;
                }
                break;

            case 'delete':
                if (!$auth->auth()) {
                    die;
                }
                return (new FileManger($messageArr[1]))->delete();
                break;
            case 'download':
                return (new FileManger($messageArr[1]))->download($messageArr[2]);
                break;
            case 'preview':
                (new FileManger($messageArr[1]))->preview();
                break;
            default:
                echo "<h2>SadFile remote server</h2>";
                break;
        }

       
        
    }


    function getParameter($name){
        $value = '';
        if (!empty($_POST)) {
            $value = $_POST[$name];
        }else{
            $value = $_GET[$name];
        }
        return $value;
        
    }

    echo json_encode(start());
   

 ?>