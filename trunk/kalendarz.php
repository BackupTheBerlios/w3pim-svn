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
<TITLE>WebPim - kalendarz</TITLE>
</HEAD>
<BODY>

<?php

     $dbcnx = polacz($GLOBALS['login'], $GLOBALS['pass']);
     
     //wyswietlenie nazwy zalogowanego uzytkownika
     $uzk_id = $u->get_id();

     //wyswietlenie nazwy zalogowanego uzytkownika
     $sql = "SELECT uzk_login
             FROM uzytkownicy
             WHERE uzk_id='$uzk_id'";
     $res = @mysql_query($sql, $dbcnx);
     if (!$res) {
        exit('<p>Problem z pobraniem danych</p>');
     }
     $res = mysql_fetch_array($res);
     echo '<p>Użytkownik:<br>'.$res['uzk_login'].'</p>';
     
     //wylogowanie
     echo '<p><a href="login.php?m=logout">Wyloguj</a></p>';
     
     //wyswietlenie biezacej daty
     echo '<h3 align=center>Dziś jest '.data_napis().'</h3>';
     
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
?>


<table width=100%>
       <tr>
           <td width=15%>
               <?php
                    //czesc kalendarza(tabela) zawierajaca rok i miesiace
                    kalendarz_m($dzien, $miesiac, $rok);
               ?>
           </td>
           <td>
               <?php
                    //czesc kalendarza(tabela) zawierajaca dni miesiaca
                    kalendarz_d($dzien, $miesiac, $rok);
               ?>
           </td>
       </tr>
</table>

</BODY>
</HTML>
