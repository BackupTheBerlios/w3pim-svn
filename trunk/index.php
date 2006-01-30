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
	
	$tpl = new Templates('templates');
	$tpl->set('home', 'user', $u->get_login());
	$tpl->set('main', 'body', $tpl->parse('home'));
	print $tpl->parse('main');
?>
