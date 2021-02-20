<?php
header("content-type:application/json;charset=utf-8");
$PostData = json_encode($_POST,JSON_UNESCAPED_UNICODE);
$fp = fopen('log.txt','a') or die ("File Fail…");
$dt = date("Y-m-d h:i:sa");
fwrite($fp,$dt."\n".$PostData."\n");
fclose($fp);

$Location = $_POST['Location'];
$Size = $_POST['Size'];
$Named = $_POST['Named'];
$Items = json_encode(json_decode($_POST['Items']),JSON_UNESCAPED_UNICODE);
$Tags = $_POST['Tags'];
$Uper = $_POST['Uper'];

if($Items == "[]" or $Items == "" or is_null($Items)){
    die("Empty…");
}

$dbhost = "localhost";
$dbuser = ""; #Your Username
$dbpass = ""; #Your Password
$dbname = "ffxiv";

$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname) or die("Sql Con Fail…");

$con->set_charset('utf8mb4');

$hash = hash("md5",$Items);
$matchid = $con->query("SELECT hash FROM housing WHERE hash = '".$hash."';");
if($matchid->fetch_assoc()['hash'] !== null){
    mysqli_close($con);
    die("Exist…");
}
$checkit = "checked";
$sql=sprintf("INSERT INTO housing (location,size,named,items,tags,uper,hash,checkit) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",$Location,$Size,$Named,$Items,$Tags,$Uper,$hash,$checkit);
$result = $con->query($sql);

if($result){
    echo "Success!";
    $fp1 = fopen('result/'.$hash.'.json','w') or die ("HashFile Fail…");
    fwrite($fp1,$Items);
    fclose($fp1);
    if(file_exists("map.json")){
        $fp2 = fopen('map.json','r+') or die ("MapFile Fail…");
        fseek($fp2,-1,SEEK_END);
        fwrite($fp2,',{"location": "'.$Location.'","size": "'.$Size.'","hash": "'.$hash.'", "named": "'.$Named.'", "tags": "'.$Tags.'"}]');
        fclose($fp2);
    }
    else{
        $fp2 = fopen('map.json','w') or die ("MapFile Fail…");
        fwrite($fp2,'[{"location": "'.$Location.'","size": "'.$Size.'","hash": "'.$hash.'", "named": "'.$Named.'", "tags": "'.$Tags.'"}]');
        fclose($fp2);
    }
}
else{
    echo "INSERT Fail…";
}
$con->close();
?>