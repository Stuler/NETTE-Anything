<?php   
        $ovocie = ["jablko", "hruska", "banan","marhula"];
        $zelenina = ["brokolica", "mrkva", "petrzlen", "cibula"];

        $plod = $_POST['vyber'];
        if (in_array($plod, $ovocie)) echo "$plod je ovocie";
        else if (in_array($plod, $zelenina)) echo "$plod je zelenina";
        else echo "Plod nie je zadefinovany";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Nette Web</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="vyber">
        <input type="submit" value="Zisti"
    </form>
</body>
</html>

