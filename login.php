<?php
require "./app/default.php";
if ($_POST) {
    try {
        if ($auth->login($_POST['username'], $_POST['password'])) {
            $username = $_POST['username'];
            var_dump($username);
            die();
            header("location:index.php");
            exit;
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <style type="text/css">
        body {
            text-align: center;
        }

        .form-group {
            padding: 5px;
            font-size: 1.2em;
            margin-left: 20%;
            margin-right: 20%;
            width: 50%;

        }

        input {
            padding: 15px;
            font-size: 1.2em;
            border: 2px solid lightgrey;
            border-radius: 10px;
        }

        .btn {
            padding: 5px;
            background-color: green;
            border-radius: 4px;
            color: whitesmoke;
            margin: 5px;
        }
    </style>
</head>

<body>
    <a type="button" class="btn btn-info" href="index.php">HOME</a>
    <hr>

    <form class="" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Inserisci Username" required>
        </div>

        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Inserisci password" required>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-info" value="Effettua il login">
        </div>
    </form>
</body>

</html>