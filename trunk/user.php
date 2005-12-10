<?php
    require_once 'fakedataset.php';
        
    class user
    {
        private $id;
        private $login;
        private $email;
         
        function __construct($login='', $passwd='')        
        {            
            // sprawdzenie czy uzytkownik juz sie zalogowal            
            if (($login == '') and ($passwd == '')) {
                session_start();
                $u = $_SESSION['user'];
                if (isset($u)) {
                    $this->id = $u->id;
                    $this->login = $u->login;
                    $this->email = $u->email;
                    unset($_SESSION['user']);
                    $_SESSION['user'] = $this;                    
                } else
                    throw new Exception('Brak zalogowanego użytkownika.');                
            } else {            
                // pobranie danych o uzytkowniku
                $q = "SELECT * FROM uzytkownicy WHERE uzk_login = '$login'";
                $ds = new fakedataset($q, true);                
                $row = $ds->fetch();
                
                // porownianie hasel
                if (strcmp(md5($passwd), $row['uzk_haslo']) <> 0) 
                    throw new Exception('Brak użytkownika o podanych parametrach.');
                    
                // zapisanie danych uzytkownika
                $this->id = $row['uzk_id'];
                $this->login = $login;
                $this->email = $row['uzk_email'];
                
                // ustawienie zalogowanego uzytkownika
                session_start();
                $_SESSION['user'] = $this;
            }
        }
        
        function get_id()
        {
            return $this->id;            
        }
        
        function get_login()
        {
            return $this->login;
        }                
    };
?>
