<?php
     require_once 'funkcje_kal.inc.php';
     
     require_once 'user.php';

	try {
		$u = new user();
	} catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<META NAME="Author" CONTENT="Janusz Paluch">
<TITLE>Kalendarz</TITLE>
</HEAD>
<BODY>

<?php

     $dbcnx = polacz($GLOBALS['login'], $GLOBALS['pass']);
     $uzk_id = $u->get_id();
     
     //spr czy bylo dodawanie zadania do bazy
     if (isset($_POST['tytul'])) {
        polacz($login, $pass);
        if (isset($_POST['id_e'])) {
           //edycja
           dodaj(1);
        } else {
          dodaj(0);
        }
     }
     
     if (isset($_GET['id_u'])) {
        $id = $_GET['id_u'];
        $sql = "DELETE from zadania
                WHERE zad_id='$id'";
        mysql_query($sql);
     }

     //przypisanie wartosci odpowiednich zmiennych (byla metoda GET)
     if (isset($_GET['rok'])) {
        $rok = $_GET['rok'];
        $miesiac = $_GET['mies'];
        $dzien = $_GET['dzien'];
     //jesli bez parametrow to biezaca data
     } else {
       $rok = date("Y");
       $miesiac = date("m");
       $dzien = date("d");
     }

     //stworzenie adresu powrotu do dnia
     $url = 'dzien.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien;
     
     dzien_tbl($dzien, $miesiac, $rok);
     
     //stworzenie adresu powrotu do kalendarza
     $url = 'kalendarz.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien;
     echo '<p><a href='.$url.'>Wstecz</a></p>';
?>

</BODY>
</HTML>
