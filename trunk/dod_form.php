<?php
     require_once 'funkcje_kal.inc.php';
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<META NAME="Author" CONTENT="Janusz Paluch">
<TITLE>Dodawanie zadania</TITLE>
</HEAD>
<BODY>

<?php
   
   $dbcnx = polacz($GLOBALS['login'], $GLOBALS['pass']);
   $uzk_id = 1;
   
   $edycja = 0;
   
   if (isset($_GET['id_e'])) {
      $id_e = $_GET['id_e'];
      $edycja = 1;
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

 dod_e_form($dzien, $miesiac, $rok, $edycja, $id_e);

?>

</BODY>
</HTML>
