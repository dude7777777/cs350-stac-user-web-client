<?php

include_once('./assets/php/util.php');

$messaged = false;
$response = false;

if(isset($_POST['serverIp']) && isset($_POST['serverPort'])) {
    $host = $_POST['serverIp'];
    $port = (int)$_POST['serverPort'];
    $message = (isset($_POST['serverMessage']) ? $_POST['serverMessage'] : '');
    $util->saveTCPConfig($host, $port, './assets/config/tcp-connection.ini');

    if(strlen($message) > 0) {
        $messaged = true;
        $response = $util->request($message);
    }
}

?>

<html>
<head>
    <title>Server Connection - CS 350 User Client</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/form-box.css" />
</head>

<body>
    <?php include_once('./partials/nav.php'); ?>
    <main>
        <div class="form-box">
            <div class="centered">
                <h1>Server Connection Setup</h1>
                
                <form name="serverConnection" method="POST" action="./server.php">
                    <div class="row"><label for="serverIp">Server IP:</label></div>
                    <div class="row"><input name="serverIp" id="serverIp" type="text" value="<?php echo $util->tcpHost; ?>" placeholder="Server IP Address" /></div>

                    <div class="row topspace"><label for="serverPort">Server Port:</label></div>
                    <div class="row"><input name="serverPort" id="serverPort" type="text" value="<?php echo $util->tcpPort; ?>" placeholder="Server Port" /></div>

                    <div class="row topspace"><label for="serverMessage">Message</label></div>
                    <div class="row"><textarea name="serverMessage" id="serverMessage" placeholder="Enter a message to send over TCP"></textarea></div>

                    <div class="row topspace center">
                        <input name="serverSubmit" type="submit" value="Send TCP Message" />
                        <input name="serverSave" type="submit" value="Save TCP Config" />
                    </div>

                    <div class="row topspace">
                        <?php if($messaged) { ?>
                            <h1>TCP Sent</h1>
                            <h2>The contents of the packet were:</h2>
                            <p><?php echo $message; ?></p>

                            <?php if($response) { ?>
                            <h2>The response from the server:</h2>
                            <p><?php echo $response; ?></p>
                    
                            <?php } else { ?>
                            <h2>Response</h2>
                            <p>Uh oh.... Something went wrong</p>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </form>
                
            </div>
        </div>
    </main>
</body>
</html>