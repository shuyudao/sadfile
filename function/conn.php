<?php
include '../config.php';
$conn = mysqli_connect($server,$dbuser,$dbpwd,$dbname);
mysqli_query($conn,"set character set 'utf8'");//读库 字符
mysqli_query($conn,"set names 'utf8'");//写库 字符

?>