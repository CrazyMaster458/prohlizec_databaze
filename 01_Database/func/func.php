<?php
    $mistnostiArrows = [
        "name_up",
        "name_down",
        "no_up",
        "no_down",
        "phone_up",
        "phone_down",
    ];
    $lideArrows = [
        "surname_up",
        "surname_down",
        "name_up",
        "name_down",
        "phone_up",
        "phone_down",
        "job_up",
        "job_down",
    ];

    function OrderBy($order, $db){
        if($order === null || $order === false){
            return "ORDER BY ".explode("_", $db[0])[0];
        }
        else{
            $text = explode("_", $order);
            $way = "";
    
            if($text[1] === "down"){
                $way = "desc";
            }
            
            return "ORDER BY ".$text[0]." ".$way;
        }
    }

    function EchoTitle($status, $title, $text){
        switch($status){
            case "bad_request":
                return "<title>400: Bad request</title>";
                break;
            case "not_found":
                return "<title>404: Not found</title>";
                break;
            default:
                return "<title> $text $title </title>";
                break;
        }
    }

    function GenerateHeadlines($headlines , $arrowType, $arrow){
        $arrowCount = 0;
        $finaltext = "";
        foreach($headlines as $headline){
            $finaltext .= "<th>";
            $finaltext .=  $headline;

            for($i = 0; $i<2; $i++){
                $isSelected = "";
                $type = explode("_", $arrowType[$arrowCount]);
                if($arrow === $arrowType[$arrowCount] || $arrow === null && $arrowCount === 0){
                    $isSelected = "style='color:red'";
                }
                $finaltext .=  "<a href='?poradi={$arrowType[$arrowCount++]}' class='sorted'><span class='glyphicon glyphicon-arrow-{$type[1]}' aria-hidden='true'$isSelected ></span></a>";
            }
            
            $finaltext .=  "</th>";
        }
        return $finaltext;
    }

    function GoBack($path, $text){
        return  "<a href='$path'>"
        ."<span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>".
        " $text</a>";
    }

?>