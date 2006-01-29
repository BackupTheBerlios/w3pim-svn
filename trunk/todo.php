<?php
     require_once 'globals.php';
	require_once 'user.php';
              try {
              $u = new user();
              } catch (Exception $e) {
                      echo $e->getMessage();
                      exit;
              }






$link = mysql_connect(XHOST, XLOGIN, XPASSWD) or die(mysql_error());
mysql_select_db(XDB) or die(mysql_error()) ;

$user = $u->get_id();
$query = "SELECT uzk_login FROM uzytkownicy WHERE uzk_id={$user}";
$result = mysql_query($query) or die(mysql_error());
$tmp = mysql_fetch_array($result, MYSQL_ASSOC);
$user_name = $tmp['uzk_login'];
//echo "Uzytkownik : {$tmp['uzk_login']}";

if (($HTTP_GET_VARS['akcja'] == 'dodaj') || ($HTTP_GET_VARS['akcja']== 'zmien'))
{

        if (strlen($HTTP_GET_VARS['tytul']) == 0)
           {
               header('Location: todo.php?blad=1');
               exit;
           }
        if (strlen($HTTP_GET_VARS['opis']) == 0)
           {
               header('Location: todo.php?blad=2');
               exit;
           }
       /* foreach($HTTP_GET_VARS as $k => $w)
        {
           echo "{$k} => {$w}<BR>";
        }*/

	$query = "SELECT pri_id FROM priorytety where pri_nazwa='{$HTTP_GET_VARS['priorytet']}'";
	$result = mysql_query($query) or die(mysql_error());
	$tmp = mysql_fetch_array($result, MYSQL_ASSOC);
	$id_priorytetu = $tmp['pri_id'];
	mysql_free_result($result);

	$query = "SELECT kat_id FROM kategorie where kat_nazwa='{$HTTP_GET_VARS['kategoria']}'";
	$result = mysql_query($query) or die(mysql_error());
	$tmp = mysql_fetch_array($result, MYSQL_ASSOC);
	$id_kategorii = $tmp['kat_id'];
	mysql_free_result($result);
	
	if ($HTTP_GET_VARS['akcja'] == 'dodaj')
	{
	    $query = "INSERT INTO zadania
		(zad_tytul,zad_opis,zad_postep,zad_data_zakonczenia,
		zad_pri_id, zad_kat_id, zad_uzk_id) values
		('{$HTTP_GET_VARS['tytul']}','{$HTTP_GET_VARS['opis']}',0,
		'{$HTTP_GET_VARS['rok']}-{$HTTP_GET_VARS['miesiac']}-{$HTTP_GET_VARS['dzien']}',
		{$id_priorytetu},{$id_kategorii},{$user})";
	
	
        }
        elseif ($HTTP_GET_VARS['akcja'] == 'zmien')
        {
             $query = "UPDATE zadania SET
		zad_tytul='{$HTTP_GET_VARS['tytul']}',
                zad_opis='{$HTTP_GET_VARS['opis']}',
                zad_postep='{$HTTP_GET_VARS['postep']}',
		zad_data_zakonczenia=
                '{$HTTP_GET_VARS['rok']}-{$HTTP_GET_VARS['miesiac']}-{$HTTP_GET_VARS['dzien']}',
		zad_pri_id={$id_priorytetu},
                zad_kat_id={$id_kategorii}
                WHERE zad_id={$HTTP_GET_VARS['zad_id']}";
	
	//        echo $query;
        }
        $result = mysql_query($query)or die("<BR>Zapytanie niepoprawne" . mysql_error());;

 	header('Location: todo.php?akcja=lista');
	//echo 'Element zosta³ dodany!';
	//echo '<A HREF="todo.php?akcja=lista">Wyswietl liste TODO</A><BR>';
	//echo '<A HREF="todo.php">Dodaj zdarzenie TODO</A><BR>';
 	//echo '</BODY>';
        //echo '</HTML>';
	
}
elseif ($HTTP_GET_VARS['akcja'] == 'lista' || $HTTP_GET_VARS['akcja'] == 'anuluj')
{	
//	echo '<B>Bedzimy listowac</B><BR>';
	echo '</BODY>';
        echo '</HTML>'; 	
	
	switch ($HTTP_GET_VARS['sort']) 
	{
   		case 'data':
			$ord_by_str = 'zad_data_zakonczenia';
			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data_desc">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
   		case 'tytul':
       			$ord_by_str = 'zad_tytul';
       			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul_desc">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
		case 'postep':
			$ord_by_str = 'zad_postep';
			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep_desc">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
		case 'kat':
			$ord_by_str = 'kat_nazwa';
			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat_desc">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
		case 'pri':
			$ord_by_str = 'pri_nazwa';
			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri_desc">Priorytet</A>';
                        break;
			
		case 'data_desc':
			$ord_by_str = 'zad_data_zakonczenia desc';
              		$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
   		case 'tytul_desc':
       			$ord_by_str = 'zad_tytul desc';
       			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
		case 'postep_desc':
			$ord_by_str = 'zad_postep desc';
			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
		case 'kat_desc':
			$ord_by_str = 'kat_nazwa desc';
			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
		case 'pri_desc':
			$ord_by_str = 'pri_nazwa desc';
			$link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
		default:
			$ord_by_str = 'zad_data_zakonczenia';
		        $link_param_data = '<A HREF="todo.php?akcja=lista&sort=data">Data</A>';
                        $link_param_tytul = '<A HREF="todo.php?akcja=lista&sort=tytul">Tytu³</A>';
                        $link_param_postep = '<A HREF="todo.php?akcja=lista&sort=postep">Postep</A>';
              		$link_param_kat = '<A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A>';
                        $link_param_pri = '<A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A>';
                        break;
	}
	
	$q = 'SELECT max(zad_id) as maksimum from zadania';
	$r = mysql_query($q) or die ('Blad SQLa:' . mysql_error());
	if ($t = mysql_fetch_array($r))
		$max_id = $t['maksimum'];
	$max_id = (int)log10($max_id) + 1;

			
	$query	= "SELECT zad_id,zad_tytul,zad_opis, zad_postep, zad_data_zakonczenia,
			kat_nazwa,pri_nazwa from zadania z, uzytkownicy u, kategorie k, 
			priorytety p where u.uzk_id=z.zad_uzk_id and z.zad_kat_id=k.kat_id
			and z.zad_pri_id=p.pri_id and zad_postep<100 and uzk_id={$user}
			order by {$ord_by_str}";

	$result = mysql_query($query) or die ('Blad SQLa:' . mysql_error());
	$wiersze = mysql_num_rows($result);

	$data = getdate();

	echo '<FORM action="todo.php" method="get">';	
	echo '<TABLE BORDER="0">';


	echo 	"<TR>
		<TH>{$link_param_tytul}</TH>
		<TH>Opis</TH>
		<TH>{$link_param_data}</TH>
		<TH>{$link_param_postep}</TH>
		<TH>{$link_param_kat}</TH>
		<TH>{$link_param_pri}</TH>
		<TH>Edycja</TH>
		<TH>Usuwanie</TH>
		</TR>";
			
	while ($tmp = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		if ($tmp['zad_data_zakonczenia'] < date("Y-m-d 00:00:00"))
				$kolor = 'red';
			else
				$kolor = '';
				
		$kol_tytul = $tmp['zad_tytul'];
		
		$kol_opis = $tmp['zad_opis'];

		$kol_data = substr($tmp['zad_data_zakonczenia'],0,10);


                
		$s = sprintf("%%0%dd",$max_id);
		$numer_zad = sprintf($s, $tmp['zad_id']);

		$kol_postep = "<SELECT NAME=\"id_zad_{$numer_zad}_postep\">";
		for ($i = 0; $i <= 100; $i += 10)
		{
			if ($tmp['zad_postep'] != $i)
				$kol_postep .= "<OPTION>{$i}";	
			else
				$kol_postep .= "<OPTION SELECTED>{$i}";	
		} 	
		$kol_postep .= '</SELECT>';


		$query_kat = 'SELECT kat_nazwa FROM kategorie order by kat_nazwa';
		$result_kat = mysql_query($query_kat) or die ('Blad SQLa:' . mysql_error());
		$kol_kat = "<SELECT NAME=\"id_zad_{$numer_zad}_kat\">";
		while($tmp_kat = mysql_fetch_array($result_kat,MYSQL_ASSOC))
		{
			if ($tmp_kat['kat_nazwa'] != $tmp['kat_nazwa'])
				$kol_kat .= "<OPTION>{$tmp_kat['kat_nazwa']}";	
			else
				$kol_kat .= "<OPTION SELECTED>{$tmp_kat['kat_nazwa']}";	
		}
		$kol_kat .= '</SELECT>';
		
		
		$query_pri = 'SELECT pri_nazwa FROM priorytety order by pri_nazwa';
		$result_pri = mysql_query($query_pri) or die ('Blad SQLa:' . mysql_error());
		$kol_pri = "<SELECT NAME=\"id_zad_{$numer_zad}_pri\">";
		while($tmp_pri = mysql_fetch_array($result_pri,MYSQL_ASSOC))
		{
			if ($tmp_pri['pri_nazwa'] != $tmp['pri_nazwa'])
				$kol_pri .= "<OPTION>{$tmp_pri['pri_nazwa']}";	
			else
				$kol_pri .= "<OPTION SELECTED>{$tmp_pri['pri_nazwa']}";	
		}
		$kol_pri .= '</SELECT>';
		$kol_usun =  "<INPUT  TYPE=\"CHECKBOX\" NAME=\"{$numer_zad}\">";
		//$kol_usun = "<input type=\"submit\" value=\"usuñ\" name=\"akcja\">";
		//$kol_edycja = "<input type=\"submit\" value=\"edycja\" name=\"akcja\">";
                $kol_edycja = "<input type=\"radio\" value=\"{$numer_zad}\" name=\"doedycji\">";
		echo "<TR BGCOLOR=\"{$kolor}\"><TD>{$kol_tytul}</TD>
                     <TD>{$kol_opis}</TD><TD>{$kol_data}</TD>
		     <TD>{$kol_postep}</TD><TD>{$kol_kat}</TD>
                     <TD>{$kol_pri}</TD><TD>{$kol_edycja}</TD><TD>{$kol_usun}</TD> </TR>";
			
	}
		
	echo '</TABLE>';
	echo '<input type="submit" value="update" name="akcja">';
	echo '<input type="submit" value="usun" name="akcja">';
	echo '<input type="submit" value="edycja" name="akcja">';
	
	echo '</FORM>';
	echo "<A href=\"todo.php?akcja=lista\">Lista TODO</A><BR>";
	echo '<a href="kalendarz.php">Kalendarz</a><BR>';
	echo "<A href=\"todo.php\">Dodaj zdarzenie TODO</A><BR>";
	echo '<a href="login.php?m=logout">Logout</a><BR>';
	echo '</BODY>';
        echo '</HTML>';
}
elseif ($HTTP_GET_VARS['akcja'] == 'update')
{
       /* echo '</BODY>';
        echo '</HTML>';

        foreach($HTTP_GET_VARS as $k => $w)
        {
           echo "{$k} => {$w}<BR>";
        }

       	echo '</BODY>';
        echo '</HTML>';*/
        	
	$l_elementow = (int)(count($HTTP_GET_VARS)/3);
	//echo "Liczba elementow tablicy = {$l_elementow}<BR>";
	reset($HTTP_GET_VARS);
	$liczik = 0;
	for ($i = 0; $i < $l_elementow; $i++)
	{
		$postep = each($HTTP_GET_VARS);
		$kategoria = each($HTTP_GET_VARS);
		$priorytet = each($HTTP_GET_VARS);
		sscanf($postep[0], "id_zad_%d_postep", $id);
//		echo "{$id}<BR>";
		$q1 = "SELECT kat_id from kategorie where kat_nazwa='{$kategoria[1]}'";
//		echo $q1;
		$r1 = mysql_query($q1) or die('aaaa' . mysql_error()); 
		$t1 = mysql_fetch_array($r1,MYSQL_ASSOC);	
		$q2 = "SELECT pri_id from priorytety where pri_nazwa='{$priorytet[1]}'";
		$r2 = mysql_query($q2) or die('bbb' . mysql_error()); 
		$t2 = mysql_fetch_array($r2,MYSQL_ASSOC);
		
		$query = "	UPDATE zadania 
				SET zad_postep={$postep[1]}, zad_kat_id={$t1['kat_id']}, zad_pri_id={$t2['pri_id']}
		 		WHERE zad_id={$id}";
//		echo $query;
		$result = mysql_query($query) or die(mysql_error()); 
		$licznik += mysql_affected_rows();
		
		
	}
        header('Location: todo.php?akcja=lista');
	//echo "<A href=\"todo.php?akcja=lista\">Lista TODO</A><BR>";
	//echo "<A href=\"todo.php\">Dodaj zdarzenie TODO</A><BR>";

}
elseif   ($HTTP_GET_VARS['akcja'] == 'usun')
{
       // echo '</BODY>';
       // echo '</HTML>';
       // echo "Nacisnieto usun<BR>";
        foreach($HTTP_GET_VARS as $k => $w)
        {
              //echo "{$k} => {$w}<BR>";
              
              if ($w == 'on')
              {
                 $query = "UPDATE zadania SET zad_postep=100 WHERE zad_id = {$k}";
		 mysql_query($query) or die('aaaa' . mysql_error());

              }


        }
       header('Location: todo.php?akcja=lista');


}
elseif ($HTTP_GET_VARS['akcja'] == 'edycja')
{
        if (array_key_exists('doedycji', $HTTP_GET_VARS))
        {
           echo '</BODY>';
           echo '</HTML>';


           $query = "SELECT zad_id, zad_tytul,zad_opis,zad_postep,zad_data_zakonczenia,
                     kat_nazwa, pri_nazwa
                     FROM zadania z, uzytkownicy u, priorytety p, kategorie k
                     WHERE z.zad_id={$HTTP_GET_VARS['doedycji']} AND
                     u.uzk_id={$user} AND z.zad_kat_id=k.kat_id AND
                     z.zad_pri_id=p.pri_id";

	   $result = mysql_query($query) or die(mysql_error());
	   $wynik = mysql_fetch_array($result, MYSQL_ASSOC);
	   list($rok, $miesiac, $dzien) = sscanf($wynik['zad_data_zakonczenia'], "%d-%d-%d");


           $sel_dzien = "<SELECT NAME=\"dzien\" >";
	   
	   for ($a = 1; $a <= 31; $a++)
	   {
		if ($a == $dzien)
			$tmp = "<OPTION SELECTED> {$a}";
		else
			$tmp = "<OPTION> {$a}";
		$sel_dzien .= $tmp;
	   }

	$sel_dzien =$sel_dzien .'</SELECT>';
	$sel_miesiac = "<SELECT NAME=\"miesiac\" >";

	for ($a = 1; $a <= 12; $a++)
	{
		if($a == $miesiac)
			$tmp = "<OPTION SELECTED> {$a}";
		else
			$tmp = "<OPTION> {$a}";
		$sel_miesiac .= $tmp;
	}

	$sel_miesiac = $sel_miesiac .'</SELECT>';
	$pole_rok = "<INPUT TYPE=\"TEXT\" NAME=\"rok\" MAXLENGTH=\"4\" VALUE={$rok}>";
	
	echo '<FORM action="todo.php" method="get">';

	echo '<TABLE BORDER="1">';

	echo "<TR><TD WIDTH=\"200\"> Data </TD><TD>{$sel_dzien} {$sel_miesiac} {$pole_rok}</TD></TR>";

	$pole_tytul = "<INPUT TYPE=\"TEXT\" NAME=\"tytul\" VALUE=\"{$wynik['zad_tytul']}\" MAXLENGTH=\"255\">";
	echo "<TR><TD WIDTH=\"200\"> Tytu³ </TD><TD>{$pole_tytul}</TD></TR>";

	$pole_opisu = "<TEXTAREA NAME=\"opis\" ROWS=\"4\" COLS=\"50\" >{$wynik['zad_opis']}</TEXTAREA>";
	echo "<TR><TD WIDTH=\"200\"> Opis </TD><TD>{$pole_opisu}</TD></TR>";



	$query = 'SELECT pri_nazwa FROM priorytety ORDER BY pri_id';
	$result = mysql_query($query) or die(mysql_error());
	echo '<TR><TD>Priorytet, Kategoria, Postep</TD><TD>';

	$sel_priorytet = "Priorytet <SELECT NAME=\"priorytet\">";
	while ($tmp1 = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		foreach($tmp1 as $wartosc)
		{
		        if ($wartosc == $wynik['pri_nazwa'])
                             $tmp = "<OPTION SELECTED> {$wartosc}";
		        else    			
                             $tmp = "<OPTION> {$wartosc}";
			$sel_priorytet = $sel_priorytet . $tmp;
		}
	
	}
	echo "{$sel_priorytet}" . '</SELECT>';
	
	
        $query = 'SELECT kat_nazwa FROM kategorie ORDER BY kat_id';
	$result = mysql_query($query) or die(mysql_error());

	$sel_kat = " Kategoria <SELECT NAME=\"kategoria\">";
	while ($tmp1 = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		foreach($tmp1 as $wartosc)
		{
		        if ($wartosc == $wynik['kat_nazwa'])
			    $tmp = "<OPTION SELECTED> {$wartosc}";
                        else
                            $tmp = "<OPTION> {$wartosc}";
			$sel_kat = $sel_kat . $tmp;
		}
	
	}
	    	
	echo "{$sel_kat}" . '</SELECT>';
	
		
	$sel_postep = "Postep <SELECT NAME=\"postep\">";
	
	for($a = 0; $a <= 100; $a += 10)
	{
	        if ($a == $wynik['zad_postep'])
                      $tmp = "<OPTION SELECTED> {$a}";
	        else    			
                      $tmp = "<OPTION> {$a}";
	              $sel_postep = $sel_postep . $tmp;
	}
	
	
	echo "{$sel_postep}" . '</SELECT>';
	
	echo '</TD></TR>';
	echo '<TR><TD> </TD>
             <TD><input type="submit" value="zmien" name="akcja">
                 <input type="submit" value="anuluj" name="akcja">
             </TD></TR>';



	echo '</TABLE>';
	echo "<input type=hidden value=\"{$HTTP_GET_VARS['doedycji']}\" name=\"zad_id\">";
	echo '</FORM>';
        echo "<A href=\"todo.php?akcja=lista\">Lista TODO</A><BR>";
	echo '<a href="kalendarz.php">Kalendarz</a><BR>';
	echo "<A href=\"todo.php\">Dodaj zdarzenie TODO</A><BR>";
	echo '<a href="login.php?m=logout">Logout</a><BR>';
           echo '</BODY>';
           echo '</HTML>';
           
        }
        else
        {
          header('Location: todo.php?akcja=lista');
        }

        
        
        
}

