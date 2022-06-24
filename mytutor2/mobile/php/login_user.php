<?php 
if(!isset($_POST)){
    echo "failed";
}

include_once("dbconnect.php");
$email = $_POST['email'];
$password = sha1($_POST['password']);
$sqllogin = "SELECT * FROM tbl_admin WHERE admin_email ='$email' AND admin_password = '$password'";
$result = $conn->query($sqllogin);
$numrow = $result->num_rows;

if($result->num_rows> 0){
    while ($row = $result ->fetch_assoc()){
        $userlist = array();
        $userlist['id'] = $row['user_id'];
        $userlist['name'] = $row['name'];
        $userlist['email'] = $row['email'];
        $userlist['regdate'] = $row['regdate'];
        echo json_decode($userlist);
        return;
    }
}else{
    echo "failed";
}

?>