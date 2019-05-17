<?php
/**
 * 
 */
class Config
{
	
	private static $access_key = "31313231"; #权限密钥,用以验证身份

	public static function getAk(){
		return self::$access_key;
	}
}
  ?>