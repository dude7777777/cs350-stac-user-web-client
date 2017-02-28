<?php

include_once("./assets/php/util.php");

$util->requireLogin();

if(!$util->loggedIn()) {
    header("Location: ./index.php");
}

if(isset($_POST['classes']) && is_array($_POST['classes'])) {
    $succeeded = array();
    $failed = array();

    $courseIds = $_POST['classes'];
    for($i=0; $i<count($courseIds); $i++) {
        $courseId = $courseIds[$i];
        $drop = $util->drop($courseId);
        if($drop) array_push($succeeded, $courseId);
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
            <h2>Dropped Classes</h2>
            <p>You were successfully dropped from the following classes:</p>
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
            </ul><br /><br />
        <?php } ?>

        <?php if(count($failed) > 0) { ?>
            <h2>Failed Drops</h2>
            <p>There was a problem dropping you from the following classes:</p>
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
            </ul><br /><br />
        <?php } ?>

        <p><a href="./index.php">Take Me Home</a></p>
    </main>
</body>
</html>