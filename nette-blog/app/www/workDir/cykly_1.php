<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Nette Web</title>
</head>
<body>
    <h1>Kolik ryb si dáš k večeři?</h1>
    <form action="cykly_1.php" method="post">
        <input type="text" name="kolik" />
        <input type="submit" value="Nakrmte mě!">
    </form>

    <?php
        $pocet = $_POST['kolik'];
        for ($n = 0;$n <= $pocet; $n+=1) {
            echo 'x';
        }
    ?>

</body>
</html>

