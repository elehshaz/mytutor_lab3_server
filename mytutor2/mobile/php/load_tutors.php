<?php

if(!isset($_POST)){
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}
include_once("dbconnect.php");
$results_per_page = 5;
$pageno = (int)$_POST['pageno'];
$first_page_result = ($pageno - 1) * $results_per_page;

$sqlloadtutors = "SELECT tbl_tutors.tutor_id AS tutor_id, tbl_tutors.tutor_email AS tutor_email, tbl_tutors.tutor_phone AS tutor_phone, tbl_tutors.tutor_name 
AS tutor_name, tbl_tutors.tutor_description AS tutor_description, tbl_tutors.tutor_datereg AS tutor_datereg, 
GROUP_CONCAT(tbl_subjects.subject_name) AS subject_name FROM tbl_tutors INNER JOIN tbl_subjects ON tbl_tutors.tutor_id = tbl_subjects.tutor_id GROUP BY tbl_tutors.tutor_id";
$result = $conn->query($sqlloadtutors);
$total_result = $result->num_rows;
$page_number = ceil($total_result / $results_per_page);
$sqlloadtutors = $sqlloadtutors . " LIMIT $first_page_result , $results_per_page";
$result = $conn->query($sqlloadtutors);

if($result-> num_rows > 0){
   
    $tutors["tutors"] = array();
    while($row = $result->fetch_assoc()){
        $tutolist = array();
        $tutolist['tutor_id']= $row['tutor_id'];
        $tutolist['tutor_email']= $row['tutor_email'];
        $tutolist['tutor_phone']= $row['tutor_phone'];
        $tutolist['tutor_name']= $row['tutor_name'];
        $tutolist['tutor_description']= $row['tutor_description'];
        $tutolist['tutor_datereg']= $row['tutor_datereg'];
        $tutolist['subject_name']= $row['subject_name'];
        array_push($tutors["tutors"],$tutolist);
    }
    $response = array('status' => 'success', 'pageno'=>"$pageno",'numofpage'=>"$page_number", 'data' => $tutors);
    sendJsonResponse($response);
}else{
    $response = array('status' => 'failed', 'pageno'=>"$pageno",'numofpage'=>"$page_number", 'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentarray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
?>