<?PHP
      require_once 'globals.php';
//uzytkownik i haslo za pomoca ktorych laczymy sie z baza
$login = XLOGIN;
$pass = XPASSWD;

//------------------------------------------------------------------------------
/*funkcja laczy sie z baza danych
$login - login uzytkwoniak bazy danych
$haslo - haslo uzytkownika bazy danych :)
*/
function polacz($login, $haslo)
{
 $dbcnx = @mysql_connect(XHOST, $login, $haslo);
 if (!$dbcnx) {
   exit('<p>Nie mo¿na skontaktowaæ siê w tej chwili z serwerem bazy danych.</p>');
 }
 if (!@mysql_select_db(XDB)) {
   exit('<p>Nie mo¿na w tej chwili zlokalizowaæ bazy danych.</p>');
 }
 return $dbcnx;
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funkcja zamienia miesiac podany jakos napis po polsku
na odpowiadajaca mu liczbe (w formie napisu)
$m - zamnieniany napis*/
function mies_naz2num($m)
{
 switch ($m) {
        case 'Styczeñ':
             return '01';
        case 'Luty':
             return '02';
        case 'Marzec':
             return '03';
        case 'Kwiecieñ':
             return '04';
        case 'Maj':
             return '05';
        case 'Czerwiec':
             return '06';
        case 'Lipiec':
             return '07';
        case 'Sierpieñ':
             return '08';
        case 'Wrzesieñ':
             return '09';
        case 'Pa¼dziernik':
             return '10';
        case 'Listopad':
             return '11';
        case 'Grudzieñ':
             return '12';
 }
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funckja zamieniajaca miesiac podany jako liczba
na napis po polsku
*/
function mies_num2naz($m)
{
 switch ($m) {
        case 1:
             return 'Styczeñ';
        case 2:
             return 'Luty';
        case 3:
             return 'Marzec';
        case 4:
             return 'Kwiecieñ';
        case 5:
             return 'Maj';
        case 6:
             return 'Czerwiec';
        case 7:
             return 'Lipiec';
        case 8:
             return 'Sierpieñ';
        case 9:
             return 'Wrzesieñ';
        case 10:
             return 'Pa¼dziernik';
        case 11:
             return 'Listopad';
        case 12:
             return 'Grudzieñ';
 }
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funkcja zamienia date do odpowiedniego formatu sql(datetime)
$r - rok
$m - miesiac
$d - dzien
*/
function data_sql($r, $m, $d)
{
 if (strlen($d) == 1)
    $d = '0'.$d;
 return $r.'-'.mies_naz2num($m).'-'.$d.' 00:00:00';
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funkcja zwraca biezaca date w formacie
Dzien tygodnia, dzien miesiac(nazwa) rok  - po polsku
*/
function data_napis()
{
 $d = date("w");
 switch ($d) {
        case 0:
             return 'Niedziela, '. date("j").' '.mies_num2naz(date(m)).' '.date("Y");
        case 1:
             return 'Poniedzia³ek, '. date("j").' '.mies_num2naz(date(m)).' '.date("Y");
        case 2:
             return 'Wtorek, '. date("j").' '.mies_num2naz(date(m)).' '.date("Y");
        case 3:
             return '¦roda, '. date("j").' '.mies_num2naz(date(m)).' '.date("Y");
        case 4:
             return 'Czwartek, '. date("j").' '.mies_num2naz(date(m)).' '.date("Y");
        case 5:
             return 'Pi±tek, '. date("j").' '.mies_num2naz(date(m)).' '.date("Y");
        case 6:
             return 'Sobota, '. date("j").' '.mies_num2naz(date(m)).' '.date("Y");
 }
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funkcja rysujaca czesc kalendarza (tabele) z rokiem i nazwami miesiecy
$r - rok
$m - miesiac
$d - dzien
*/
function kalendarz_m($d, $m ,$r)
{
 echo '<table border>';
 //pierwszy wiersz tabeli
 echo '<tr>';
      //"strzalka w lewo" - rok do tylu
      echo '<th><a href="kalendarz.php?rok=';
      echo $r - 1;
      echo '&amp;mies=';
      echo $m;
      echo '&amp;dzien=';
      echo $d;
      echo '"><-</a></th>';
      //wyswietlenie roku
      echo '<th>'.$r.'</th>';
      //"strzalka w prawo" - rok do przodu
      echo '<th><a href="kalendarz.php?rok=';
      echo $r + 1;
      echo '&amp;mies=';
      echo $m;
      echo '&amp;dzien=';
      echo $d;
      echo '">-></a></th>';
 echo '</tr>';
 //kolejne nazwy miesiecy
 for ($i = 1; $i < 13; $i++) {
     echo '<tr><th colspan=3>';
     echo '<a href="kalendarz.php?rok=';
     echo $r;
     echo '&amp;mies=';
     echo $i;
     echo '&amp;dzien=';
     echo $d;
     echo '">'.mies_num2naz($i).'</a>';
     echo '</th></tr>';
 }
 echo '</table>';
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funkcja rysujaca kalendarz (dni miesiaca)
$m - miesiac
$r - rok
$d - dzien
*/
function kalendarz_d($d, $m, $r)
{
 $ts = mktime(0,0,0,$m,1,$r);//ts timestamp dla 1 dnia miesiaca i roku przekazanego jako parametry
 $dzien_tyg = date("w",$ts); //dzien tygodnia od ktorego zaczyna sie miesiac
 if ($dzien_tyg == 0) {
    $dzien_tyg = 7;          //dla niedzieli zamiast 0 to 7
 }
 
 echo '<table border width=100%>';
 //pierwszy wiersz tabeli
 echo '<tr>';
 //"strzalka w lewo" - miesiac do tylu
 $p1 = $m;
 $p2 = $r;
 $p1 -= 1;
 if ($p1 == 0) {
    $p1 = 12;
    $p2 -= 1;
 }
 echo '<th colspan=7><a href="kalendarz.php?rok=';
      echo $p2;
      echo '&amp;mies=';
      echo $p1;
      echo '&amp;dzien=';
      echo $d;
      echo '"><-</a>';
 //wyswietlenie miesiaca i roku
 echo ' '.mies_num2naz($m).' '.$r.' ';
 //"strzalka w prawo" - rok do przodu
 $p1 = $m;
 $p2 = $r;
 $p1 += 1;
 if ($p1 == 13) {
    $p1 = 1;
    $p2 += 1;
 }
 echo '<a href="kalendarz.php?rok=';
      echo $p2;
      echo '&amp;mies=';
      echo $p1;
      echo '&amp;dzien=';
      echo $d;
      echo '">-></a></th>';
 echo '</tr>';
 //czesc tabeli z dniami miesiaca
 for ($i=0; $i<7; $i++) {
     echo '<tr>';
     //pierwsza kolumna zawierajaca dni tygodnia
     echo '<th width=10%>';
     switch ($i) {
            case 0:
                 echo 'PN';
                 break;
            case 1:
                 echo 'WT';
                 break;
            case 2:
                 echo '¦R';
                 break;
            case 3:
                 echo 'CZ';
                 break;
            case 4:
                 echo 'PT';
                 break;
            case 5:
                 echo 'SO';
                 break;
            case 6:
                 echo 'N';
                 break;
     }
     echo '</th>';
     //kolejnych 6 kolumn zawierajacych dni miesiaca
     for ($j=0; $j<6; $j++) {
         //wyliczenie dnia dla danej komorki tabeli
         $pom = 7*$j + ($i +2 - $dzien_tyg);
         //jesli dzien jest mniejszy od 1 to -
         if ($pom < 1) {
            $pom = '-';
         }
         //dla Stycznia, Marca, Maja, Lipca, Sierpnia, Pa¼dziernika, Grudnia
         //dzien nie moze byc wiekszy od 31
         if ($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12) {
            if ($pom > 31) {
               $pom = '-';
            }
         //dla Kwietnia, Czerwca, Wrze¶nia, Listopada
         //dzien nie moze byc wiekszy od 30
         } else if ($m == 4 || $m == 6 || $m == 9 || $m == 11) {
           if ($pom > 30) {
              $pom = '-';
           }
         //dla Lutego
         } else if ($m == 2) {
           $przestepny = date("L",$ts);
           //jesli nie ma roku przestepnego
           //dzien nie moze byc wiekszy niz 28
           if ($przestepny == 0 && $pom > 28) {
              $pom = '-';
           //jesli jest rok przestepny
           //dzien nie moze byc wiekszy niz 29
           } else if ($przestepny == 1 && $pom > 29) {
             $pom = '-';
           }
         }
         echo '<td width=15%>';
         //wyswietlenie dnia miesiaca
         if ($pom == '-') {
            echo $pom;
         } else {
           echo '<a href="dzien.php?rok=';
           echo $r;
           echo '&amp;mies=';
           echo $m;
           echo '&amp;dzien=';
           echo $pom;
           echo '">';
           echo '     '.$pom.'</a>';
         }

         //zapytanie ktore zwroca liczbe zadan na dany dzien
         if ($pom != '-') {
            $pom = intval($pom);
            //tutaj nalezy wyciagnac id zalogowanego uzytkownika
            $uzk_id = $GLOBALS['uzk_id'];
            $data_ts = data_sql($r, mies_num2naz($m), $pom);
            $sql = "SELECT count(*) as ile_zad
                     FROM zadania
                     WHERE zad_uzk_id='$uzk_id' and zad_cyklicznosc=0 and
                           zad_data_rozpoczecia<='$data_ts' and zad_data_zakonczenia>='$data_ts'";
            $res = @mysql_query($sql, $GLOBALS['dbcnx']);
            if (!$res) {
               exit('<p>Problem z pobraniem danych</p>');
            }
            $res = mysql_fetch_array($res);
            $ile_z = $res['ile_zad'];
            //dla cyklicznosci "co miesiac"
            $sql = "SELECT count(*) as ile_zad
                    FROM zadania
                    WHERE zad_uzk_id='$uzk_id' and
                          zad_cyklicznosc=1 and zad_data_cyklicznosc>='$data_ts' and
                          zad_data_rozpoczecia<='$data_ts' and
                          DAYOFMONTH(zad_data_rozpoczecia)<=DAYOFMONTH(zad_data_zakonczenia) and
                          DAYOFMONTH(zad_data_rozpoczecia)<='$pom' and
                          DAYOFMONTH(zad_data_zakonczenia)>='$pom'";
            $res = @mysql_query($sql, $GLOBALS['dbcnx']);
            if (!$res) {
               exit('<p>Problem z pobraniem danych</p>');
            }
            $res = mysql_fetch_array($res);
            $ile_z += $res['ile_zad'];

            $sql = "SELECT count(*) as ile_zad
                    FROM zadania
                    WHERE zad_uzk_id='$uzk_id' and
                          zad_cyklicznosc=1 and zad_data_cyklicznosc>='$data_ts' and
                          zad_data_rozpoczecia<='$data_ts' and
                          DAYOFMONTH(zad_data_rozpoczecia)>DAYOFMONTH(zad_data_zakonczenia) and
                          (DAYOFMONTH(zad_data_rozpoczecia)<='$pom' or
                          DAYOFMONTH(zad_data_zakonczenia)>='$pom')";
            $res = @mysql_query($sql, $GLOBALS['dbcnx']);
            if (!$res) {
               exit('<p>Problem z pobraniem danych</p>');
            }
            $res = mysql_fetch_array($res);
            $ile_z += $res['ile_zad'];
            //dla cyklicznosci "co rok"
            $sql = "SELECT count(*) as ile_zad
                    FROM zadania
                    WHERE zad_uzk_id='$uzk_id' and
                          zad_cyklicznosc=2 and zad_data_cyklicznosc>='$data_ts' and
                          zad_data_rozpoczecia<='$data_ts' and
                          YEAR(zad_data_rozpoczecia)=YEAR(zad_data_zakonczenia) and
                          SUBDATE(zad_data_rozpoczecia, INTERVAL YEAR(zad_data_rozpoczecia) YEAR)<=SUBDATE('$data_ts', INTERVAL YEAR('$data_ts') YEAR) and
                          SUBDATE(zad_data_zakonczenia, INTERVAL YEAR(zad_data_rozpoczecia) YEAR)>=SUBDATE('$data_ts', INTERVAL YEAR('$data_ts') YEAR)";
            $res = @mysql_query($sql, $GLOBALS['dbcnx']);
            if (!$res) {
               exit('<p>Problem z pobraniem danych</p>');
            }
            $res = mysql_fetch_array($res);
            $ile_z += $res['ile_zad'];
            
            $sql = "SELECT count(*) as ile_zad
                    FROM zadania
                    WHERE zad_uzk_id='$uzk_id' and
                          zad_cyklicznosc=2 and zad_data_cyklicznosc>='$data_ts' and
                          zad_data_rozpoczecia<='$data_ts' and
                          YEAR(zad_data_rozpoczecia)<YEAR(zad_data_zakonczenia) and
                          ((SUBDATE(zad_data_rozpoczecia, INTERVAL YEAR(zad_data_rozpoczecia) YEAR)<=SUBDATE('$data_ts', INTERVAL YEAR('$data_ts') YEAR) and
                          '0001-01-01 00:00:00'>=SUBDATE('$data_ts', INTERVAL YEAR('$data_ts') YEAR)) or
                          (SUBDATE(zad_data_zakonczenia, INTERVAL YEAR(zad_data_zakonczenia) YEAR)>=SUBDATE('$data_ts', INTERVAL YEAR('$data_ts') YEAR) and
                          '0000-01-01 00:00:00'<=SUBDATE('$data_ts', INTERVAL YEAR('$data_ts') YEAR)))";
            $res = @mysql_query($sql, $GLOBALS['dbcnx']);
            if (!$res) {
               exit('<p>Problem z pobraniem danych</p>');
            }
            $res = mysql_fetch_array($res);
            $ile_z += $res['ile_zad'];
            //jesli liczba zadan na dany dzien jest rozna od 0 to wyswietl ta liczbe
            if ($ile_z != 0) {
               echo ' '.$ile_z.'zad';
            }
         }
         echo '</td>';
     }
     echo '</tr>';
 }
 echo '<tr>';
      //komorka tabeli zawierajaca link do strony z dodawaniem zadan
      echo '<td colspan=7><a href="dod_form.php?rok=';
      echo $r;
      echo '&amp;mies=';
      echo $m;
      echo '&amp;dzien=';
      echo $d;
      echo '">Dodaj zadanie</a></td>';
 echo '</tr>';
 echo '</table>';
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funckja dodaje zadanie do bazy danych
$ed = 0 - dodawanie, 1- edycja
*/
function dodaj($ed)
{
 $tytul = $_POST['tytul'];
 $opis = $_POST['opis'];
 $powiadomienie = $_POST['pow'];
 $dzien_r = $_POST['dzien_r'];
 $miesiac_r = $_POST['miesiac_r'];
 $rok_r = $_POST['rok_r'];
 $dzien_z = $_POST['dzien_z'];
 $miesiac_z = $_POST['miesiac_z'];
 $rok_z = $_POST['rok_z'];
 $kategoria = $_POST['kat'];
 $cyklicznosc = $_POST['cykl'];
 $dzien_c = $_POST['dzien_c'];
 $miesiac_c = $_POST['miesiac_c'];
 $rok_c = $_POST['rok_c'];
 

 
 $id_e = $_POST['id_e'];

 if ($powiadomienie=='SMS') {
    $powiadomienie = 1;
 } else {
   $powiadomienie = 0;
 }
 
  if ($cyklicznosc == 'co miesi±c') {
    $cyklicznosc = 1;
 } else if ($cyklicznosc == 'co rok') {
   $cyklicznosc = 2;
 } else {
   $cyklicznosc = 0;
 }

 //sprawdzenie czy daty sa poprawne
 $m_r = mies_naz2num($miesiac_r);
 $m_z = mies_naz2num($miesiac_z);
 $m_c = mies_naz2num($miesiac_c);
 if (!checkdate($m_r, $dzien_r, $rok_r)) {
    exit('Z³a data rozpoczêcia');
 }
 if (!checkdate($m_z, $dzien_z, $rok_z)) {
    exit('Z³a data zakoñczenia');
 }
 $data_r = mktime(0, 0, 0, $m_r, $dzien_r, $rok_r);
 $data_z = mktime(0, 0, 0, $m_z, $dzien_z, $rok_z);
 $data_c = mktime(0, 0, 0, $m_c, $dzien_c, $rok_c);
 if ($data_z < $data_r) {
    exit('Data zakoñczenia jest wcze¶niejsza ni¿ data rozpoczêcia');
 }
 $data_b = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
 if ($data_z < $data_b) {
    exit('Data zakoñczenia jest wcze¶niejsza ni¿ data bie¿±ca');
 }
 
 if($cyklicznosc == 1) {
    if(!(($m_z==$m_r && $rok_r==$rok_z) || ($m_z==$m_r+1 && $rok_z==$rok_r && $dzien_z<$dzien_r) || ($m_z==1 && $m_r==12 && $rok_z==$rok_r+1 && $dzien_z<$dzien_r))) {
      exit('Przy cykliczno¶ci: <i>co miesi±c</i> zadanie nie mo¿e trwaæ ponad miesi±c');
    }
 } else if($cyklicznosc == 2) {
   if(!(($rok_z==$rok_r) || ($rok_z==$rok_r+1 && $m_z<$m_r) || ($rok_z==$rok_r+1 && $m_z==$m_r && $dzien_z<$dzien_r))) {
     exit('Przy cykliczno¶ci: <i>co rok</i> zadanie nie mo¿e trwaæ ponad rok');
   }
 }
 
 $data_r = data_sql($rok_r, $miesiac_r, $dzien_r);
 $data_z = data_sql($rok_z, $miesiac_z, $dzien_z);
 $data_c = data_sql($rok_c, $miesiac_c, $dzien_c);

 //tutaj trzeba wyciagnac id zalogowanego uzytkownika
 $uzk_id = $GLOBALS['uzk_id'];

 $sql = "SELECT kat_id
         FROM kategorie
         WHERE kat_nazwa='$kategoria'";
 $res = @mysql_query($sql, $GLOBALS['dbcnx']);
 $res = mysql_fetch_array($res);
 $kat = $res['kat_id'];
 


 //jesli jest edycja
 if ($ed) {
    $sql = "UPDATE zadania SET
                   zad_tytul='$tytul',
                   zad_opis='$opis',
                   zad_data_rozpoczecia='$data_r',
                   zad_data_zakonczenia='$data_z',
                   zad_powiadomienie='$powiadomienie',
                   zad_kat_id='$kat'
            WHERE zad_id='$id_e'";
 //jest dodawanie
 } else {
   $sql = "INSERT INTO zadania(zad_tytul, zad_opis,
                     zad_data_rozpoczecia, zad_data_zakonczenia,
                     zad_powiadomienie, zad_cyklicznosc, zad_data_cyklicznosc,
                     zad_kat_id, zad_uzk_id)
           VALUES('$tytul', '$opis', '$data_r', '$data_z', '$powiadomienie',
                '$cyklicznosc', '$data_c', '$kat', '$uzk_id')";
 }
 if (!@mysql_query($sql, $GLOBALS['dbcnx'])) {
   echo("<p>Problem podczas dodawania wpisu</p>");
 }
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*
funkcja rysuje tabelke z zadaniami dla danego dnia
*/
function dzien_tbl($dzien, $miesiac, $rok)
{

         $url = 'dzien.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien;
         $url_z = 'zadanie.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien;

     //tabela z danymi
     echo '<table border width=100%>';
          echo '<tr>';
               echo '<th colspan=6>';
               //"strzalka w lewo"
               $r = $rok;
               $m = $miesiac;
               $d = $dzien;
               $d -= 1;
               $ts = mktime(0,0,0,$m,1,$r);
               if ($d == 0) {
                  $m -= 1;
                  if ($m == 0) {
                     $r -=1;
                     $m = 12;
                  }
                  //dla Stycznia, Marca, Maja, Lipca, Sierpnia, Pa¼dziernika, Grudnia
                  if ($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12) {
                     $d = 31;
                  //dla Kwietnia, Czerwca, Wrze¶nia, Listopada
                  } else if ($m == 4 || $m == 6 || $m == 9 || $m == 11) {
                     $d = 30;
                  //dla Lutego
                  } else if ($m == 2) {
                     $przestepny = date("L",$ts);
                     //jesli nie ma roku przestepnego
                     if (!$przestepny) {
                        $d = 28;
                     //jesli jest rok przestepny
                     } else {
                        $d = 29;
                     }
                  }
               }
               echo '<a href="dzien.php?rok=';
               echo $r;
               echo '&amp;mies=';
               echo $m;
               echo '&amp;dzien=';
               echo $d;
               echo '"><-</a>';
               //naglowek z data
               echo " $dzien ".mies_num2naz($miesiac)." $rok ";
               //strzalka w prawo
               $r = $rok;
               $m = $miesiac;
               $d = $dzien;
               $d += 1;
               //dla Stycznia, Marca, Maja, Lipca, Sierpnia, Pa¼dziernika, Grudnia
               if ($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12) {
                  if ($d == 32) {
                     $d = 1;
                  }
               //dla Kwietnia, Czerwca, Wrze¶nia, Listopada
               } else if ($m == 4 || $m == 6 || $m == 9 || $m == 11) {
                  if ($d == 31) {
                     $d = 1;
                  }
               //dla Lutego
               } else if ($m == 2) {
                 $przestepny = date("L",$ts);
                 //jesli nie ma roku przestepnego
                 if (!$przestepny && $d == 29) {
                    $d = 1;
                 //jesli jest rok przestepny
                 } else if ($przestepny && $d == 30) {
                    $d = 1;
                 }
               }
               if ($d == 1) {
                  $m += 1;
                  if ($m == 13) {
                     $m = 1;
                     $r += 1;
                  }
               }
               $ts = mktime(0,0,0,$m,1,$r);
               echo '<a href="dzien.php?rok=';
               echo $r;
               echo '&amp;mies=';
               echo $m;
               echo '&amp;dzien=';
               echo $d;
               echo '">-></a>';
               echo '</th>';
          echo '</tr>';
          //naglowki tabeli
          echo '<tr>';
               echo '<th width=10%>L.p.</th>';
               echo '<th width=30%>Tytu³</th>';
               echo '<th width=15%>Kategoria</th>';
               echo '<th width=15%>Powiadomienie</th>';
               echo '<th width=15% >Cykliczno¶æ</th>';
               echo '<th width=15%>Opcje</th>';
          echo '</tr>';

          //tutaj powinno byc id zalogowanego uzytkownika
          $uzk_id = $GLOBALS['uzk_id'];
          $data = data_sql($rok, mies_num2naz($miesiac), $dzien);
          $sql = "SELECT z. zad_id as id, z.zad_tytul as tytul, k.kat_nazwa as kategoria, z.zad_powiadomienie as pow, z.zad_cyklicznosc as cykl
                  FROM zadania z, kategorie k
                  WHERE z.zad_kat_id=k.kat_id and z.zad_uzk_id='$uzk_id'
                        and z.zad_cyklicznosc=0
                        and z.zad_data_rozpoczecia<='$data' and z.zad_data_zakonczenia>='$data'";
          $wyniki = mysql_query($sql, $GLOBALS['dbcnx']);
          $i = 1;
          while ($wynik = mysql_fetch_array($wyniki)) {
                echo '<tr>';
                     echo '<td>'.$i.'</td>';
                     //tutaj bedzie link do pelnego ogladniecia
                     echo '<td>';
                     echo '<a href='.$url_z.'&amp;zad_id='.$wynik['id'].'>'.$wynik['tytul'].'</a>';
                     echo '</td>';
                     echo '<td>'.$wynik['kategoria'].'</td>';
                     echo '<td>';
                     if ($wynik['pow'] == 0) {
                        echo 'Nie';
                     } else {
                       echo 'Tak';
                     }
                     echo '</td>';
                     echo '<td>';
                     if ($wynik['cykl'] == 0) {
                        echo '---';
                     } else if ($wynik['cykl'] == 1) {
                       echo 'co miesi±c';
                     } else {
                       echo 'co rok';
                     }
                     echo '</td>';
                     //tutaj beda linki do edycji i usuniecia
                     $edycja = 'dod_form.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien.'&amp;id_e='.$wynik['id'];
                     echo '<td>';
                     echo '<a href='.$edycja.'>Edytuj</a> ';
                     echo '<a href='.$url.'&amp;id_u='.$wynik['id'].'>Usuñ</a>';
                     echo'</td>';
                echo '</tr>';
                $i++;
          }
          //cyklicznosc co miesiac
          $sql = "SELECT z. zad_id as id, z.zad_tytul as tytul, k.kat_nazwa as kategoria, z.zad_powiadomienie as pow, z.zad_cyklicznosc as cykl
                  FROM zadania z, kategorie k
                  WHERE z.zad_uzk_id='$uzk_id' and z.zad_kat_id=k.kat_id and
                          z.zad_cyklicznosc=1 and z.zad_data_cyklicznosc>='$data' and
                          z.zad_data_rozpoczecia<='$data' and
                          DAYOFMONTH(z.zad_data_rozpoczecia)<=DAYOFMONTH(z.zad_data_zakonczenia) and
                          DAYOFMONTH(z.zad_data_rozpoczecia)<='$dzien' and
                          DAYOFMONTH(z.zad_data_zakonczenia)>='$dzien'";
          $wyniki = mysql_query($sql, $GLOBALS['dbcnx']);
          while ($wynik = mysql_fetch_array($wyniki)) {
                echo '<tr>';
                     echo '<td>'.$i.'</td>';
                     //tutaj bedzie link do pelnego ogladniecia
                     echo '<td>';
                     echo '<a href='.$url_z.'&amp;zad_id='.$wynik['id'].'>'.$wynik['tytul'].'</a>';
                     echo '</td>';
                     echo '<td>'.$wynik['kategoria'].'</td>';
                     echo '<td>';
                     if ($wynik['pow'] == 0) {
                        echo 'Nie';
                     } else {
                       echo 'Tak';
                     }
                     echo '</td>';
                     echo '<td>';
                     if ($wynik['cykl'] == 0) {
                        echo '---';
                     } else if ($wynik['cykl'] == 1) {
                       echo 'co miesi±c';
                     } else {
                       echo 'co rok';
                     }
                     echo '</td>';
                     //tutaj beda linki do edycji i usuniecia
                     $edycja = 'dod_form.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien.'&amp;id_e='.$wynik['id'];
                     echo '<td>';
                     echo '<a href='.$edycja.'>Edytuj</a> ';
                     echo '<a href='.$url.'&amp;id_u='.$wynik['id'].'>Usuñ</a>';
                     echo'</td>';
                echo '</tr>';
                $i++;
          }
          $sql = "SELECT z. zad_id as id, z.zad_tytul as tytul, k.kat_nazwa as kategoria, z.zad_powiadomienie as pow, z.zad_cyklicznosc as cykl
                  FROM zadania z, kategorie k
                  WHERE z.zad_uzk_id='$uzk_id' and z.zad_kat_id=k.kat_id and
                          z.zad_cyklicznosc=1 and z.zad_data_cyklicznosc>='$data' and
                          z.zad_data_rozpoczecia<='$data' and
                          DAYOFMONTH(z.zad_data_rozpoczecia)>DAYOFMONTH(z.zad_data_zakonczenia) and
                          (DAYOFMONTH(z.zad_data_rozpoczecia)<='$dzien' or
                          DAYOFMONTH(z.zad_data_zakonczenia)>='$dzien')";
          $wyniki = mysql_query($sql, $GLOBALS['dbcnx']);
          while ($wynik = mysql_fetch_array($wyniki)) {
                echo '<tr>';
                     echo '<td>'.$i.'</td>';
                     //tutaj bedzie link do pelnego ogladniecia
                     echo '<td>';
                     echo '<a href='.$url_z.'&amp;zad_id='.$wynik['id'].'>'.$wynik['tytul'].'</a>';
                     echo '</td>';
                     echo '<td>'.$wynik['kategoria'].'</td>';
                     echo '<td>';
                     if ($wynik['pow'] == 0) {
                        echo 'Nie';
                     } else {
                       echo 'Tak';
                     }
                     echo '</td>';
                     echo '<td>';
                     if ($wynik['cykl'] == 0) {
                        echo '---';
                     } else if ($wynik['cykl'] == 1) {
                       echo 'co miesi±c';
                     } else {
                       echo 'co rok';
                     }
                     echo '</td>';
                     //tutaj beda linki do edycji i usuniecia
                     $edycja = 'dod_form.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien.'&amp;id_e='.$wynik['id'];
                     echo '<td>';
                     echo '<a href='.$edycja.'>Edytuj</a> ';
                     echo '<a href='.$url.'&amp;id_u='.$wynik['id'].'>Usuñ</a>';
                     echo'</td>';
                echo '</tr>';
                $i++;
          }
          //cyklicznosc co rok
          $sql = "SELECT z. zad_id as id, z.zad_tytul as tytul, k.kat_nazwa as kategoria, z.zad_powiadomienie as pow, z.zad_cyklicznosc as cykl
                  FROM zadania z, kategorie k
                  WHERE z.zad_kat_id=k.kat_id and z.zad_uzk_id='$uzk_id' and
                        z.zad_cyklicznosc=2 and z.zad_data_cyklicznosc>='$data' and
                        z.zad_data_rozpoczecia<='$data' and
                        YEAR(z.zad_data_rozpoczecia)=YEAR(z.zad_data_zakonczenia) and
                        SUBDATE(z.zad_data_rozpoczecia, INTERVAL YEAR(z.zad_data_rozpoczecia) YEAR)<=SUBDATE('$data', INTERVAL YEAR('$data') YEAR) and
                        SUBDATE(z.zad_data_zakonczenia, INTERVAL YEAR(z.zad_data_rozpoczecia) YEAR)>=SUBDATE('$data', INTERVAL YEAR('$data') YEAR)";
          $wyniki = mysql_query($sql, $GLOBALS['dbcnx']);
          while ($wynik = mysql_fetch_array($wyniki)) {
                echo '<tr>';
                     echo '<td>'.$i.'</td>';
                     //tutaj bedzie link do pelnego ogladniecia
                     echo '<td>';
                     echo '<a href='.$url_z.'&amp;zad_id='.$wynik['id'].'>'.$wynik['tytul'].'</a>';
                     echo '</td>';
                     echo '<td>'.$wynik['kategoria'].'</td>';
                     echo '<td>';
                     if ($wynik['pow'] == 0) {
                        echo 'Nie';
                     } else {
                       echo 'Tak';
                     }
                     echo '</td>';
                     echo '<td>';
                     if ($wynik['cykl'] == 0) {
                        echo '---';
                     } else if ($wynik['cykl'] == 1) {
                       echo 'co miesi±c';
                     } else {
                       echo 'co rok';
                     }
                     echo '</td>';
                     //tutaj beda linki do edycji i usuniecia
                     $edycja = 'dod_form.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien.'&amp;id_e='.$wynik['id'];
                     echo '<td>';
                     echo '<a href='.$edycja.'>Edytuj</a> ';
                     echo '<a href='.$url.'&amp;id_u='.$wynik['id'].'>Usuñ</a>';
                     echo'</td>';
                echo '</tr>';
                $i++;
          }
          $sql = "SELECT z. zad_id as id, z.zad_tytul as tytul, k.kat_nazwa as kategoria, z.zad_powiadomienie as pow, z.zad_cyklicznosc as cykl
                  FROM zadania z, kategorie k
                  WHERE z.zad_kat_id=k.kat_id and z.zad_uzk_id='$uzk_id' and
                        z.zad_cyklicznosc=2 and z.zad_data_cyklicznosc>='$data' and
                        z.zad_data_rozpoczecia<='$data' and
                        YEAR(z.zad_data_rozpoczecia)<YEAR(z.zad_data_zakonczenia) and
                        ((SUBDATE(z.zad_data_rozpoczecia, INTERVAL YEAR(z.zad_data_rozpoczecia) YEAR)<=SUBDATE('$data', INTERVAL YEAR('$data') YEAR) and
                        '0001-01-01 00:00:00'>=SUBDATE('$data', INTERVAL YEAR('$data') YEAR)) or
                        (SUBDATE(z.zad_data_zakonczenia, INTERVAL YEAR(z.zad_data_zakonczenia) YEAR)>=SUBDATE('$data', INTERVAL YEAR('$data') YEAR) and
                        '0000-01-01 00:00:00'<=SUBDATE('$data', INTERVAL YEAR('$data') YEAR)))";
          $wyniki = mysql_query($sql, $GLOBALS['dbcnx']);
          while ($wynik = mysql_fetch_array($wyniki)) {
                echo '<tr>';
                     echo '<td>'.$i.'</td>';
                     //tutaj bedzie link do pelnego ogladniecia
                     echo '<td>';
                     echo '<a href='.$url_z.'&amp;zad_id='.$wynik['id'].'>'.$wynik['tytul'].'</a>';
                     echo '</td>';
                     echo '<td>'.$wynik['kategoria'].'</td>';
                     echo '<td>';
                     if ($wynik['pow'] == 0) {
                        echo 'Nie';
                     } else {
                       echo 'Tak';
                     }
                     echo '</td>';
                     echo '<td>';
                     if ($wynik['cykl'] == 0) {
                        echo '---';
                     } else if ($wynik['cykl'] == 1) {
                       echo 'co miesi±c';
                     } else {
                       echo 'co rok';
                     }
                     echo '</td>';
                     //tutaj beda linki do edycji i usuniecia
                     $edycja = 'dod_form.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien.'&amp;id_e='.$wynik['id'];
                     echo '<td>';
                     echo '<a href='.$edycja.'>Edytuj</a> ';
                     echo '<a href='.$url.'&amp;id_u='.$wynik['id'].'>Usuñ</a>';
                     echo'</td>';
                echo '</tr>';
                $i++;
          }
          //pusty wiersz dla dodawania
          echo '<tr>';
               echo '<td> </td><td> </td><td> </td><td> </td><td> </td>';
               echo '<td><a href="dod_form.php?rok=';
               echo $rok;
               echo '&amp;mies=';
               echo $miesiac;
               echo '&amp;dzien=';
               echo $dzien;
               echo '">Dodaj</a></td>';
     echo '</table>';
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funkcja wyswietla formularz do
dodawania/edycji zadania
*/
function dod_e_form($dzien, $miesiac, $rok, $edycja, $id_e)
{
 $url = 'dzien.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien;
 
 if ($edycja) {
    $sql = "SELECT z.zad_tytul as tytul,
                  z.zad_opis as opis,
                  date_format(z.zad_data_rozpoczecia, '%e') as dzien_r,
                  date_format(z.zad_data_rozpoczecia, '%c') as mies_r,
                  date_format(z.zad_data_rozpoczecia, '%Y') as rok_r,
                  date_format(z.zad_data_zakonczenia, '%e') as dzien_z,
                  date_format(z.zad_data_zakonczenia, '%c') as mies_z,
                  date_format(z.zad_data_zakonczenia, '%Y') as rok_z,
                  z.zad_powiadomienie as pow,
                  k.kat_nazwa as kategoria,
                  date_format(z.zad_data_cyklicznosc, '%e') as dzien_c,
                  date_format(z.zad_data_cyklicznosc, '%c') as mies_c,
                  date_format(z.zad_data_cyklicznosc, '%Y') as rok_c,
                  z.zad_cyklicznosc as cykl
           FROM zadania z, kategorie k
           WHERE z.zad_id='$id_e' and
                 z.zad_kat_id=k.kat_id";
      $res = mysql_query($sql, $GLOBALS['dbcnx']);
      $res = mysql_fetch_array($res);
 }
 
echo '<form action="';
echo $url;
echo '" method=post NAME="formularz" onsubmit="';
echo "if (document.formularz.tytul.value == '') { alert('Proszê wype³niæ Tytu³!'); return false }";
echo '">';
      echo '<table>';
      echo '<tr><th align=left>Data rozpoczecia:</th>';
          echo '<td><select name=dzien_r>';
                        //jesli jest dodawanie
                        if (!$edycja) {
                           for ($i = 1; $i < 32; $i++) {
                               if ($i == $dzien) {
                                  echo("<option selected> $i");
                               } else {
                                 echo("<option> $i");
                               }
                           }
                        //jesli jest edycja
                        } else {
                          for ($i = 1; $i < 32; $i++) {
                               if ($i == $res['dzien_r']) {
                                  echo("<option selected> $i");
                               } else {
                                 echo("<option> $i");
                               }
                           }
                        }
                        echo '</select>';
                        echo '<select name=miesiac_r>';
                        //jesli jest dodawanie
                        if (!$edycja) {
                           for ($i = 1; $i < 13; $i++) {
                               if ($i == $miesiac) {
                                  echo '<option selected>'.mies_num2naz($i);
                               } else {
                                 echo '<option>'.mies_num2naz($i);
                                 }
                           }
                        //jesli jest edycja
                        } else {
                          for ($i = 1; $i < 13; $i++) {
                               if ($i == $res['mies_r']) {
                                  echo '<option selected>'.mies_num2naz($i);
                               } else {
                                 echo '<option>'.mies_num2naz($i);
                                 }
                           }
                        }
                        echo '</select>';
                        echo '<input name="rok_r" value=';
                        //jesli jest edycja
                        if ($edycja) {
                           echo $res['rok_r'];
                        //jesli jest dodawanie
                        } else {
                          echo $rok;
                        }
                        echo ' maxlength=4 size=6> </td>';
      echo '</tr>';
      echo '<tr><th align=left>Data zakoñczenia:</th>';
          echo '<td><select name=dzien_z>';
                        //jesli jest dodawanie
                        if (!$edycja) {
                           for ($i = 1; $i < 32; $i++) {
                               if ($i == $dzien) {
                                  echo("<option selected> $i");
                               } else {
                                 echo("<option> $i");
                               }
                           }
                        //jesli jest edycja
                        } else {
                          for ($i = 1; $i < 32; $i++) {
                               if ($i == $res['dzien_z']) {
                                  echo("<option selected> $i");
                               } else {
                                 echo("<option> $i");
                               }
                           }
                        }
                        echo '</select>';
                        echo '<select name=miesiac_z>';
                        //jesli jest dodawanie
                        if (!$edycja) {
                           for ($i = 1; $i < 13; $i++) {
                               if ($i == $miesiac) {
                                  echo '<option selected>'.mies_num2naz($i);
                               } else {
                                 echo '<option>'.mies_num2naz($i);
                                 }
                           }
                        //jesli jest edycja
                        } else {
                          for ($i = 1; $i < 13; $i++) {
                               if ($i == $res['mies_z']) {
                                  echo '<option selected>'.mies_num2naz($i);
                               } else {
                                 echo '<option>'.mies_num2naz($i);
                                 }
                           }
                        }
                        echo '</select>';
                        echo '<input name="rok_z" value=';
                        //jesli jest edycja
                        if ($edycja) {
                           echo $res['rok_z'];
                        } else {
                          echo $rok;
                        }
                        echo ' maxlength=4 size=6> </td>';
      echo '</tr>';
      echo '<tr><th align=left>Tytu³:</th>';
          echo '<td><input name="tytul"';
                          //jesli jest edycja
                          if ($edycja) {
                             echo  ' value="'.$res['tytul']. '" ';
                          }
                      echo 'maxlength=255 size=50></td>';
      echo '</tr>';
      echo '<tr><th align=left>Kategoria:</th>';
          echo '<td><select name=kat>';
                      $sql1 = "SELECT kat_nazwa
                              FROM kategorie";
                      $res1 = @mysql_query($sql1, $GLOBALS['dbcnx']);
                      while ($kat = mysql_fetch_array($res1)) {
                            if ($kat['kat_nazwa'] == $res['kategoria']) {
                               echo '<option selected>'.$kat['kat_nazwa'];
                            } else {
                              echo '<option>'.$kat['kat_nazwa'];
                            }
                      }
              echo '</select>';
          echo '</td>';
      echo '</tr>';
      echo '<tr><th align=left valign=top>Opis:</th>';
          echo '<td><textarea name="opis" rows=10 cols=50>'.$res['opis'].'</textarea></td>';
      echo '</tr>';
      echo '<tr><th align=left valign=top>Cykliczno¶æ:</th>';
        echo '<td>';
        echo '<table>';
             //dodac jak jest edycja
             echo '<tr><td>Powtarza siê:</td>';
             echo '<td><select name=cykl>';
             //jesli jest dodawanie
             if (!$edycja || $res['cykl'] == 0) {
                echo '<option selected>---------------';
                echo '<option>co miesi±c';
                echo '<option>co rok';
             //jesli dodawanie
             } else {
               if ($res['cykl'] == 1) {
                  echo '<option>---------------';
                  echo '<option selected>co miesi±c';
                  echo '<option>co rok';
               } else {
                  echo '<option>---------------';
                  echo '<option>co miesi±c';
                  echo '<option selected>co rok';
               }
             }
             echo '</select></td></tr>';
             echo '<tr><td>Do dnia:</td>';
             echo '<td><select name=dzien_c>';
                        //jesli jest dodawanie
                        if (!$edycja) {
                           for ($i = 1; $i < 32; $i++) {
                               if ($i == $dzien) {
                                  echo("<option selected> $i");
                               } else {
                                 echo("<option> $i");
                               }
                           }
                        //jesli jest edycja
                        } else {
                          for ($i = 1; $i < 32; $i++) {
                               if ($i == $res['dzien_c']) {
                                  echo("<option selected> $i");
                               } else {
                                 echo("<option> $i");
                               }
                           }
                        }
                        echo '</select>';
                        echo '<select name=miesiac_c>';
                        //jesli jest dodawanie
                        if (!$edycja) {
                           for ($i = 1; $i < 13; $i++) {
                               if ($i == $miesiac) {
                                  echo '<option selected>'.mies_num2naz($i);
                               } else {
                                 echo '<option>'.mies_num2naz($i);
                                 }
                           }
                        //jesli jest edycja
                        } else {
                          for ($i = 1; $i < 13; $i++) {
                               if ($i == $res['mies_c']) {
                                  echo '<option selected>'.mies_num2naz($i);
                               } else {
                                 echo '<option>'.mies_num2naz($i);
                                 }
                           }
                        }
                        echo '</select>';
                        echo '<input name="rok_c" value=';
                        //jesli jest edycja
                        if ($edycja) {
                           echo $res['rok_c'];
                        //jesli jest dodawanie
                        } else {
                          echo $rok;
                        }
                        echo ' maxlength=4 size=6> </td>';
             echo '</tr>';
        echo '</table>';
        echo '</td>';
      echo '</tr>';
      echo '<tr><th align=left>Powiadomienie:</th>';
          echo '<td><input type="checkbox" name="pow" value="SMS"';
                          //jesli jest edycja
                          if ($edycja && $res['pow']) {
                                echo ' checked';
                          }
                     echo '> Tak</td>';
      echo '</tr>';
      echo '<tr><th align=left>';
      echo '<input type="reset" value="Wyczy¶æ">';
      echo '<input type="submit" value="';
      if ($edycja) {
         echo 'Edytuj';
      } else {
        echo 'Dodaj';
      }
      echo '"></th>';
      echo '</tr>';
      echo '</table>';
      if ($edycja) {
         echo '<input type="hidden" name="id_e" value='.$id_e.'>';
      }
echo '</form>';
}
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
/*funkcja wyswietla tabele
ze szczegolami zadania
*/
function zad_tbl($dzien, $miesiac, $rok, $id)
{
 $sql = "SELECT z.zad_tytul as tytul,
                       z.zad_opis as opis,
                       date_format(z.zad_data_rozpoczecia, '%e') as dzien_r,
                       date_format(z.zad_data_rozpoczecia, '%c') as mies_r,
                       date_format(z.zad_data_rozpoczecia, '%Y') as rok_r,
                       date_format(z.zad_data_zakonczenia, '%e') as dzien_z,
                       date_format(z.zad_data_zakonczenia, '%c') as mies_z,
                       date_format(z.zad_data_zakonczenia, '%Y') as rok_z,
                       z.zad_powiadomienie as pow,
                       k.kat_nazwa as kategoria,
                       z.zad_cyklicznosc as cykl,
                       date_format(z.zad_data_cyklicznosc, '%e') as dzien_c,
                       date_format(z.zad_data_cyklicznosc, '%c') as mies_c,
                       date_format(z.zad_data_cyklicznosc, '%Y') as rok_c
                FROM zadania z, kategorie k
                WHERE z.zad_id='$id' and
                      z.zad_kat_id=k.kat_id";
 $res = mysql_query($sql, $GLOBALS['dbcnx']);
 $res = mysql_fetch_array($res);
 
 $url = 'dzien.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien;
 
 $opis = $res['opis'];
  //formatowanie opisu
 $opis = ereg_replace("\r", '', $opis);
 $opis = ereg_replace("\n\n",'<p></p>', $opis);
 $opis = ereg_replace("\n", '<br>', $opis);
 
echo '<table border>';
       echo '<tr>';
           echo '<th align=left>Data rozpoczêcia: </th>';
           echo '<td>';
                echo $res['dzien_r'].' '.mies_num2naz($res['mies_r']).' '.$res['rok_r'];
           echo '</td>';
       echo '</tr>';
       echo '<tr>';
           echo '<th align =left>Data zakoñczenia: </th>';
           echo '<td>';
                echo $res['dzien_z'].' '.mies_num2naz($res['mies_z']).' '.$res['rok_z'];
           echo '</td>';
       echo '</tr>';
       //cyklicznosc
       echo '<tr>';
           echo '<th align=left valign=top>Cykliczno¶æ: </th>';
           if ($res['cykl'] == 0) {
              echo '<td>---</td>';
           } else {
             echo '<td>';
             echo '<table>';
                  echo '<tr><td>Powtarza siê: </td><td>';
                  if ($res['cykl'] == 1) {
                     if ($res['dzien_r'] == $res['dzien_z']) {
                        echo $res['dzien_r'].' ka¿dego miesi±ca</td></tr>';
                     } else {
                       echo $res['dzien_r'].'-'.$res['dzien_z'].' ka¿dego miesi±ca</td></tr>';
                     }
                  } else {
                    if ($res['dzien_r'] == $res['dzien_z'] && $res['mies_r'] == $res['mies_z']) {
                       echo $res['dzien_r'].' '.mies_num2naz($res['mies_r']).' ka¿dego roku</td></tr>';
                    } else {
                      echo $res['dzien_r'].' '.mies_num2naz($res['mies_r']).' - '.$res['dzien_z'].' '.mies_num2naz($res['mies_z']).' ka¿dego roku</td></tr>';
                    }
                  }
                  echo '<tr><td>Do dnia: </td>';
                  echo '<td>'.$res['dzien_c'].' '.mies_num2naz($res['mies_c']).' '.$res['rok_c'].'</td></tr>';
             echo '</table>';
             echo '</td>';
           }
       echo '</tr>';
       echo '<tr>';
           echo '<th align=left>Tytu³: </th>';
           echo '<td>'.$res['tytul'].'</td>';
       echo '</tr>';
       echo '<tr>';
           echo '<th align=left>Kategoria: </th>';
           echo '<td>'.$res['kategoria'].'</td>';
       echo '</tr>';
       echo '<tr>';
           echo '<th align=left valign=top>Opis: </th>';
           echo '<td>'.$opis.'</td>';
       echo '</tr>';
       echo '<tr>';
           echo '<th align=left>Powiadomienie: </th>';
           echo '<td>';
               if ($res['pow']) {
                  echo 'Tak';
               } else {
                  echo 'Nie';
               }
           echo '</td>';
       echo '</tr>';
       echo '<tr>';
            echo '<th align=left>Opcje</th>';
            $edycja = 'dod_form.php?rok='.$rok.'&amp;mies='.$miesiac.'&amp;dzien='.$dzien.'&amp;id_e='.$id;
            echo '<td>';
            echo '<a href='.$edycja.'>Edytuj</a> ';
            echo '<a href='.$url.'&amp;id_u='.$id.'>Usuñ</a>';
            echo'</td>';
       echo '</tr>';
echo '</table>';
}
//------------------------------------------------------------------------------
?>
