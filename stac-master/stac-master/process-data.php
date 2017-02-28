<?php 
if(isset($_POST['classes']) && is_array($_POST['classes'])) {
    if(isset($_POST['attendClasses'])) {
        include_once('./attend.php');
    } else if(isset($_POST['dropClasses'])) {
        include_once('./drop.php');
    } else if(isset($_POST['classAttendance'])) {
        include_once('./check-attendance.php');
    } else {
        header("Location: ./index.php");
    }
} else {
    header("Location: ./index.php");
}

?>
