<?php

include_once("./assets/php/util.php");

$util->requireLogin();

?>

<html>
<head>
    <title>Class Search - CS 350 User Client</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/form-box.css" />
</head>
<body>
    <?php include_once('./partials/nav.php'); ?>
    <main>
        <div class="form-box">
            <div class="centered">
                <div class="row center"><h1>Find a Class</h1></div>
                <div class="row center"><p>Enter a class name and institution below to search for classes:</p><br /></div>

                <form method="get" action="./search-results.php">
                    <div class="row"><label for="className">Class Name:</label></div>
                    <div class="row"><input type="text" name="className" placeholder="Name" /></div>

                    <div class="row topspace"><label for="classInstitution">Class Institution:</label></div>
                    <div class="row"><input type="text" name="classInstitution" placeholder="Institution" /></div>

                    <div class="row topspace center"><input type="submit" name="search" value="Search" /></div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>