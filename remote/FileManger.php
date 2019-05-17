<?php 
/**
 * Created by PhpStorm.
 * User: shuyudao
 * Date: 2019.5.9
 * Time: 18:18
 *
 * 文件远程/本地 上传策略
 *
 * 
 */

/**文件管理类
 * 
 */
class FileManger
{

	private $file_path;
	private $file_name;
	
	public function __construct($file_path)
	{
		$this->file_path = './upload/'.$file_path;
		$this->file_name = $file_path;
	}

	//删除
	public function delete(){
		if(!file_exists($this->file_path)){
			return "FILE NOT FOUND";
		}
		if (@unlink($this->file_path)) {
			return true;
		}else{
			return false;
		}
	}

	//下载
	public function download($downloadFilename){
    	if (! file_exists ( $this->file_path )) {
    		header('HTTP/1.1 404 NOT FOUND');
		} else {    
		    //以只读和二进制模式打开文件   
		    $file = fopen ( $this->file_path, "rb" ); 
		    //告诉浏览器这是一个文件流格式的文件    
		    Header ( "Content-type: application/octet-stream" ); 
		    //请求范围的度量单位  
		    Header ( "Accept-Ranges: bytes" );  
		    //Content-Length是指定包含于请求或响应中数据的字节长度    
		    Header ( "Accept-Length: " . filesize ( $this->file_path ) );  
		    //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
		    Header ( "Content-Disposition: attachment; filename=" . $downloadFilename );    
		    //读取文件内容并直接输出到浏览器    
		    echo fread ( $file, filesize ( $this->file_path ) );    
		    fclose ( $file );    
		    exit ();    

		}
	}

	//预览
	public function preview(){
		//可预览的文件：图片、视频、TXT、pdf、MP3
		if (! file_exists ( $this->file_path )) {
			header('HTTP/1.1 404 NOT FOUND');
		}else{

			$type = substr($this->file_path,strrpos($this->file_path,'.')+1);
			$type = strtolower($type);
			if ($type=='jpg'||$type=='png'||$type=='gif'||$type=='jpeg'||$type=='bmp') {
				header("Content-Type: image/jpeg;text/html; charset=utf-8");
			}else if($type=='txt'){
				header("Content-Type: text/plain;text/html; charset=gbk");
			}else if($type=='mp4'){
				header("Content-Type: audio/mp4;text/html; charset=uft-8");
			}else if ($type=='mp3') {
				header("Content-Type: audio/mp3;text/html; charset=uft-8");
			}else if($type=='pdf'){
				header("Content-Type: application/pdf;text/html; charset=uft-8");
			}else{
				echo "不支持预览";
			}
			$file = fopen ( $this->file_path, "rb" );
			echo fread ( $file, filesize ( $this->file_path ) ); 
		    fclose ( $file );    
		    exit ();  
		}

	}
}
?>