<?php

// require_once './assets/modules/php-mac/src/BlakeGardner/MacAddress.php';
// use BlakeGardner\MacAddress;
// var_dump();

class Util {
    public $tcpHost = '127.0.0.1';
    public $tcpPort = 55056;
    public $socket;
    public $mac;

    public function __construct() {
        session_start();
        date_default_timezone_set("America/Denver");
        if(!isset($_SESSION['status'])){
            $_SESSION['status'] = false;
        }

        $ini_array = @parse_ini_file("./assets/config/tcp-connection.ini");
        if(!isset($ini_array)) {
            $ini_array = array();
            $ini_array['host'] = '127.0.0.1';
            $ini_array['port'] = 55056;
        }

        $this->tcpHost = $ini_array['host'];
        $this->tcpPort = (int)$ini_array['port'];
        $this->socket = @pfsockopen($this->tcpHost, $this->tcpPort, $errno, $errstr);
    }

    public function heartbeat() {
        echo "heartbeat from class";
    }

    public function writeINI($array, $file) {
        $res = array();
        foreach($array as $key => $val)
        {
            if(is_array($val))
            {
                $res[] = "[$key]";
                foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
            }
            else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
        }

        $this->fileWrite($file, implode("\r\n", $res));
    }

    public function fileWrite($fileName, $dataToSave) {
        if($fp = fopen($fileName, 'w')) {
            $startTime = microtime(TRUE);
            do {
                $canWrite = flock($fp, LOCK_EX);
                if(!$canWrite) usleep(round(rand(0, 100)*1000));
            } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

            if ($canWrite) {
                fwrite($fp, $dataToSave);
                flock($fp, LOCK_UN);
            }

            fclose($fp);
        }
    }

    public function saveTCPConfig($host, $port, $path) {
        $ini_array = array();
        $ini_array['host'] = $host;
        $ini_array['port'] = (int)$port;
        $this->tcpHost = $host;
        $this->tcpPort = $port;
        $this->writeINI($ini_array, $path);
    }

    public function serverMac() {
        return 'FF:FF:FF:FF:FF:FF';
        // return MacAddress::getCurrentMacAddress();
    }

    public function request($message) {
        $host = $this->tcpHost;
        $port = $this->tcpPort;
        $ret = false;

        if((filter_var($host, FILTER_VALIDATE_IP) || $this->sameString($host, "localhost")) && is_int($port) && $port > 0 && $port < 65534) {
            if($this->sameString($host, "localhost")) $host = "127.0.0.1";
            $this->socket = @pfsockopen($host, $port, $errno, $errstr);
            if($this->socket) {
                $message .= "\r";
                $write = @fwrite($this->socket, $message);
                $response = @fread($this->socket, 1024);

                if($response) {
                    // echo $message;
                    // echo "<br />";
                    // echo $response;
                    // echo "<br />";
                    // echo "<br />";
                    
                    if($this->sameString(substr($response, 0, 2), "ERR")){
                        return $this->logout();
                    }

                    $ret = trim($response);
                }
            } else {
                $_SESSION['status'] = false;
            }

            return $ret;
        } else {
            return $ret;
        }
    }

    public function validateString($string, $acceptString="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_ ") {
        if(strlen($string) == 0){
            return false;
        }

        for($i=0; $i<strlen($string); $i++) {
            $badChar = true;
            for($j=0; $j<strlen($acceptString); $j++) {
                $char1 = substr($string, $i, 1);
                $char2 = substr($acceptString, $j, 1);

                if($char1 == $char2) {
                    $badChar = false;
                    break;
                }
            }

            if($badChar) return false;
        }

        return true;
    }

    public function sameString($a, $b) {
        return (strcmp($a, $b) == 0);
    }

    // public function stripQuotes($string) {
    //     $strLen = strlen($string);
    //     return substr($string, 1, $strLen-2);
    // }

    public function stripQuotes($mixed) {

        if(is_array($mixed)) {
            for($i=0; $i<count($mixed); $i++) {
                $v = $this->stripQuotes($mixed[$i]);
                $mixed[$i] = $v;
            }

            return $mixed;
        } else if(is_string($mixed)) {
            $strLen = strlen($mixed);
            return substr($mixed, 1, $strLen-2);
        } else {
            return $mixed;
        }
    }

    public function login($username, $password) {
        if($this->validateString($username)) {
            if($this->validateString($password)) {
                $message = 'LOGU "' . $username . '" "' . $password . '"';
                return $this->request($message);
            }
        }
        return false;
    }

    public function register($username, $password, $first, $last){
        if($this->validateString($username)){
            if($this->validateString($password)){
                if($this->validateString($first)){
                    if($this->validateString($last)){
                        $message = 'REGU "' . $username . '" "' . $password . '" "' . $first . '" "' . $last . '"';
                        return $this->request($message);
                    }
                }
            }
        }

        return false;
    }

