<?php
header("content-type:application/json;charset=utf-8");
$fp = fopen('log.txt','a') or die ("File Fail…");
$dt = date("Y-m-d h:i:sa");
fwrite($fp,$dt."\n初始化...\n");
fclose($fp);

$dbhost = "localhost";
$dbuser = ""; #Your Username
$dbpass = ""; #Your Password
$dbname = "ffxiv";

$con = new mysqli($dbhost, $dbuser, $dbpass) or die("Sql Con Fail…");
$con->set_charset('utf8mb4');

$sql = "CREATE DATABASE IF NOT EXISTS ".$dbname."DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($con->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    die("Error creating database: " . $conn->error);
}

$sql = "use ".$dbname;
$con->query($sql);

$sql = "CREATE TABLE IF NOT EXISTS housing (
    location varchar(20),
    Size char(1),
    named varchar(255),
    items json,
    tags varchar(255),
    uper varchar(255),
    likeit int,
    pics json,
    hash char(32),
    checkit char(10),
    UNIQUE KEY `id` (`hash`)
    )DEFAULT CHARSET=utf8mb4;";

if ($con->query($sql) === TRUE) {
    echo "Table created successfully";
} else {
    die("Error creating Table: " . $conn->error);
}

$con->close();

$fp = fopen('map.json','w') or die ("Map File Fail…");
fwrite($fp,"[]");
fclose($fp);

if (mkdir("result") === TRUE) {
    echo "Mkdir successfully";
} else {
    die("Error Mkdir: result");
}
?>