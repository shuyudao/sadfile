<?php

/**
 * DB数据库
 */
class DB
{
    private $host;
    private $username;
    private $password;
    private $datebasename;

    private $conn;  #数据库连接

    /**
     * 初始化配置连接数据库
     *
     * @param [type] $config [description]
     */
    public function __construct($config)
    {
        $this->host = $config['host'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->datebasename = $config['datebasename'];
        $this->getConn();
    }

    /**
     * 获取数据库连接
     * @return [type] [description]
     */
    private function getConn(){
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->datebasename);
        mysqli_set_charset($this->conn,'UTF8');
    }

    /**
     * sql查询方法
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
    public function query($sql){

        $res=$this->conn->query($sql) or die("数据查询失败".$this->mysqli->error);

        $result = array();
        while($row=mysqli_fetch_assoc($res))
        {

            array_push($result,$row);
        }

        return $result;

    }

    /**
     * sql增删改操作
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
    public function update($sql){

        $res=$this->conn->query($sql) or die("数据操作失败".$this->conn->error);

        if ($res) {
            //返回数据库影响行数
            return $this->conn->affected_rows;
        }

        return 0;
    }

    public function error(){
        return mysqli_error($this->conn);
    }



    /**
     * 释放资源
     */
    function __destruct() {
        $this->conn->close();
    }
}

$config['host'] = $server;
$config['username'] = $dbuser;
$config['password'] = $dbpwd;
$config['datebasename'] = $dbname;
$DB = new DB($config);


?>