<?php
/*
 * Aria2须知：
 * 设置定时任务，定时访问：/check.php?url=主程序站点url e: http://192.168.43.142/remote/check.php?url=http://192.168.43.142/
 *
 * */
//检查Aria下载
include 'config.php';
$dir = "./download/";
if ($dh = opendir($dir)){

    $curl = curl_init();

    while (($file = readdir($dh))!= false){
        if ($file != '.' && $file != '..'){

            $t_arr = explode(".",$file);
            if ($t_arr[count($t_arr)-1]=='aria2'){ ///.aria2的文件忽略
                continue;
            }

            $filePath = $dir.$file;
            $newPath = "./upload/".$file;
            copy($filePath,$newPath); //拷贝到新目录
            unlink($filePath); //删除旧目录下的文件

            $file_name = $file;
            $file_size = filesize($newPath);

            //TODO 发送求请告诉客户端，存入数据库，验证方式：url+md5(aonfig_access_key)
            $key = base64_encode($_SERVER['HTTP_HOST']."^^".md5(Config::getAk()));
            echo $_SERVER['HTTP_HOST'];
            //$_GET['url']为网盘站点url
            curl_setopt($curl, CURLOPT_URL, $_GET['url']."/function/upload.php?file_name="."[离线下载]".$file_name."&fszie=".$file_size."&parent_dir_key=0&qiniu_name=".$file_name."&key=".$key);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            echo $data;
        }
    }

    closedir($dh);

}