else
{
	echo '</BODY>';
        echo '</HTML>';
        	
	if ($HTTP_GET_VARS['blad'] == 1)
	   {
                 echo "Pole zawieraj±ce tytu³ nie mo¿e byæ puste!<BR>";
	         exit;
	   }
	if ($HTTP_GET_VARS['blad'] == 2)
	   {
                 echo "Pole zawieraj±ce opis nie mo¿e byæ puste!<BR>";
                 exit;
           }
        $data = getdate();
	$sel_dzien = "<SELECT NAME=\"dzien\" >";
	for ($a = 1; $a <= 31; $a++)
	{
		if ($a == $data['mday'])
			$tmp = "<OPTION SELECTED> {$a}";
		else
			$tmp = "<OPTION> {$a}";
		$sel_dzien .= $tmp;
	}

	$sel_dzien =$sel_dzien .'</SELECT>';
	$sel_miesiac = "<SELECT NAME=\"miesiac\" >";

	for ($a = 1; $a <= 12; $a++)
	{
		if($a == $data['mon'])
			$tmp = "<OPTION SELECTED> {$a}";
		else 
			$tmp = "<OPTION> {$a}";
		$sel_miesiac .= $tmp;
	}

	$sel_miesiac =$sel_miesiac .'</SELECT>';
	$pole_rok = "<INPUT TYPE=\"TEXT\" NAME=\"rok\" MAXLENGTH=\"4\" VALUE={$data['year']}>";
	
	echo '<FORM action="todo.php" method="get">';

	echo '<TABLE BORDER="1">';

	echo "<TR><TD WIDTH=\"200\"> Data </TD><TD>{$sel_dzien} {$sel_miesiac} {$pole_rok}</TD></TR>";

	$pole_tytul = "<INPUT TYPE=\"TEXT\" NAME=\"tytul\" MAXLENGTH=\"255\">";
	echo "<TR><TD WIDTH=\"200\"> Tytu³ </TD><TD>{$pole_tytul}</TD></TR>";

	$pole_opisu = "<TEXTAREA NAME=\"opis\" ROWS=\"4\" COLS=\"50\"></TEXTAREA>";
	echo "<TR><TD WIDTH=\"200\"> Opis </TD><TD>{$pole_opisu}</TD></TR>";



	$query = 'SELECT pri_nazwa FROM priorytety ORDER BY pri_id';
	$result = mysql_query($query) or die(mysql_error());
	echo '<TR><TD>Priorytet i Kategoria</TD><TD>';

	$sel_priorytet = "Priorytet <SELECT NAME=\"priorytet\">";
	while ($tmp1 = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		foreach($tmp1 as $wartosc)
		{
			$tmp = "<OPTION> {$wartosc}";
			$sel_priorytet = $sel_priorytet . $tmp;
		}
	
	}
	echo "{$sel_priorytet}" . '</SELECT>';

	$query = 'SELECT kat_nazwa FROM kategorie ORDER BY kat_id';
	$result = mysql_query($query) or die(mysql_error());

	$sel_kat = " Kategoria <SELECT NAME=\"kategoria\">";
	while ($tmp1 = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		foreach($tmp1 as $wartosc)
		{
			$tmp = "<OPTION> {$wartosc}";
			$sel_kat = $sel_kat . $tmp;
		}
	
	}
	echo "{$sel_kat}" . '</SELECT>';
	echo '</TD></TR>';
	echo '<TR><TD> </TD><TD><input type="submit" value="dodaj" name="akcja"></TD></TR>';



	echo '</TABLE>';
	
	echo '</FORM>';
	echo "<A href=\"todo.php?akcja=lista\">Lista TODO</A><BR>";
	echo '<a href="kalendarz.php">Kalendarz</a><BR>';
	echo "<A href=\"todo.php\">Dodaj zdarzenie TODO</A><BR>";
	echo '<a href="login.php?m=logout">Logout</a><BR>';
    	echo '</BODY>';
        echo '</HTML>';

}



?>

