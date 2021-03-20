<?php   
    $mapa = [
        ['','hrad', 'les2', 'les', 'les2', 'rybnik',''],
        ['','','','les2','','',''],
        ['','','kopec','les','dum','',''] 
        ];

    $x = (isset($_GET['x'])) ? $_GET['x']:3;
    $y = (isset($_GET['y'])) ? $_GET['y']:1;

    $pozicia = $mapa[$y][$x];

    $smery = [];
    
    echo "$pozicia";

    //sever
    if ($y>=1 && $y<=2){
        if ($mapa[$y-1][$x]!=""){
            array_push($smery, '<a href="pole_cykly_3.php?x='.$x.'&y='.($y-1).'">hore</a>');
        }
    } 

    //juh
    if ($y>=0 && $y<=1){
        if ($mapa[$y+1][$x]!=""){
            array_push($smery, '<a href="pole_cykly_3.php?x='.$x.'&y='.($y+1).'">dole</a>');
        }
    } 

    //zapad
    if ($y==0 || ($mapa[$y][$x-1])!=""){
        if ($mapa[$y][$x-1]!=""){
            array_push($smery, '<a href="pole_cykly_3.php?x='.($x-1).'&y='.$y.'">dolava</a>');
        }
    }

    //vychod
    if ($y==0 || ($mapa[$y][$x+1])!=""){
        if ($mapa[$y][$x+1]!=""){
            array_push($smery, '<a href="pole_cykly_3.php?x='.($x+1).'&y='.$y.'">doprava</a>');
        }
    }

    foreach ($smery as $smer){
        echo "</br>$smer";}