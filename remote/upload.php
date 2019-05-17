<?php
/**
 * Created by PhpStorm.
 * User: shuyudao
 * Date: 2019.5.9
 * Time: 18:18
 *
 * 文件远程/本地 上传策略
 */

class Upload
{

    private $file_path='';
    private $temp_path='';
    private $blob_num;
    private $total_num;
    private $file_name;
    private $temp_name;
    private $OriginalFileName;
    private $newName;
    private $fielSize;
    /**
     *upload constructor.
     * @access  public
     * @param   string $filePath
     * @param   string|integer $blobNum
     * @param   string|integer $totalNum
     * @param   string $fileName
     * @param   string $tempName
     *
     */
    public function __construct($filePath,$blobNum,$totalNum,$fileName,$tempName){
        $this->file_path=$filePath;
        $this->blob_num=$blobNum;
        $this->total_num=$totalNum;
        $this->file_name=$fileName;
        $this->temp_name=$tempName;
        $this->temp_path='./tempupload/';
        $this->OriginalFileName=$fileName;
        $this->moveFile();
        $this->mergeFile();
 
    }
    //移动临时文件
    private function moveFile(){
        $this->touchDir();
        
        $tempFileName = md5($this->file_name);

        $filename=$this->temp_path.$tempFileName.'_'.$this->blob_num;
        move_uploaded_file($this->temp_name,$filename);
    }
    //合并文件
    private function mergeFile(){
        //当前分片序号（从0开始）等于总分片数-1
        if($this->blob_num==($this->total_num-1)){
            $blob='';
            //使用fopen
            //使用file_get(put)_contents
            //先判断文件是否已经存在
            if(file_exists($this->file_path.iconv('UTF-8','GB2312',$this->file_name))){
                @unlink($this->file_path.iconv('UTF-8','GB2312',$this->file_name));
            }
            $tempFileName = md5($this->file_name);

            //随机字符
            $randomchar = $this->randChar();
            //生成新的名字
            $this->newName = time().$randomchar.'.'.substr($this->file_name,strrpos($this->file_name,'.')+1);

            for($i=0;$i<$this->total_num;$i++){
                //获取上传好的分块文件
                $blob=file_get_contents($this->temp_path.$tempFileName.'_'.$i);
                //最后存储的位置以及文件名
                $last_path=$this->file_path.$this->newName;
                iconv('UTF-8','GB2312',$this->file_path.$this->file_name);
                //写入新的文件
                file_put_contents($last_path,$blob,FILE_APPEND);
            }
            $this->fielSize = filesize($last_path);
            $this->deleteTempFile();
        }
    }
    //删除上传的临时文件
    private function deleteTempFile(){
        for($i=0;$i<$this->total_num;$i++){
            @unlink($this->temp_path.md5($this->file_name).'_'.$i);
        }
    }
    //创建文件架
    private function touchDir(){
        //上传目录
 
        if(!file_exists($this->file_path)){
            $oldmask=umask(0);
            @mkdir($this->file_path,0777,true);
            umask($oldmask);
        }
        //临时文件上传目录
        if(!file_exists($this->temp_path)){
            @mkdir($this->temp_path,0777,true);
        }
        return;
    }
    //随机字符
    private function randChar(){
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );

        $charsLen = count($chars) - 1;
        shuffle($chars); 
        $str = '';
        for($i=0; $i<4; $i++){
            $str .= $chars[mt_rand(0, $charsLen)];
        }
        return $str;
    }
    
    //API返回数据GB
    public function apiReturn(){

        if($this->blob_num==($this->total_num-1)){
            //修改文件权限
            $oldmask=umask(0);
            $res=chmod($this->file_path.$this->newName,0777);
            umask($oldmask);
            $res1=$this->file_path.$this->newName;
            $res2=file_exists($res1);
            if($res2){
                $data['code']=2;
                $data['msg']='success';
                $data['file_path']=$this->file_path.$this->newName;
                $data['OriginalFileName']=$this->OriginalFileName;
                $data['newFileName']=$this->newName;
                $data['fielSize']=$this->fielSize;
            }
        }else{
            if(file_exists($this->temp_path.$this->newName.'_'.$this->blob_num)){
                $data['code']=1;
                $data['msg']='waiting for all';
                $data['file_path']='';
            }
        }
        return $data;
 
    }
 
}
