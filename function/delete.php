 <?php
// error_reporting(0);
include '../config.php';
include 'function.php';
islogin();
$id = $_GET['id'];
require '../qiniu/autoload.php'; #引入七牛
use Qiniu\Auth;  #鉴权类
use Qiniu\Storage\BucketManager;
$ak = $data[0]['qiniu_AK'];
$sk = $data[0]['qiniu_SK'];
$name_pan = $data[0]['qiniu_name'];
#文件删除方法
function deleteFile($name)
{
  global $ak,$sk,$name_pan,$conn;
  // code...
  $accessKey = $ak;
  $secretKey = $sk;
  $bucket = $name_pan;
  $key = $name;
  $auth = new Auth($accessKey, $secretKey);
  $config = new \Qiniu\Config();
  $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
  $err = $bucketManager->delete($bucket, $key);
  if ($err) {
      print_r($err);
      echo '<br>错误文件名：'.$name;
  }else{
    $sql_delete = "DELETE FROM files WHERE qiniu_name = '$name';"; #删除数据库中的记录
    if (mysqli_query($conn,$sql_delete)) {
    }
  }
}

if ($id!='') {
	$sql_arr_files = "SELECT * FROM files WHERE id = '$id';";  #筛选出当前目录下的子文件
	$file_list = mysqli_fetch_assoc(mysqli_query($conn,$sql_arr_files));	#查询出的删除目标信息;
	$qiniu = $file_list['qiniu_name'];
	#从七牛云中删除目标文件
	if ($qiniu!='') {
    deleteFile($qiniu);
	}
}

if ($_GET['dirkey']!='') {

 deleteAll($_GET['dirkey']);
 #删掉自己这个文件夹
 $sql_delete_empty_dir = "DELETE FROM dirs WHERE dir_key = ".$_GET['dirkey'];
 if(mysqli_query($conn,$sql_delete_empty_dir)){
   echo "删除成功";
 }
}

function deleteAll($key) #传入父级的dir_key
{
  #查询出所有的子目录
    $sql_select_childdirs = "SELECT * FROM dirs WHERE partent_dir_key = ".$key;
    $arr_dir = [];
    $list_dir = mysqli_query($conn,$sql_select_childdirs);
    while ($row = mysqli_fetch_assoc($conn,$list_dir)) {
        $arr_dir[] = $row;
    }
  #查询出所有的子文件
    $sql_select_childfiles = "SELECT * FROM files WHERE partent_dir_key = ".$key;
    $arr_file = [];
    $list_file = mysqli_query($conn,$sql_select_childfiles);
    while ($row = mysqli_fetch_assoc($list_file)) {
        $arr_file[] = $row;
    }
#如果有子文件则删除
    if (count($arr_file)>0) {
        for($i = 0 ; $i < count($arr_file) ; $i++){
          deleteFile($arr_file[$i]['qiniu_name']);
        }
    }
#递归进入下级目录
    if(count($arr_dir)>0){
      foreach($arr_dir as $i){
        $temp_key = $i['dir_key'];
        deleteAll($temp_key);
        #删掉自己这个文件夹
        $sql_delete_empty_dir = "DELETE FROM dirs WHERE id = ".$i['id'];
        mysqli_query($conn,$sql_delete_empty_dir);
      }
    }

}


 ?>
