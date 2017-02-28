<?php
include_once("./assets/php/util.php");
$response = $util->logout();
if(!$response){
    header("Location: ./index.php");
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
        <h1>Logged Out</h1>
        <p>You have been successfully logged out</p>
        <p><a href="./index.php">Click here to return home</a></p>
    </main>
</body>
</html>