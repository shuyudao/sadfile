<?php
/**
 * Log日志
 */
class Log
{
	private $file;
	
	function __construct($file)
	{
		$this->file = "./log/".$file;
		if (!file_exists($this->file)) {
			$write = fopen($this->file,"w");
			fwrite($write,"<?php\n"."die;\n");
		}else if (filesize($this->file)>2097152) {
			$write = fopen($this->file,"w");
			fwrite($write,"<?php\n"."die;\n");
		}
		
	}

	public function write($log){
		$write = fopen($this->file,"a+");
		rewind($write);
		fseek($write, 12,SEEK_SET);
		$tmp = "[".date("Y-m-d H:i:s")."] - ".getIp()." - "." - ".$log."\n";

		fwrite($write,$tmp);
	}
}