    public function logout(){
        $message = "LOGO";
        $response = $this->request($message);

        if($response == "LOGO S"){
            $_SESSION['status'] = false;
            @fclose($this->socket);
            return true;
        }
        else{
            $_SESSION['status'] = false;
            @fclose($this->socket);
            return true;
        }
    }

    public function requireLogin() {
        if(!$this->socket) {
            $_SESSION['status'] = false;
        }

        if(!$_SESSION['status']) {
            header("Location: ./index.php");
        }
    }

    public function loggedIn() {
        return $_SESSION['status'];
    }

    public function searchClasses($name, $institution) {
        $message = 'CSRC "' . $name . '" "' . $institution . '"';
        $response = $this->request($message);

        $resStatus = substr($response, 0, 6);

        if($this->sameString($resStatus, "CSRR F")) {
            // if no classes came back, return an empty array
            return array();
        } else {
            // get the length the response and split it into an array
            $resLen = strlen($response);
            $crnsString = substr($response, 7, $resLen - 7);
            $crns = explode(" ", $crnsString);

            // iterate through the array of crns and remove the quotes
            // from the beginning and end of the crn
            for($i=0; $i<count($crns); $i++) {
                $crn = $crns[$i];
                $crns[$i] = $this->stripQuotes($crn);
            }

            return $crns;
        }
    }

    public function readableDay($dayChar, $short=false) {
        if($this->sameString($dayChar, "U") || $this->sameString($dayChar, "Sun") || $this->sameString($dayChar, "Sunday")) return ($short) ? "Sun" : "Sunday";
        if($this->sameString($dayChar, "M") || $this->sameString($dayChar, "Mon") || $this->sameString($dayChar, "Monday")) return ($short) ? "Mon" :  "Monday";
        if($this->sameString($dayChar, "T") || $this->sameString($dayChar, "Tue") || $this->sameString($dayChar, "Tuesday")) return ($short) ? "Tue" :  "Tuesday";
        if($this->sameString($dayChar, "W") || $this->sameString($dayChar, "Wed") || $this->sameString($dayChar, "Wednesday")) return ($short) ? "Wed" :  "Wednesday";
        if($this->sameString($dayChar, "R") || $this->sameString($dayChar, "Thu") || $this->sameString($dayChar, "Thursday")) return ($short) ? "Thu" :  "Thursday";
        if($this->sameString($dayChar, "F") || $this->sameString($dayChar, "Fri") || $this->sameString($dayChar, "Friday")) return ($short) ? "Fri" :  "Friday";
        if($this->sameString($dayChar, "S") || $this->sameString($dayChar, "Sat") || $this->sameString($dayChar, "Saturday")) return ($short) ? "Sat" :  "Saturday";
    }

    public function readableTime($hh, $mm) {
        $hour = (int)$hh;
        $min = (int)$mm;

        $mer = "AM";
        if($hh > 12) {
            $mer = "PM";
            $hour -= 12;
        }
        
        if($hour<10){
            $hour = "0" . $hour; 
        }
        if($min<10){
            $min = "0" . $min;
        }

        return $hour . ":" . $min . " " . $mer;
    }

    public function readableMeetTime($time) {
        $day = substr($time, 0, 1);

        $startEnd = substr($time, 1, strlen($time));
        $arr = explode('-', $startEnd);
        $start = $arr[0];
        $end = $arr[1];

        $militStart = substr_replace($start, ":", 2, 0);
        $militEnd = substr_replace($end, ":", 2, 0);

        $militStartArr = explode(":", $militStart);
        $militEndArr = explode(":", $militEnd);

        $startRead = $this->readableTime($militStartArr[0], $militStartArr[1]);
        $endRead = $this->readableTime($militEndArr[0], $militEndArr[1]);

        $dayRead = $this->readableDay($day, true);

        $timeAssoc = array();
        $timeAssoc['day'] = $dayRead;
        $timeAssoc['start'] = $startRead;
        $timeAssoc['end'] = $endRead;
        return $timeAssoc;
    }

    public function classDetails($id) {
        $message = 'CDTL "' . $id . '"';
        $response = $this->request($message);

        $resStatus = substr($response, 0, 6);
        if($this->sameString($resStatus, "CRCR F")) {
            return false;
        } else {
            $resLen = strlen($response);
            $toExplode = substr($response, 7, $resLen - 7);

            $exploded = explode('"', $toExplode);
            $filtered = array();
            for($i=0; $i<count($exploded); $i++) {
                if(!$this->sameString($exploded[$i], "") && !$this->sameString($exploded[$i], " ")) {
                    array_push($filtered, $exploded[$i]);
                }
            }

            $assoc = array();
            $assoc['id'] = $filtered[0];
            $assoc['name'] = $filtered[1];
            $assoc['institution'] = $filtered[2];
            $assoc['admin'] = $filtered[3];
            $assoc['start'] = $filtered[4];
            $assoc['end'] = $filtered[5];
            $assoc['ip'] = $filtered[6];

            $assoc['times'] = array();
            
            $times = explode(";", $filtered[7]);
            for($i=0; $i<count($times); $i++) {
                $time = $times[$i];

                $timeAssoc = $this->readableMeetTime($time);
                array_push($assoc['times'], $timeAssoc);
            }

            return $assoc;
        }
    }

