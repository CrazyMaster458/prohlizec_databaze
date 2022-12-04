<?php
    require_once "../inc/db.inc.php";
    require_once "../func/func.php";

    $headlines = [
        "Název",
        "Číslo",
        "Telefon"
    ];

    $order = OrderBy($_GET['poradi'], $mistnostiArrows);
    $arrow = $_GET['poradi'];
    $stmt = $pdo->query("SELECT * FROM room $order");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Seznam místností</title>
</head>
<body class="container" data-new-gr-c-s-check-loaded="14.1026.0" data-gr-ext-installed>
    <?php

    if($stmt->rowCount() == 0){
        echo "<h1>Záznam neobsahuje žádná data</h1>";
    }
    else{
        echo "<h1>Seznam místností</h1>";
        echo "<table class='table table-striped'>";
        echo "<tr>";

        echo GenerateHeadlines($headlines, $mistnostiArrows, $arrow);

        echo "</tr>";
        while($row = $stmt->fetch()){
            echo "<tr>";
            echo "<td><a href='../karty/mistnost.php?mistnostId={$row->room_id}'>$row->name</a></td><td>$row->no</td><td>$row->phone</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    unset($stmt);
    
    echo GoBack('../firma-prohlizec.php', "Zpět na prohlízeč databáze");
    ?>
</body>
</html>