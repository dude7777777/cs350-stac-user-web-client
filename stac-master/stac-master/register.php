<?php

include_once('./assets/php/util.php');

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['first']) && isset($_POST['last'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first = $_POST['first'];
    $last = $_POST['last'];

    $response = $util->register($username, $password, $first, $last);

    if(strcmp($response, 'REGR S') == 0) {
        //$_SESSION['status'] = true;
        $util->logout();
    }
}

?>

<html>
<head>
    <title>Server Connection - CS 350 User Client</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css" />
</head>
<body>
    <?php include_once('./partials/nav.php'); ?>
    <main>
        <?php
            if($response) {
                if(strcmp($response, 'REGR S') == 0) {
                    ?>
                    <h1>Welcome!</h1>
                    <p>Your registration was successful!</p>
                    <p><a href="./index.php">Click here to login</a></p>
                    <?php
                } else if(strcmp($response, 'REGR F') == 0) {
                    ?>
                    <h1>Oops!</h1>
                    <p>Your registration failed</p>
                    <p><a href="./signup.php">Click here to try again</a></p>
                    <?php
                } else {
                    ?>
                    <h1>Error!</h1>
                    <p>An unknown error occurred</p>
                    <p><a href="./signup.php">Click here to try again</a></p>
                    <?php
                }
            } else {
                ?>
                <h1>Error!</h1>
                <p>There was an error while registering</p>
                <p><a href="./signup.php">Click here to try again</a></p>
                <?php
            }
        ?>
    </main>
</body>
</html>