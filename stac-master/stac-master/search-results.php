<?php

include_once("./assets/php/util.php");
$util->requireLogin();

$crns = $util->searchClasses($_GET['className'], $_GET['classInstitution']);

if(count($crns) > 0) {
    $classes = array();
    for($i=0; $i<count($crns); $i++) {
        $details = $util->classDetails($crns[$i]);

        if($details) {
            array_push($classes, $details);
        }
    }
}

?>

<html>
<head>
    <title>Search Results - CS 350 User Client</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/search-results.css" />
</head>
<body>
    <?php include_once('./partials/nav.php'); ?>
    <main>
        <div class="results">
        <h1>Search Results</h1>
        <p>You searched for:</p><br />

        <ul class="no-bullets">
            <li><strong>Class Name</strong>: <?php echo $_GET['className']; ?></li>
            <li><strong>Institution</strong>: <?php echo $_GET['classInstitution']; ?></li>
        </ul><br />

        <?php
        if(count($crns) > 0) {
            ?>
            <p>Below are your search results listed out:</p>

            <!--<p><?php var_dump($classes); ?></p>-->
            
            <form method="POST" action="./enroll.php">

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
                    for($i=0; $i<count($classes); $i++) {
                        $class = $classes[$i];
                        ?>
                        <tr>
                            <td class="centered"><input type="checkbox" name="register[]" value="<?php echo $class["id"]?>" /></td>
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
                ?>
            </table>
            <br />
            <input type="submit" value="Register">
            </form>
            <?php  
        } else {
            ?>
            <p><em>There were no classes listed for this search query</em></p>
            <?php
        }
        ?>
        <p class="topspace"><a class="button" href="./search.php">Start a New Search</a></p>
        </div>
    </main>
</body>
</html>