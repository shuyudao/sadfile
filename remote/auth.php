<?php
/**
 * Created by PhpStorm.
 * User: shuyudao
 * Date: 2019.5.9
 * Time: 18:18
 *
 * 文件远程/本地 上传策略
 */

/**
 * 权限验证
 */
include 'config.php';
date_default_timezone_set('Asia/Shanghai');
class auth
{
	
	private $token;

	public function __construct($token)
	{
		$this->token = $token;
	}

	public function auth(){
		$time = date("Y-m-d H");
		if ($this->token==md5(Config::getAk().$time)) {
			return true;
		}
		return false;
	}

	//临时权限验证：下载、预览
	public function tempAuth(){
		$time = date("Y-m-d H");
		if ($this->token==md5(Config::getAk().$time.'temp')) {
			return true;
		}
		return false;
	}
}

  ?>