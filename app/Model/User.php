<?php

namespace App\Model;

use \PDO;

class User
{

    private $PDO;

    public function __construct(PDO $PDOconn)
    {
        $this->PDO = $PDOconn;
    }

    public function all()
    {


        $st = $this->PDO->query("SELECT * FROM Utenti ORDER BY id DESC");
        if ($st) {
            $elenco = $st->fetchAll();
            
        }
        return $elenco;
    }
}
?>
