<!DOCTYPE html>
<head>
    <?php
    define('hris_access',true);
    require_once('../templates/path.php');
    include('../templates/_header.php');
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['email'])){
        header("location:../../index.php");
    }

    $conn = null;
    require_once("config.conf");
    require_once("../database/database.php");

    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $type  = $_SESSION['type'];
    $email = $_SESSION['email'];
    $pro_pic = $_SESSION['pro_pic'];

    ?>
    <title>HRIS | Administration</title>
</head>
<body>
<div style="padding: 0px;">
    <?php include_once('../templates/navigation_panel.php'); ?>
    <?php include_once('../templates/top_pane.php'); ?>
    <div class="bottomPanel" style="height: 100%;">


        <div class="group_div_content" id="tab_batch">
            <div class="dbox group_tab_members group_members_dbox">

                <?php if($_SESSION['system_admin_panel_access']){?>
                    <!-------------------------------ADD Subject Details--------------------------->
                    <div class="group_administration_content_field">
                        <div class="group_administration_content_field_name">Add Subject Details</div>
                        <div class="group_administration_content_field_value">

                            <form action="adminFunction.php" method="POST">
                                <label>Subject :
                                    <input type="text" class="group_administration_txtbox" name="title" placeholder="ex : Information System" required>
                                </label><br>

                                <label> Select course :</label>
                                <select name="course" class="group_administration_txtbox" required>
                                    <option value=""> -- Select -- </option>
                                    <option value="IS">Information System</option>
                                    <option value="CS">Computer Science</option>
                                    <option value="SE">Software Engineering</option>
                                </select>
                                <br>

                                <label>Course Code:
                                    <input type="text" class="group_administration_txtbox" id="course_code" name="code" placeholder="ex : 1010" required>
                                </label>
                                <br>

                                <label>Credits:
                                    <input type="number" class="group_administration_txtbox" name="credit" min="1" max="4" required>
                                </label>

                                <br><br>
                                <input type="submit" name="add_subject" class="msgbox_button group_writer_button" value="Add Subject">
                            </form>


                        </div>
                    </div>

                    <br>
                    <!-------------------------------ADD Results--------------------------->
                    <div class="group_administration_content_field">
                        <div class="group_administration_content_field_name">Add Results</div>
                        <div class="group_administration_content_field_value">

                            <form action="addResult.php" method="post" enctype="multipart/form-data">

                                <?php
                                    $q = mysqli_query($conn,"SELECT * FROM subject");
                                    if ($q){?>
                                <label for="subject_id"> Subject :
                                        <select name="subject"  id="subject_id" required>
                                            <option value="">-- Select --</option>
                                    <?php
                                        while($row = mysqli_fetch_assoc($q)){
                                            $course_title = $row['title'];
                                            $subject_code = $row['subject_code'];

                                           echo "<option value='$subject_code'> $course_title </option>";

                                        }?>
                                        </select>
                                    </label>
                                <?php
                                    }
                                ?>


                                <?php
                                $q2 = mysqli_query($conn,"SELECT * FROM batchList");
                                if ($q2){?>
                                    <label for="batch_id"> Batch :
                                    <select name="batch"  id="batch_id" required>
                                        <option value="">-- Select --</option>
                                        <?php
                                        while($row = mysqli_fetch_assoc($q2)){
                                            $batch= $row['batch'];
                                            $bb = ltrim($batch,'B');
                                            echo "<option value='$batch'> $bb </option>";

                                        }?>
                                    </select>
                                    </label>
                                    <?php
                                }
                                ?>


                                <br>
                                <span style="font-size: 12px">Please confirm uploading data arranged correct format.<br> Json data should format to { 'index_number':14020646,'IS1001':'B+' } </span><br><br>
                                <input type="file" name="readFile" accept="application/json"><br><br>
                                <input type="submit" name="add_result" value="Upload" class="msgbox_button group_writer_button">

                            </form>
                        </div>
                    </div>
                    <br>
                    <!-- ----------------------------- Add GPA table ------------------------- -->
                    <div class="group_administration_content_field">
                        <div class="group_administration_content_field_name">Add GPA List </div>
                        <div class="group_administration_content_field_value">

                            <form action="addGPAList.php" method="post" enctype="multipart/form-data">

                                <?php
                                $q2 = mysqli_query($conn,"SELECT * FROM batchList");
                                if ($q2){?>
                                    <label for="batch_id"> Batch :
                                        <select name="batch"  id="batch_id" required>
                                            <option value="">-- Select --</option>
                                            <?php
                                            while($row = mysqli_fetch_assoc($q2)){
                                                $batch= $row['batch'];
                                                $bb = ltrim($batch,'B');
                                                echo "<option value='$batch'> $bb </option>";

                                            }?>
                                        </select>
                                    </label>
                                    <?php
                                }
                                ?>
                                <br><br>
                                    <label> Cource :
                                        <select name="course" class="group_administration_txtbox" required>
                                            <option value=""> -- Select -- </option>
                                            <option value="IS">Information System</option>
                                            <option value="CS">Computer Science</option>
                                            <option value="SE">Software Engineering</option>
                                        </select>
                                    </label><br>

                                <span style="font-size: 12px">Please confirm uploading data arranged correct format.<br> Json data should format to { 'index_number':14020646,'registration_number':'2014IS099' } </span><br><br>

                                    <input type="file" name="readFile" accept="application/json">

                                <br><br>
                                <input type="submit" name="add_gpa_list" value=" Upload " class="msgbox_button group_writer_button">


                            </form>
                        </div>
                    </div>


                    <!-- ----------------------------- Generate GPA ------------------------- -->
                    <div class="group_administration_content_field">
                        <div class="group_administration_content_field_name">Generate GPA </div>
                        <div class="group_administration_content_field_value">

                            <form action="adminFunction.php" method="post">

                                <label>
                                    SELECT Course and batch :
                                </label>
                                <select name="course_batch" id='course_batch'>
                                    <option>-- SELECT --</option>
                                    <?php
                                    $r = mysqli_query($conn,"SELECT tablename FROM tablelist");

                                    while ($row = mysqli_fetch_assoc($r)) {
                                        $val = $row['tablename'];
                                        $val_a = explode("_", $val);
                                        $year = $val_a[0];
                                        $co = $val_a[1];
                                        ?>

                                        <option value=<?php echo $val?> > <?php echo $year." - ".$co ?> </option>

                                        <?php
                                    }
                                    ?>
                                </select>

                                <div id="checkBox">
                                    <!-- Subjects are list down in here -->
                                </div>

                                <input type="submit" name="generate_gpa" value=" Generate " class="msgbox_button group_writer_button">


                            </form>
                        </div>
                    </div>


                <?php }?>


                <?php
                if(!($_SESSION['system_admin_panel_access'])){
                    echo "You dont have any permissions.";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
include_once('../templates/_footer.php');
?>

<script>
    $('#course_code').keydown(function (e) {

        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }

    });

    $('#course_batch').change(function(){
        var valw = this.value;

        $.ajax({
            url:"adminFunction.php",
            type:'POST',
            data:{'sub':valw},
            success:function(res){

                $('#checkBox').html(res);

            }
        });

    });

</script>

</body>
</html>