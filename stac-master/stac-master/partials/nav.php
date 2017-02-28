<?php

include_once('./assets/php/util.php');

?>

<nav>
    <div class="tab"><a href="<?php __DIR__  ?>/stac/index.php">Home</a></div>
    <?php if(!$util->loggedIn()) { 
        // show these tabs if the user isn't logged in
        ?> 
        <div class="tab"><a href="<?php __DIR__ ?>/stac/signup.php">Sign Up</a></div>
        <?php 
    } else {
        // the user is logged in, so show these tabs
        ?>
        <div class="tab"><a href="<?php __DIR__ ?>/stac/search.php">Class Search</a></div>
        <div class="tab right"><a href="logout.php">Logout</a></div> 
        <?php
    } ?>
    <div class="tab"><a href="<?php __DIR__ ?>/stac/server.php">Settings</a></div>
</nav>