<?php
require "./app/default.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALEIDE</title>
    <style type="text/css">
        body {
            text-align: center;
        }

        .user-container {
            border: 2px solid grey;
            padding: 5px;
            width: 40%;
            margin-top: 10px;
            margin-left: 30%;
        }

        .btn {
            padding: 10px;
            background-color: green;
            border-radius: 4px;
            color: whitesmoke;
            margin: 5px;
        }
    </style>
</head>

<body>
    <?php
    if ($auth->utenteLoggato()) {
        echo "<br>";
        echo "SEI LOGGATO- <a href='logout.php'>LOGOUT</a>";
        echo "<br>";
    } else {
        echo "<div>";
        echo "<a type='button' class='btn btn-info' href='registrati.php'>REGISTRATI</a>";
        echo "<a type='button' class='btn btn-info' href='login.php'>LOGIN</a>";
        echo "</div>";
    }

    ?>
    <hr>

    <?php
    echo "<br><br><br><br>";
    $auth->getUsers();

    ?>
</body>

</html>