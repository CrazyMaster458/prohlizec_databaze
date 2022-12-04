<?php
    $id = filter_input(INPUT_GET,
    'mistnostId',
    FILTER_VALIDATE_INT,
    ["options" => ["min_range"=> 1]]);

    require_once "../func/func.php";

    $title = '';

    if($id === null || $id === false){
        http_response_code(400);
        $status = "bad_request";
    } 
    else{
        require_once "../inc/db.inc.php";

        $stmt = $pdo->prepare("SELECT * FROM room WHERE room_id=?");
        $stmt->execute([$id]);
        if($stmt->rowCount() === 0){
            http_response_code(404);
            $status = "not_found";
        } 
        else{
            $status = "OK";
            $room = $stmt->fetch();
            $lide = $pdo->query("SELECT * FROM employee WHERE room=$id");
            $keys = $pdo->query("SELECT * FROM `room` r join `key` k on (r.room_id = k.room) join `employee` e on (k.employee = e.employee_id) where k.room = $id");
            $avgWage = $pdo->query("SELECT ROUND(AVG(wage)) as 'wage' FROM employee WHERE room=$id")->fetch()->wage;
            $title = $pdo->query("SELECT * FROM room WHERE room_id=$id")->fetch()->no;
        } 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
    <?php echo EchoTitle($status, $title, "Místnosti č.");?>
</head>
<body class="container" data-new-gr-c-s-check-loaded="14.1026.0" data-gr-ext-installed>
    <?php

        switch($status){
            case "bad_request":
                echo "<h1>Error 400: Bad request</h1>";
                break;
            case "not_found":
                echo "<h1>Error 404: Not request</h1>";
                break;
            default:
                echo "<h1>Místnosti č. $room->no</h1>";
                echo "<dl class='dl-horizontal'>";
                echo "<dt>Číslo</dt><dd>$room->no</dd>";
                echo "<dt>Název</dt><dd>$room->name</dd>";
                echo "<dt>Telefon</dt><dd>$room->phone</dd>";
                echo "<dt>Lidé</dt>";

                while($row = $lide->fetch()){
                    echo "<dd><a href='clovek.php?clovekId=$row->employee_id'>". $row->surname." ". str_split($row->name,1)[0].".</a></dd>";
                }

                if($avgWage === null || $avgWage === false){
                    echo "<dt>Průměrná mzda</dt><dd>___</dd>";
                }
                else{
                    $splitWage = str_split(strrev($avgWage),3);
                    echo "<dt>Průměrná mzda</dt><dd>".strrev($splitWage[1]).".". $splitWage[0].",00</dd>";
                }
            
                echo "<dt>Klíče</dt>";
                while($row2 = $keys->fetch()){
                    echo "<dd><a href='clovek.php?clovekId=$row2->employee_id'>". $row2->surname." ". str_split($row2->name,1)[0].".</a></dd>";
                }
                
                echo "</dl>";
                break;
    }
    echo GoBack('../seznamy/mistnosti.php', "Zpět na seznam místností");

    ?>
</body>
</html>