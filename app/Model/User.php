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

    public function getUser($id_user){
        $stm = "SELECT name FROM Utenti WHERE id = :id";
        $rstm = $this->PDO->prepare($stm);

        $rstm->bindParam(":id", $id_user, PDO::PARAM_STR);
        $rstm->execute();

        $loggedin = $rstm->fetch(PDO::FETCH_ASSOC);
        $id_user = $loggedin;

        return $id_user['name'];
    }
}
?>
