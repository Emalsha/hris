<!DOCTYPE html>
<head>
    <?php
    define(hris_access,true);
    require_once('../templates/path.php');
    include('../templates/_header.php');
    $conn = null;
    require_once("config.conf");
    require_once ("../database/database.php");
    session_start();

    $user_id = $_SESSION['user_id'];

    if (!isset($_SESSION['email']) or !isset($_GET['group'])){
        header("location:../../index.php");
    }else{

        //get group id from GET method\
        $group_id = mysqli_real_escape_string($conn, $_GET['group']);

        $getGroupDetail = "SELECT * FROM groups WHERE group_id = '$group_id'";

        $res = mysqli_query($conn,$getGroupDetail);
        $group_detail = mysqli_fetch_assoc($res);

        //get user type according to user
        $Qry_to_getUserType = "SELECT * FROM group_member WHERE member_id='$user_id' AND group_id='$group_id'";
        $memberDetails =mysqli_fetch_assoc(mysqli_query($conn, $Qry_to_getUserType));

        if (!$memberDetails){
            $valid = "display:none;";
        }


    }

    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $type  = $_SESSION['type'];
    $email = $_SESSION['email'];
    $pro_pic = $_SESSION['pro_pic'];


    //Submit post to database;
    if (isset($_POST['add_post']) && $_POST['post_content'] !=""){
        $post_content = $_POST['post_content'];
        $post_query = "INSERT INTO group_post(group_id,added_user_id,content,added_time) VALUES('$group_id','$user_id','$post_content',NOW())";

        $res = mysqli_query($conn,$post_query);
        if ($res){
            echo "<script>
                    alert('Post add successfully.');
                  </script>";
        }else{
            echo "<script>
                    alert('Sorry, Unable to add post.');
                  </script>";
        }

    }

    ?>
    <title>HRIS | Groups</title>
</head>
<body>
<div style="padding: 0px;">
    <?php include_once('../templates/navigation_panel.php'); ?>
    <?php include_once('../templates/top_pane.php'); ?>


    </div>
<!--Other content goes here-->
<div class="bottomPanel">
    <!--Title and search box area-->
    <div style="float:left;height:80px;width:100%;">
        <div style="float:left;width:auto;height:100%;">
            <div class="txt_paneltitle"><?php echo $group_detail['name']; ?></div>
        </div>
        <div style="float:right;width:auto;height:100%;">
            <input type="text" name="search" placeholder="Search groups " class="mainsearch">
        </div>
    </div>

    <div>
    <!--Group details-->
    <div class="dbox" style="margin-top: 80px; width: 40%; margin-left: 20px;">
        <div class="dboxheader ">
            <div class="dboxtitle">
                Group Details
            </div>
            <div>
                <?php

                echo " <br><div style='padding: 3px;'><b>".$group_detail['name']."</b></div>";
                echo " <div style='padding: 3px;'>Category : ".$group_detail['category']."</div>";
                echo "<div style='font-size: 12px; padding: 5px;'>".$group_detail['description']."</div>";
                ?>

            </div>

        </div>
    </div>

    <!--Add news of notification to group-->
    <div class="dbox" style="<?php echo $valid ?> margin-top: 20px; width: 40%; margin-left: 20px; padding-bottom: 30px;">
        <div class="dboxheader ">
            <div class="dboxtitle">
                Add News Feed or Notification
            </div>
            <div>
                <form action="" method="post">
                    <textarea  name="post_content" style="resize: none; padding: 6px; border-radius: 3px; border:1px solid midnightblue; width: 95%; height: 85px; margin: 5px; overflow: scroll" required></textarea>
                    <input type="submit" name="add_post" value="POST" class="green_btn" style="float: right; margin-bottom:2px; margin-right:5px;">
                </form>
            </div>

        </div>
    </div>
    </div>

    <!--News feeds and notification area-->
    <div class="dashboard_rightbox" style="width: 100%; margin-right: 20px;">
        <div class="dashboard_newsfeed dbox">
            <div class="dboxheader dbox_head_newsfeed">
                <div class="dboxtitle">
                    News Feed / Activity Feed
                </div>
                <div class="newsfeed_content">

                    <div class="newsfeed_item_box" style = "border-color:orangered;">
                        <div class="newsfeed_item_colorbar" style="background-color:orangered; border-radius: 2px"></div>
                        <div class="newsfeed_item_content">
                            This is a sapmle data.. IF you see this, that means you unable to get data from database.Please check your connection of complains to remalsha@gmail.com.
                        </div>
                        <div class="newsfeed_item_timestamp">Friday, September 2, 2016 at 9:32pm</div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<?php
include_once('../templates/_footer.php');
?>
//Ajax method to get all post according to this group and show...
<script>
    $(document).ready(function () {
        $.ajax({
            type:"GET",
            url:"getGroupPost.php?group=<?php echo $group_id?>&c=<?php echo $group_detail['group_color']?>",
            success:function (response) {
                $('.newsfeed_content').html(response);
            }
        });
    });


    $('.newsfeed_content').on('click','.newsfeed_item_box',function () {
        var state = $(this).data('state');

        switch (state){
            case 1 :
            case undefined:
                $(this).css("max-height","1000px");
                $(this).css("height","100%");
                $(this).children('.newsfeed_item_colorbar').css("background-color","#4CAF50");
                $(this).data('state',2);
                break;
            case 2:
                $(this).css("max-height","70px");
                $(this).data('state',1);
                $(this).children('.newsfeed_item_colorbar').css("background-color","#<?php echo $group_detail['group_color']?>");
                break;
        }

    });

</script>

</body>
</html>