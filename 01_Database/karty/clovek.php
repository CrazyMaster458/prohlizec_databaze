<?php
    $id = filter_input(INPUT_GET,
    'clovekId',
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

        $stmt = $pdo->prepare("SELECT * FROM employee WHERE employee_id=?");
        $stmt->execute([$id]);
        if($stmt->rowCount() === 0){
            http_response_code(404);
            $status = "not_found";
        }
        else{
            $status = "OK";
            $clovek = $stmt->fetch();
            $mistnosti = $pdo->query("SELECT * FROM `employee` e join `key` k on (e.employee_id = k.employee) JOIN `room` r on (k.room = r.room_id) WHERE e.employee_id = $id");
            $employeeName = $pdo->query("SELECT * FROM employee WHERE employee_id=$id")->fetch();
            $title = $employeeName->surname ." ". str_split($employeeName->name,1)[0] .".";
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
    <?php echo EchoTitle($status, $title, "Karta osoby");?>
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
                echo "<h1>Karta osoby: <em>".$employeeName->surname." ". str_split($employeeName->name,1)[0].".</em></h1>";
                echo "<dl class='dl-horizontal'>";
                echo "<dt>Jméno</dt><dd>$clovek->name</dd>";
                echo "<dt>Příjmení</dt><dd>$clovek->surname</dd>";
                echo "<dt>Pozice</dt><dd>$clovek->job</dd>";
                $splitWage = str_split(strrev($clovek->wage),3);
                echo "<dt>Mzda</dt><dd>".strrev($splitWage[1]).".". $splitWage[0].",00</dd>";
                echo "<dt>Místnost</dt><dd><a href='mistnost.php?mistnostId=$clovek->room'>" .$mistnosti->fetch()->name."</a></dd>";
                echo "<dt>Klíče</dt>";
                while($row = $mistnosti->fetch()){
                    echo "<dd><a href='mistnost.php?mistnostId=$row->room_id'>$row->name</a></dd>";
                }
                echo "</dl>";
                break;
            }
            echo GoBack('../seznamy/lide.php', "Zpět na seznam zaměstanců");
    ?>
</body>
</html>