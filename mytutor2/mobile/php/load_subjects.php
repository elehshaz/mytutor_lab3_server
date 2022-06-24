<?php

if(!isset($_POST)){
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}
include_once("dbconnect.php");
$results_per_page = 5;
$pageno = (int)$_POST['pageno'];
$search = $_POST['search'];
$first_page_result = ($pageno - 1) * $results_per_page;

$sqlloadsubjects = "SELECT * FROM tbl_subjects WHERE subject_name LIKE '%$search%'";
$result = $conn->query($sqlloadsubjects);
$total_result = $result->num_rows;
$page_number = ceil($total_result / $results_per_page);
$sqlloadsubjects = $sqlloadsubjects . " LIMIT $first_page_result , $results_per_page";
$result = $conn->query($sqlloadsubjects);

if($result ->num_rows>0){
    
    $subjects["subjects"] =array();
    while($row = $result->fetch_assoc()){
        $sblist = array();
        $sblist['subject_id']= $row['subject_id'];
        $sblist['subject_name']= $row['subject_name'];
        $sblist['subject_description']= $row['subject_description'];
        $sblist['subject_price']= $row['subject_price'];
        $sblist['tutor_id']= $row['tutor_id'];
        $sblist['subject_sessions']= $row['subject_sessions'];
        $sblist['subject_rating']= $row['subject_rating'];
        array_push($subjects["subjects"],$sblist);
    }
    $response = array('status' => 'success', 'pageno'=>"$pageno",'numofpage'=>"$page_number", 'data' => $subjects);
    sendJsonResponse($response);
}else{
    $response = array('status' => 'failed','pageno'=>"$pageno",'numofpage'=>"$page_number",  'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentarray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
?>