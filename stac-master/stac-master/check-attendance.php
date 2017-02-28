<?php

include_once("./assets/php/util.php");

$util->requireLogin();

if(!$util->loggedIn()) {
    header("Location: ./index.php");
}

if(isset($_POST['classes']) && is_array($_POST['classes'])) {
    $succeeded = array();
    // $failed = array();

    $courseIds = $_POST['classes'];
    for($i=0; $i<count($courseIds); $i++) {
        $courseId = $courseIds[$i];
        $attend = $util->check($courseId);
        if($attend) {
            $push = array();
            $push['courseId'] = $courseId;
            $push['attendance'] = $attend;
            array_push($succeeded, $push);
        }
        // else array_push($failed, $courseId);
    }
} else {
    header("Location: ./index.php");
}

?>

<html>
<head>
    <title>Server Connection - CS 350 User Client</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/search-results.css" />
</head>
<body>
    <?php include_once('./partials/nav.php'); ?>
    <main>
        <?php if(count($succeeded) > 0) { ?>
            <h2>Attendance</h2>
            <p>Below is your attendance for all of the requested classes:</p>
            <ul class="no-bullets">
                <?php 
                for($i=0; $i<count($succeeded); $i++) {
                    $class = $util->classDetails($succeeded[$i]['courseId']);
                    if($class) {
                        $classAttendance = $succeeded[$i]['attendance'];
                        ?>
                        <li>- 
                            <?php echo $class['name']; ?>:
                            <ul class="no-bullets"> 
                                <?php 
                                for($j=0; $j<count($classAttendance); $j++) {
                                    $attendance = $classAttendance[$j];
                                    ?>
                                    <li>* <?php echo $attendance; ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <br /><br /><br />
                        </li>
                        <?php
                    }
                }
                ?>
            </ul><br /><br />
        <?php } else { ?>
            <p><em>We were unable to find any attendence records for the classes you have requested</em></p>
        <?php } ?>

        <p><a href="./index.php">Take Me Home</a></p>
    </main>
</body>
</html>