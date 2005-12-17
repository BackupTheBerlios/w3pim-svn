<!--HEADER-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<META HTTP-EQUIV="Creation-date" CONTENT="2002.03.16">
<META NAME="Description" CONTENT="skrypt tworzacy baze">
<META NAME="Author" CONTENT="Janusz Paluch">
<TITLE>Baza</TITLE>
</HEAD>
<BODY>
<?php
     //nalezy podmienic w tym miejscu uzytkownika i haslo na odpowiednie
     $user = 'root';
     $pass = 'krasnal';
     $dbcnx = @mysql_connect('localhost', $user, $pass);
     if (!$dbcnx) {
        exit('<p>Nie można skontaktować się w tej chwili z serwerem bazy danych.</p>');
     }
     if (!@mysql_select_db('mysql')) {
        exit('<p>Nie można w tej chwili zlokalizować bazy danych.</p>');
     }
     $sql = "DROP table if exists zadania";
     mysql_query($sql);
     $sql = "DROP table if exists pozycje_slownikow";
     mysql_query($sql);
     $sql = "DROP table if exists uzytkownicy";
     mysql_query($sql);
     $sql = "DROP table if exists priorytety";
     mysql_query($sql);
     $sql = "DROP table if exists kategorie";
     mysql_query($sql);
     /*
     if (@mysql_query($sql)) {
        echo("<p>Wpis został dodany</p>");
     } else {
       echo("<p>Problem podczas dodawania wpisu</p>");
     } */
     $sql = "CREATE table uzytkownicy (
          uzk_id int not null auto_increment primary key,
          uzk_login varchar(32),
          uzk_haslo varchar(32))";
     mysql_query($sql);
     $sql = "CREATE table kategorie (
          kat_id int not null auto_increment primary key,
          kat_nazwa varchar(32))";
     mysql_query($sql);
     $sql = "CREATE table priorytety (
          pri_id int not null auto_increment primary key,
          pri_nazwa varchar(32),
          pri_wartosc int)";
     mysql_query($sql);
     $sql = "CREATE table zadania (
          zad_id int not null auto_increment primary key,
          zad_tytul varchar(255),
          zad_opis text,
          zad_postep int,
          zad_data_rozpoczecia datetime,
          zad_data_zakonczenia datetime,
          zad_powiadomienie int,
          zad_pri_id int,
          zad_kat_id int,
          zad_uzk_id int,
          constraint priorytet foreign key(zad_pri_id) references priorytety(pri_id),
          constraint kategoria foreign key(zad_kat_id) references kategorie(kat_id),
          constraint foreign key(zad_uzk_id) references uzytkownicy(uzk_id))";
     mysql_query($sql);
     $sql = "INSERT into priorytety(pri_nazwa, pri_wartosc) VALUES('niski', 3)";
     mysql_query($sql);
     $sql = "INSERT into priorytety(pri_nazwa, pri_wartosc) VALUES('sredni', 2)";
     mysql_query($sql);
     $sql = "INSERT into priorytety(pri_nazwa, pri_wartosc) VALUES('wysoki', 1)";
     mysql_query($sql);
     $sql = "INSERT into kategorie(kat_nazwa) VALUES('dom')";
     mysql_query($sql);
     $sql = "INSERT into kategorie(kat_nazwa) VALUES('praca')";
     mysql_query($sql);
     $sql = "INSERT into kategorie(kat_nazwa) VALUES('szkoła')";
     mysql_query($sql);
     $sql = "INSERT into kategorie(kat_nazwa) VALUES('inne')";
     mysql_query($sql);
?>
</BODY>
</HTML>

