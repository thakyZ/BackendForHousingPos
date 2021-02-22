<?php
header("content-type:application/json;charset=utf-8");
$PostData = json_encode($_POST,JSON_UNESCAPED_UNICODE);
$fp = fopen('.log.txt','a') or die ("File Fail…");
$dt = date("Y-m-d h:i:sa");
fwrite($fp,$dt."\n".$PostData."\n");
fclose($fp);

$Location = $_POST['LocationId'];
$UploadName = $_POST['UploadName'];
$Items = json_encode(json_decode($_POST['Items']),JSON_UNESCAPED_UNICODE);
$Tags = $_POST['Tags'];
$Uploader = $_POST['Uploader'];
$UserId = $_POST['UserId'];

if($Items == "[]" or $Items == "null" or is_null($Items)){
    die("Empty…");
}
#如果不需要数据库，可以从这里开始删除。
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
$checkit = hash('md5',$hash.$UserId);
$sql_insert=$con->prepare("INSERT INTO housing (locationId,uploadname,items,tags,uploader,hash,checkit) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sql_insert->bind_param("sssssss",$Location,$UploadName,$Items,$Tags,$Uploader,$hash,$checkit);
$result = $sql_insert->execute();
#数据库部分到这里结束,同时要删除下面的判断和最后的关闭连接语句。
if($result){
    echo "Success!";
    $fp1 = fopen('result/'.$hash.'.json','w') or die ("HashFile Fail…");
    fwrite($fp1,$Items);
    fclose($fp1);
    if(file_exists("map.json")){
        $fp2 = fopen('map.json','r+') or die ("MapFile Add Fail…");
        fseek($fp2,-1,SEEK_END);
        fwrite($fp2,',{"locationId": "'.$Location.'", "uploadName": "'.$UploadName.'","hash": "'.$hash.'", "tags": "'.$Tags.'"}]');
        fclose($fp2);
    }
    else{
        $fp2 = fopen('map.json','w') or die ("MapFile Create Fail…");
        fwrite($fp2,'[{"locationId": "'.$Location.'", "uploadName": "'.$UploadName.'","hash": "'.$hash.'", "tags": "'.$Tags.'"}]');
        fclose($fp2);
    }
}
else{
    echo "INSERT Fail…";
}
$con->close();
?>