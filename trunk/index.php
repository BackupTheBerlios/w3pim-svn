<?
	function __autoload($class_name)
	{
		require_once $class_name . ".php";
	}
	
	error_reporting(0);

	$u = null;
	try {
		$u = new user();
	} catch (Exception $e) {
		header("Location: login.php");
	}
	
	$m = 'Hello <i>' . $u->get_login() . '</i>';
	$m = $m . '<p><a href="todo.php">ToDo</a></p>';
	$m = $m . '<p><a href="kalendarz.php">Kalendarz</a></p>';
	$tpl = new Templates('templates');
	$tpl->set('main', 'body', $m);
	print $tpl->parse('main');
?>
