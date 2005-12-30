<?
	function __autoload($class_name) 
	{
		require_once $class_name . ".php";
	}

	error_reporting(0);

	$mode = $_GET['m'];
	switch ($mode) {
	case 'enter':
		$login = $_POST['login'];
		$passwd = $_POST['passwd'];

		try {
			$u = new user($login, $passwd);

			header("Location: index.php");
		} catch (Exception $e) {
			header("Location: ?m=new");
		}
		break;

	case 'new':
		$tpl = new Templates('templates');
	
		$body = $tpl->parse('register');
		$tpl->set('main', 'body', $body);
		print $tpl->parse('main');
		break;

	case 'register':
		$l = $_POST['login'];
		$pm = $_POST['pass_main'];
		$pc = $_POST['pass_conf'];
		$e = $_POST['email'];

		try {
			if (strlen($l) < 1)
				throw new Exception('Nie podano identyfikatora użytkownika.');
			if ($pm == '' or $pc == '')
				throw new Exception('Żadne z podanych haseł nie może być puste.');
			if ($pm != $pc)
				throw new Exception('Podane hasła nie są jednakowe.');
			$u = new user($l, $pm, true);
			header("Location: ?");
		} catch (Exception $e) {
			$tpl = new Templates('templates');
			$tpl->set('error', 'message', $e->getMessage());
			print $tpl->parse('error');
			exit;
		}
		break;

	case 'logout':
		session_start();
		unset($_SESSION['user']);
		session_destroy();
		header("Location: index.php");
		break;

	default:
		$tpl = new Templates('templates');

		$page = $tpl->parse('login');
		$tpl->set('main', 'body', $page);
		print $tpl->parse('main');
		break;
	}
?>

