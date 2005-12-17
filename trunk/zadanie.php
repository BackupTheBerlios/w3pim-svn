<?php
     require_once 'funkcje_kal.inc.php';
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<META NAME="Author" CONTENT="Janusz Paluch">
<TITLE>Zadanie</TITLE>
</HEAD>
<BODY>

<?php

     $dbcnx = polacz($GLOBALS['login'], $GLOBALS['pass']);
     $uzk_id = 1;
     
     if (isset($_GET['zad_id'])) {
        $id = $_GET['zad_id'];
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
     
     zad_tbl($dzien, $miesiac, $rok, $id);

     echo '<p><a href='.$url.'>Wstecz</a></p>';
?>

</BODY>
</HTML>

