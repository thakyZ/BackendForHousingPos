<?php
header("content-type:application/json;charset=utf-8");
header("Access-Control-Allow-Headers:x-requested-with,content-type");
function initPostData(){
    $data = array();
    if(!empty($_GET)){
        $data = array_merge($data,$_GET);
        return $data;
    }
    if(!empty($_POST) && $_SERVER["CONTENT_TYPE"]!='application/json'){
        $data = array_merge($data,$_POST);
        return $data;
    }
    $content = file_get_contents('php://input');

    /*switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }*/
    $data = array_merge($data,json_decode($content, true));
    return $data;
}
$Data = initPostData();
if(empty($Data)){
    die("Empty!");
}
$fp = fopen('.log.txt','a') or die ("File Fail…");
$dt = date("Y-m-d h:i:sa");
fwrite($fp,$dt."\n".json_encode($Data,JSON_UNESCAPED_UNICODE)."\n");
fclose($fp);

$Location = $Data['LocationId'];
$UploadName = $Data['UploadName'];
$Items = json_encode(json_decode($Data['Items']),JSON_UNESCAPED_UNICODE);
$Tags = $Data['Tags'];
$Uploader = $Data['Uploader'];
$UserId = $Data['UserId'];

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
$sql_insert->close();
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