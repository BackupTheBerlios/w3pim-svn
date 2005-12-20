<?php
     require_once 'globals.php';
	require_once 'user.php';
              try {
              $u = new user();
              } catch (Exception $e) {
                      echo $e->getMessage();
                      exit;
              }
?>

</BODY>
</HTML> 
      

<?php


$user = $u->get_id();
echo "Uzytkownik ma id: {$user}";


$link = mysql_connect($xhost, $xlogin, $xpasswd) or die(mysql_error());
mysql_select_db($xdb) or die(mysql_error()) ;

if ($HTTP_GET_VARS['akcja'] == 'dodaj')
{
	
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
	
	$query = "INSERT INTO zadania 
		(zad_tytul,zad_opis,zad_postep,zad_data_zakonczenia, 
		zad_pri_id, zad_kat_id, zad_uzk_id) values 
		('{$HTTP_GET_VARS['tytul']}','{$HTTP_GET_VARS['opis']}',0,
		'{$HTTP_GET_VARS['rok']}-{$HTTP_GET_VARS['miesiac']}-{$HTTP_GET_VARS['dzien']}',
		{$id_priorytetu},{$id_kategorii},{$user})";
	$result = mysql_query($query);
	echo 'Element zosta³ dodany!';
	echo '<A HREF="todo.php?akcja=lista">Wyswietl liste TODO</A><BR>';
	echo '<A HREF="todo.php">Dodaj zdarzenie TODO</A><BR>';
	
	
}
elseif ($HTTP_GET_VARS['akcja'] == 'lista')
{	
//	echo '<B>Bedzimy listowac</B><BR>';
	
	switch ($HTTP_GET_VARS['sort']) 
	{
   		case 'data':
			$ord_by_str = 'zad_data_zakonczenia';
              		break;
   		case 'tytul':
       			$ord_by_str = 'zad_tytul';
			break;
		case 'postep':
			$ord_by_str = 'zad_postep';
			break;
		case 'kat':
			$ord_by_str = 'kat_nazwa';
			break;
		case 'pri':
			$ord_by_str = 'pri_nazwa';
			break;
		default:
			$ord_by_str = 'zad_data_zakonczenia';
              		break;
	}
	
	$q = 'SELECT max(zad_id) as maksimum from zadania';
	$r = mysql_query($q) or die ('Blad SQLa:' . mysql_error());
	if ($t = mysql_fetch_array($r))
		$max_id = $t['maksimum'];
	$max_id = (int)log10($max_id) + 1;
//	echo "Max_id wynosi: {$max_id}<BR>";
			
	$query	= "SELECT zad_id,zad_tytul,zad_opis, zad_postep, zad_data_zakonczenia,
			kat_nazwa,pri_nazwa from zadania z, uzytkownicy u, kategorie k, 
			priorytety p where u.uzk_id=z.zad_uzk_id and z.zad_kat_id=k.kat_id
			and z.zad_pri_id=p.pri_id and zad_postep<100 and uzk_id={$user}
			order by {$ord_by_str}";

	$result = mysql_query($query) or die ('Blad SQLa:' . mysql_error());
	$wiersze = mysql_num_rows($result);
//	echo "{$wiersze}<BR>";
	$data = getdate();

	echo '<FORM action="todo.php" method="get">';	
	echo '<TABLE BORDER="0">';
//	echo '<TABLE >';
	echo 	'<TR>
		<TH><A HREF="todo.php?akcja=lista&sort=tytul">Tytul</A></TH>
		<TH>Opis</TH>
		<TH><A HREF="todo.php?akcja=lista&sort=data">Data zakonczenia</A></TH>
		<TH><A HREF="todo.php?akcja=lista&sort=postep">Postep</A></TH>
		<TH><A HREF="todo.php?akcja=lista&sort=kat">Kategoria</A></TH>
		<TH><A HREF="todo.php?akcja=lista&sort=pri">Priorytet</A></TH>
		</TR>';
			
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
		

		echo "<TR BGCOLOR=\"{$kolor}\"><TD>{$kol_tytul}</TD><TD>{$kol_opis}</TD><TD>{$kol_data}</TD>
		<TD>{$kol_postep}</TD><TD>{$kol_kat}</TD><TD>{$kol_pri}</TD> </TR>";
			
	}
		
	echo '</TABLE>';
	echo '<input type="submit" value="update" name="akcja">';

	echo '</FORM>';
	echo "<A href=\"todo.php?akcja=lista\">Lista TODO</A><BR>";
	echo "<A href=\"todo.php\">Dodaj zdarzenie TODO</A><BR>";
}
elseif ($HTTP_GET_VARS['akcja'] == 'update')
{

	echo '<B>Update\'ujemy liste</B><BR>';
	
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
	echo "<A href=\"todo.php?akcja=lista\">Lista TODO</A><BR>";
	echo "<A href=\"todo.php\">Dodaj zdarzenie TODO</A><BR>";

}
else
{
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
	echo "<A href=\"todo.php\">Dodaj zdarzenie TODO</A><BR>";
	

}



?>
</BODY>
</HTML>
