<?php
/**
 * Created by PhpStorm.
 * User: emalsha
 * Date: 9/2/16
 * Time: 4:59 PM
 */

$conn = null;
require_once("../config.conf");
require_once("../../database/database.php");
session_start();

if (!isset($_SESSION['email']) and !isset($_GET['group'])){
    header("location:../groups.php");
}else{

    $gid = $_GET['group'];
    $userValid = $_GET['u'];
    $qry = "SELECT member_id FROM group_member WHERE group_id = '$gid' ORDER BY join_date DESC";
    $res = mysqli_query($conn,$qry);

    if($userValid == 000){
        $valid = 'display:none';
    }


    while ($row = mysqli_fetch_assoc($res)){
        //get users from each db
        $q = "SELECT first_name,last_name,profile_picture FROM member WHERE member_id='".$row['member_id']."'";
        $qres = mysqli_fetch_assoc(mysqli_query($conn,$q));
        $path_pro_pic = '../images/pro_pic/'.$qres['profile_picture'];
        //send html as respond to ajax request
        echo "<div class=\"group_member_face tooltip\" style='background-image: url(\" ".$path_pro_pic." \");'><span class=\"tooltiptext\">".$qres['first_name']." ".$qres['last_name']."</span> </div>";
    }
}