    public function enroll($classId) {
        $message = 'ENRL "' . $classId . '" "' . $this->serverMac() . '"';
        $response = $this->request($message);
        $resStatus = substr($response, 0, 6);
        if($this->sameString($resStatus, 'ENRR F')) {
            return false;
        } else {
            return true;
        }
    }

    public function drop($classId) {
        $message = 'CDRP "' . $classId . '"';
        $response = $this->request($message);
        $resStatus = substr($response, 0, 6);
        if($this->sameString($resStatus, 'CDRR F')) {
            return false;
        } else {
            return true;
        }
    }

    public function enrolledClasses() {
        $message = 'ELST';
        $response = $this->request($message);
        $resStatus = substr($response, 0, 6);

        $arr = array();
        if($this->sameString($resStatus, 'ELSR F')) {
            return false;
        } else {
            $resLen = strlen($response);
            //if user has no classes returns empty array
            if($this->sameString(trim($response), 'ELSR S')){
                return array();
            }
            $crnsString = substr($response, 7, $resLen - 7);
            $crns = explode(" ", $crnsString);

            // iterate through the array of crns and remove the quotes
            // from the beginning and end of the crn
            for($i=0; $i<count($crns); $i++) {
                $crn = $crns[$i];
                $crns[$i] = $this->stripQuotes($crn);
            }
            
            return $crns;
        }
    }

    public function attend($classId) {
        $deviceId = $this->serverMac();
        $attnDate = date('m-d-Y');
        $attnTime = date('Hi');

        $message = 'ATTN "' . $classId . '" "' . $deviceId . '" "' . $attnDate . '" "' . $attnTime . '"';
        $response = $this->request($message);
        $resStatus = substr($response, 0, 6);

        if($this->sameString($resStatus, 'ATTR S')) {
            return true;
        } else {
            return false;
        }
    }

    public function check($classId) {
        $message = 'CCHK "' . $classId . '"';
        $response = $this->request($message);
        $resStatus = substr($response, 0, 6);

        if($this->sameString($resStatus, 'CCHR S')) {
            $resLen = strlen($response);
            if($this->sameString(trim($response), 'CCHR S')) {
                return array();
            }

            $datesString = substr($response, 7, $resLen - 7);
            $dates = explode(" ", $datesString);

            // iterate through the array of crns and remove the quotes
            // from the beginning and end of the crn
            for($i=0; $i<count($dates); $i++) {
                $date = $dates[$i];
                $dates[$i] = $this->stripQuotes($date);
            }
            
            return $dates;
        } else {
            return false;
        }
    }

    public function checkAbility($courseID){
        $details = $this->classDetails($courseID);
        if($details){
            $startDate = $details["start"];
            $endDate = $details["end"];

            //get date object with start
            $startYear = substr($startDate, 0, 4);
            if((int)$startYear >= 2038){
                $startYear = "2037";
            }
            $startMonth = substr($startDate, 5, 2);
            $startDay = substr($startDate, 8, 2);
            $startDateString = $startMonth . '/' . $startDay . '/' . $startYear;
            $startDate = strtotime($startDateString);

            //get date object with end date
            $endYear = substr($endDate, 0, 4);
            if((int)$endYear >= 2038){
                $endYear = "2037";
            }
            $endMonth = substr($endDate, 5, 2);
            $endDay = substr($endDate, 8, 2);
            $endDateString = $endMonth . '/' . $endDay . '/' . $endYear;
            $endDate = strtotime($endDateString);

            //var_dump($endDateString);

            //get date object now
            $cDate = time();

            //if now is between start and end
            // var_dump($startDate);
            // var_dump($cDate);
            // var_dump($endDate);

            if($startDate < $cDate && $cDate < $endDate){
                for($i=0; $i<count($details['times']); $i++) {
                    $time = $details['times'][$i];
                    $readableDay = $this->readableDay($time['day']);
                    $todayDay = date('l');
                    if($this->sameString($todayDay, $readableDay)) {
                        $startingTime = strtotime($time['start']);
                        $endingTime = strtotime($time['end']);
                        if($startingTime < $cDate && $cDate < $endingTime) return true;
                    }
                }
                return false;
            } else return false;
        }
        else {
            return false;
        }
    }
}

$util = new Util();

?>