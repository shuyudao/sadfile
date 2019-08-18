 <?php
// error_reporting(0);
include "Response.php";
include 'FileManger.php';
include 'policyManger.php';

islogin();

$policy_id = "";

#文件删除方法
function deleteFile($name)
{
  global $policy_id,$DB;
  $data = PolicyManger::getPolicydataBypolicyId($policy_id,$DB);
  $auto = new FileManger($name , $data[0]);
  $end = $auto->delete();

  if($end){
    $sql_delete = "DELETE FROM files WHERE qiniu_name = '$name';"; #删除数据库中的记录
    if ($DB->update($sql_delete)) {
      return true;
    }
  }
} 

//多文件/文件夹 删除
if (isset($_GET['file'])||isset($_GET['dir'])) {

    if(isset($_GET['file'])){
        $file = $_GET['file'];
        $sql_arr_files = "SELECT * FROM files WHERE id in (";
        for($i = 0 ; $i < count($file) ; $i++){

            if ($i==count($file)-1){
                $sql_arr_files.=$file[$i].")";
            }else{
                $sql_arr_files.= $file[$i].",";
            }
        }
        $file_list = $DB->query($sql_arr_files);
        for($i = 0 ; $i < count($file_list) ; $i++){
            $qiniu = $file_list[$i]['qiniu_name'];
            if ($qiniu!='') {
                $policy_id = $file_list[$i]['for_policy_id'];
                deleteFile($qiniu);
            }
        }
    }

    if (isset($_GET['dir'])){
        $dir = $_GET['dir'];
        $sql_arr_dirs = "DELETE FROM dirs WHERE dir_key in (";
        for($i = 0 ; $i < count($dir) ; $i++){

            if ($i==count($dir)-1){
                $sql_arr_dirs.=$dir[$i].")";
            }else{
                $sql_arr_dirs.= $dir[$i].",";
            }
            deleteAll($dir[$i]);
        }
        $DB->update($sql_arr_dirs);
    }

    exit($Response->success(1));
}

#传入父级的dir_key
function deleteAll($key) {
    global $policy_id,$DB;
    #查询出所有的子目录
    $sql_select_childdirs = "SELECT * FROM dirs WHERE partent_dir_key = ".$key;
    $arr_dir = $DB->query($sql_select_childdirs);
    #查询出所有的子文件
    $sql_select_childfiles = "SELECT * FROM files WHERE partent_dir_key = ".$key;
    $arr_file = $DB->query($sql_select_childfiles);;

    #如果有子文件则删除
    if (count($arr_file)>0) {
        for($i = 0 ; $i < count($arr_file) ; $i++){
          $policy_id = $arr_file[$i]['for_policy_id'];
          deleteFile($arr_file[$i]['qiniu_name']);
        }
    }
    #递归进入下级目录
    if(count($arr_dir)>0){
        for($i = 0 ; $i < count($arr_dir) ; $i++){
            $temp_key = $arr_dir[$i]['dir_key'];
            deleteAll($temp_key);
            #删掉自己这个文件夹
            $sql_delete_empty_dir = "DELETE FROM dirs WHERE id = ".$arr_dir[$i]['id'];
            $DB->update($sql_delete_empty_dir);
        }
    }
}


 ?>
