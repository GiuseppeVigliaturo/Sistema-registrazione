<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
//inizializzo la sessione
session_start();
chdir(dirname(__DIR__));
require __DIR__ . '/../DB/DBPDO.php';
$data = require 'config/database.php';
$pdoConn = App\DB\DBPDO::getInstance($data);
$conn = $pdoConn->getConn();
require 'Model/User.php';
require 'Controller/UsersController.php';

//la variabile $PDO è quella creata in db.php
$auth = new APP\Controller\UsersController($conn);

?>