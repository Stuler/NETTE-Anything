<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Nette Web</title>
</head>
<body>
    <?php   
        $cisla = array();
        echo "<ul>";
        for ($i=10; $i!=0; $i--){
            array_push($cisla, $i);
            echo "<li>$i</li>";
        }
    ?>
</body>
</html>

