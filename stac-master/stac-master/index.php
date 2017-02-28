<?php

include_once("./assets/php/util.php");

if($util->loggedIn()) {
    $util->requireLogin();

    if(!$util->loggedIn()) {
        header("Location: ./index.php");
    }
}

$enrolled = false;
if($util->loggedIn()) {
    $enrolled = array();
    $enrolled = $util->enrolledClasses();
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
        <?php
        if(!$util->loggedIn()) {
            include_once('./partials/login-box.php');        
        } else {
            ?>
            <h2>Welcome User!</h2>
            <br />

            <h3>Your Classes</h3>
            <?php 
            if(!is_array($enrolled)) {
                ?> <p>There was an error getting your class list</p> <?php
            } else {
                if(count($enrolled) <= 0) {
                    ?>
                    <p>You have no classes that you are registered for</p>
                    <?php
                } else {
                    ?>
                    <div class="results">
                        <form method="POST" action="./process-data.php">
                            <table class="topspace" cellspacing="0">
                                <tr>
                                    <th>Check</th>
                                    <th>CRN</th>
                                    <th>Institution</th>
                                    <th>Instructor</th>
                                    <th>Class Name</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Meeting Times</th>
                                </tr>
                            <?php
                            for($i=0; $i<count($enrolled); $i++) {
                                $class = $util->classDetails($enrolled[$i]);
                                if($class) {
                                    ?>
                                    <tr>
                                        <td class="centered"><input type="checkbox" name="classes[]" value="<?php echo $class["id"]?>" /></td>
                                        <td><?php echo $class['id']; ?></td>
                                        <td><?php echo $class['institution']; ?></td>
                                        <td><?php echo $class['admin']; ?></td>
                                        <td><?php echo $class['name']; ?></td>
                                        <td><?php echo $class['start']; ?></td>
                                        <td><?php echo $class['end']; ?></td>
                                        <td>
                                            <ul class="no-bullets">
                                            <?php
                                                for($j=0; $j<count($class['times']); $j++) {
                                                    $time = $class['times'][$j];
                                                    ?><li><?php echo $time['day'] . ' ' . $time['start'] . ' to ' . $time['end'] . ''; ?></li><?php
                                                }
                                            ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </table><br />
                            <input type="submit" name="dropClasses" value="Drop" />
                            <input type="submit" name="attendClasses" value="Attend" />
                            <input type="submit" name="classAttendance" value="View Attendance Record" />
                        </form>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </main>
</body>
</html>