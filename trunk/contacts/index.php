<?php
	require_once 'libgmailer.php';
	require_once '../Templates.php';
	
	$gm = new GMailer();
	if (!$gm->created)
		die("GMailer not created");
	$gm->getCookieFromBrowser();
	$mode = $_GET['mode'];
	if ((strlen($mode) != 0) and ($mode != 'enter') and (!$gm->isConnected()))
		header("Location: ?");
	
	switch ($mode) {
	case 'new':
		$tpl = new Templates('tpls');
		$tpl->set('contact_form', array(
			'name' => "", 
			'email' => "", 
			'id' => -1));
		$tpl->set('main', 'content', $tpl->parse('contact_form'));
		print $tpl->parse('main');
		break;

	case 'edit':
		$id = $_GET['id'];
		if (!$gm->fetchBox(GM_CONTACT, 'detail', $id))
			die($gm->lastActionStatus());
		$sps = $gm->getSnapshot(GM_CONTACT);
		$tpl = new Templates('tpls');
		foreach ($sps->contacts as $c) {
			$tpl->set('contact_form', array(
				'name' => $c['name'], 
				'email' => $c['email'], 
				'id' => $id));
			$tpl->set('main', 'content', $tpl->parse('contact_form'));
		}
		print $tpl->parse('main');
		break;

	case 'del':
		$id = $_GET['id'];
		if (!$gm->deleteContact($id))
			die($gm->lastActionStatus());
		header("Location: ?mode=list");
		break;

	case 'supply':
		// TODO notes & details
		if (!$gm->editContact($_POST['id'], $_POST['name'], $_POST['email'], ""))
			die($gm->lastActionStatus());
		header("Location: ?mode=list");
		break;

	case 'email':
		$email = $_GET['email'];
		if (!isset($email))
			$email = "";
		$tpl = new Templates('tpls');
		$tpl->set('email_form', 'email', $email);
		$tpl->set('main', 'content', $tpl->parse('email_form'));
		print $tpl->parse('main');
		break;
		
	case 'send':
		$email = $_POST['email'];
		$subj = $_POST['subj'];
		$body = $_POST['content'];

		if (!$gm->send($email, $subj, $body))
			die($gm->lastActionStatus());
		header("Location: ?mode=list");
		break;

	case 'list':
		$tpl = new Templates('tpls');
		$gm->fetchBox(GM_CONTACT, "all", 0);
		$snapshot = $gm->getSnapshot(GM_CONTACT);
		$cs = "";
		foreach ($snapshot->contacts as $c) {
			$tpl->set('contact', array(
				'id' => $c['id'], 
				'name' => $c['name'],
				'email' => $c['email']));
			$cs = $cs.$tpl->parse('contact');
		}
		$tpl->set('contacts', 'list', $cs);
		$tpl->set('main', 'content', $tpl->parse('contacts'));
		print $tpl->parse('main');
		break;

	case 'enter':
		$u = $_POST['user'];
		$p = $_POST['pass'];		
		$gm->setLoginInfo($u, $p, 1.0);
		if ($gm->connect()) {
			$gm->saveCookieToBrowser();
	    		header("Location: ?mode=list");
		} else {
			die ("fuck");
			header("Location: ?");
		}
		break;

	case 'logout':
		$gm->removeCookieFromBrowser();
		header("Location: ?");
		break;

	default:
		$tpl = new Templates('tpls');
		$tpl->set('main', 'content', $tpl->parse('login'));
		print $tpl->parse('main');
		break;
	}
?>
