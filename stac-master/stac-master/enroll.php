<?php

include_once("./assets/php/util.php");

$util->requireLogin();

if(!$util->loggedIn()) {
    header("Location: ./index.php");
}

$enrolled = false;
if(isset($_POST['register']) && is_array($_POST['register'])) {
    $succeeded = array();
    $failed = array();

    $courseIds = $_POST['register'];
    for($i=0; $i<count($_POST['register']); $i++) {
        $courseId = $courseIds[$i];
        $enroll = $util->enroll($courseId);
        if($enroll) array_push($succeeded, $courseId);
        else array_push($failed, $courseId);
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
            <h2>Enrolled Classes</h2>
            <p>You were successfully enrolled in the following classes:</p>
            <ul class="no-bullets">
                <?php 
                for($i=0; $i<count($succeeded); $i++) {
                    $class = $util->classDetails($succeeded[$i]);
                    if($class) {
                        ?>
                        <li>- <?php echo $class['name']; ?></li>
                        <?php
                    }
                }
                ?>
            </ul>
        <?php } ?>

        <?php if(count($failed) > 0) { ?>
            <h2>Failed Enrollment</h2>
            <p>There was a problem enrolling oyou in the following classes:</p>
            <ul class="no-bullets">
                <?php 
                for($i=0; $i<count($failed); $i++) {
                    $class = $util->classDetails($failed[$i]);
                    if($class) {
                        ?>
                        <li>- <?php echo $class['name']; ?></li>
                        <?php
                    }
                }
                ?>
            </ul>
        <?php } ?>

        <p><a href="./index.php">Take Me Home</a></p>
    </main>
</body>
</html>