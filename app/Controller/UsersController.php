<?php

namespace App\Controller;

use \Exception;
use PDOException;
use \PDO;
use APP\Model\User;
class UsersController {

    private $PDO;
    protected $User;
    public function __construct(PDO $PDOconn) {
        $this->PDO = $PDOconn;
        $this->User = new User($PDOconn);
    }

    public function getUsers(){

        $users = $this->User->all();
        if ($users) {
            foreach ($users as $user) {
              echo "<div class=user-container>";
                echo "<b>NAME:</b>". $user['name'] .
                 "<br>" .
                "<b>USERNAME:</b>" . $user['username'] .
                "<br>" .
                "<b>BIRTHDAY:</b>" . $user['birthdate'] .
                "<br>" .
                  "<b>EMAIL:</b>" . $user['email']. 
                  "<br><br>";
              echo "</div>";
            }
        }
         else {
        echo "Non ci sono utenti registrati";
        }
}


    public function registraNuovoUtente($post) {
        /*
        CONTROLLI
        *username non sia già presente e abbia solo lettere e numeri da 8 a 15 caratteri
        *password che abbia solo lettere, numeri ed alcuni caratteri speciali 
        *password e conferma password devono coincidere
        *email passata valida
        *presenza nome
        */

        //per prima cosa pulisco i valori che mi sono stati passati da eventuali spazi
        $username = trim($post['username']);
        $password = trim($post['password']);
        $repassword = trim($post['re_password']);
        $name = trim($post['name']);
        $birthday = trim($post['birthday']);
        $birthday_date = explode("-", $birthday);
        $email = trim($post['email']);

        //la funzione ctype_alnum permette di verificare se i 
        //caratteri passati sono solo lettere e numeri
        //entriamo nell'if solo se c'è un errore perciò nego tutto
        if (!(ctype_alnum($username) && mb_strlen($username) >=8 && mb_strlen($username) <= 15)) {
            
            throw new Exception("Username non valida deve avere tra gli 8 e i 15 caratteri alfanumerici");
            
        }

        $q = "SELECT * FROM Utenti WHERE (username = :username)";
        $rq = $this -> PDO->prepare($q);
        $rq->bindParam(":username", $username, PDO::PARAM_STR);
        $rq-> execute();

        //se trovo una corrispondenza allora la username è già presente
        if ($rq->rowCount()> 0) {
            throw new Exception("Username già presente");
        }

        if (!preg_match('/^[a-zA-Z0-9_\-\$@#!]{8,}$/',$password)) {
            throw new Exception("Password non valida");
        }

        //strcmp compara due stringhe
        if (strcmp($password, $repassword) !==0) {
            throw new Exception("Password e conferma Password non coincidono");
            
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email non valida");
            
        }
        //data di nascita
        $today = date("Y-m-d");
        //per confrontare due date in php uso la funzione strtotime che mi da il timestamp 
        //della data cioè la converte in millisecondi a partire da una certa data
        $today_tmstmp = strtotime($today);
        $birthday_tmstmp = strtotime($birthday);
          $differenza = ($today_tmstmp - $birthday_tmstmp);
        
        $date_today = explode("-", $today);
        //per verificare il range di età mi prendo l'anno di nascita e l'anno corrente
        $today_year = (int)$date_today[0];
        $birth_year = (int)$birthday_date[0];
        
        /*
        *verifico che l'anno sia un intero
        *verifico che la data di nascita non sia superiore alla data di oggi
        *verifco che l'età sia compresa tra i 18 e i 99 anni
         */
        if (!(is_int($birth_year) 
        && ($differenza > 0)
        && ($today_year - $birth_year)> 18 
        && ($today_year - $birth_year) < 99)) {
            throw new Exception("INSERIRE UNA DATA DI NASCITA VALIDA");
        }

         if (mb_strlen($name)== 0) {
            throw new Exception("Nome non indicato!");
            
        }

        //criptare la password

        $pwd_hash = password_hash($password, PASSWORD_DEFAULT);

        //una volta fatti tutti i controlli aggiungiamo l'utente nella tabella del db
        try {
            $q = "INSERT INTO Utenti (username, password, name, birthdate ,email) VALUES(:username, :password, :name,:birthdate , :email)";

        $rq = $this ->PDO->prepare($q);
        $rq->bindParam(":username", $username, PDO::PARAM_STR);
        $rq->bindParam(":password", $pwd_hash, PDO::PARAM_STR);
        $rq->bindParam(":name", $name, PDO::PARAM_STR);
        $rq->bindParam(":birthdate", $birthday);
        $rq->bindParam(":email", $email, PDO::PARAM_STR);
        $rq->execute();
        } catch (PDOException $e) {
           
        echo "Errore inserimento nel database";

        }
        
           return TRUE;

    }

    public function login(string $username, string $password) {
        //verifichiamo che l'utente sia presente nel db
        try {
            $q = "SELECT * FROM Utenti WHERE username = :username";
            $rq = $this->PDO->prepare($q);
            $rq -> bindParam("username", $username, PDO::PARAM_STR);
            $rq->execute();
            //se non trovo una corrispondenza
            if ($rq->rowCount()== 0) {
                throw new Exception("Lo username fornito non è valido per il login");  
            }
            //se invece troviamo una corrispondenza recuperiamo il record
            $record = $rq ->fetch(PDO::FETCH_ASSOC);
            //controllo la corrispondenza tra password criptata e non criptata 
            if (!password_verify($password, $record['password'])) {
                throw new Exception("La password fornita non è valida per il login");   
            }
            //logghiamo l'utente
            $session_id = session_id();
            $user_id = $record['id'];
            $q = "INSERT INTO UtentiLoggati (session_id, user_id) VALUES (:sessionid, :userid)";
            $rq = $this->PDO->prepare($q);
            $rq-> bindParam (":sessionid", $session_id, PDO::PARAM_STR);
            $rq-> bindParam (":userid", $user_id, PDO::PARAM_INT);
            $rq -> execute();

            header("location:index.php");
            
            return TRUE;
        } catch (PDOException $e) {
            echo "Errore login";
        }
    }

    public function logout() {
        try {
            $q = "DELETE FROM UtentiLoggati Where session_id = :sessionid";
            $rq = $this ->PDO-> prepare($q);
            $session_id = session_id();
           // var_dump($session_id);
           // die();
            $rq->bindParam(":sessionid", $session_id, PDO::PARAM_STR);
            $rq-> execute();
            header("location:index.php");
        } catch (PDOException $e) {
           echo "Errore logout";
        }
    }

    //l'utente sarà loggato finquando non farà logout
    //o non chiuderà il browser
    public function utenteLoggato() {
        $q ="SELECT * FROM UtentiLoggati WHERE session_id = :sessionid";
        $rq = $this->PDO ->prepare($q);
        $session_id = session_id();
        $rq->bindParam(":sessionid", $session_id, PDO::PARAM_STR);
        $rq-> execute();
        if ($rq->rowCount()==0) {
            return FALSE;
        }

        //mostro il nome dell'utente loggato recuperando l'id dalla tabella
        //Utenti loggati e facendo una ricerca per id nella tabella Utenti
        $logged= $rq->fetch(PDO::FETCH_ASSOC);
        $id_user_logged = $logged['user_id'];

        if ($id_user_logged) {

            $nomeutente = $this->User->getUser($id_user_logged);
              return $nomeutente;  
        }
    }
}

?>