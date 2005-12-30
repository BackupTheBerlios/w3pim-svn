<?php
	function __autoload($class_name) 
	{
		require_once $class_name . ".php";
	}

	class user
	{
		private $id;
		private $login;
		private $email;

		/*
		 * login
		 * passwd
		 *
		 * podanie login i passwd pozwala wyszukac uzytkownika w bazie
		 * jezeli zaden z parametrow nie zostanie podany uzytkownik bedzie
		 * tworzony w oparciu o zalogowanego (jezeli takowy istnieje)
		 *
		 * w przypadku bledow rzucane sa wyjatki
		 */
		function __construct($login='', $passwd='', $register=false)
		{
			// rejestracja uzytkownika
			if ($register) {
				$q = "SELECT uzk_id FROM uzytkownicy WHERE uzk_login = '$login'";
				$ds = new fakedataset($q);
				$row = $ds->fetch();
				$ds->close();

				if (intval($row['uzk_id']) != 0)
				throw new Exception('Użytkownik o podanym loginie już istnieje.');
		
				$hash = md5($passwd);
				$q = "INSERT INTO uzytkownicy (uzk_id, uzk_login, uzk_haslo) " .
					"VALUES ('NULL', '$login', '$hash')";
				$ds = new fakedataset($q);
			}

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

		/*
		* zwraca id uzytkownika
		*/
		function get_id()
		{
			return $this->id;
		}
	
		/*
		* zwraca login uzytkownika
		*/
		function get_login()
		{
			return $this->login;
		}
	};
?>
