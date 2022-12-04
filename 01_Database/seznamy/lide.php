<?php
    require_once "../inc/db.inc.php";
    require_once "../func/func.php";

    $headlines = [
        "Jméno",
        "Místnost",
        "Telefon",
        "Pozice"
    ];

    $order = OrderBy($_GET['poradi'], $lideArrows);
    $arrow = $_GET['poradi'];
    $stmt = $pdo->query("SELECT employee_id, e.name, r.name as roomname , surname, job, wage, room, room_id, phone FROM employee e join room r on (e.room = r.room_id) $order");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Seznam zaměstanců</title>
</head>
<body class="container" data-new-gr-c-s-check-loaded="14.1026.0" data-gr-ext-installed>
    <?php

    if($stmt->rowCount() == 0){
        echo "<h1>Záznam neobsahuje žádná data</h1>";
    }
    else{
        echo "<h1>Seznam zaměstanců</h1>";
        echo "<table class='table table-striped'>";
        echo "<tr>";

        echo GenerateHeadlines($headlines, $lideArrows, $arrow);

        while($row = $stmt->fetch()){
            echo "<tr>";
            echo "<td><a href='../karty/clovek.php?clovekId={$row->employee_id}'>$row->surname $row->name</a></td><td>$row->roomname</td><td>$row->phone</td><td>$row->job</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    unset($stmt);

    echo GoBack('../firma-prohlizec.php', "Zpět na prohlízeč databáze");
    ?>
</body>
</html>