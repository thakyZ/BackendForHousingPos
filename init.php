<?php
header("content-type:application/json;charset=utf-8");
$fp = fopen('log.txt','a') or die ("File Fail…");
$dt = date("Y-m-d h:i:sa");
fwrite($fp,$dt."\n初始化...\n");
fclose($fp);
#如果不需要数据库，可以从这里开始删除。
$dbhost = "localhost";
$dbuser = ""; #Your Username
$dbpass = ""; #Your Password
$dbname = "ffxiv";

$con = new mysqli($dbhost, $dbuser, $dbpass) or die("Sql Con Fail…");
$con->set_charset('utf8mb4');

$sql = "CREATE DATABASE IF NOT EXISTS ".$dbname." DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($con->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    die("Error creating database: " . $conn->error);
}
$con->close();

$con = new mysqli($dbhost, $dbuser, $dbpass,$dbname) or die("Sql Con Fail…");
$con->set_charset('utf8mb4');

$sql = "CREATE TABLE IF NOT EXISTS housing (
    locationId varchar(255),
    uploadname varchar(255),
    items json,
    tags TEXT,
    uploader varchar(255),
    likeit int,
    pics TEXT,
    hash char(32),
    checkit char(32),
    UNIQUE KEY `id` (`hash`)
    )DEFAULT CHARSET=utf8mb4;";

if ($con->query($sql) === TRUE) {
    echo "Table created successfully\n";
} else {
    die("Error creating Table: " . $conn->error);
}

$con->close();
#数据库部分到这里结束。
if(file_exists("map.json")){
    unlink("map.json");
    echo "Deleted map.json\n";
}

if(file_exists("result")){
    echo "请手动清除不需要的存档。\n";
    echo "Init Success!";
}
else {
    mkdir("result");
    echo "Init Success!";
}
?>