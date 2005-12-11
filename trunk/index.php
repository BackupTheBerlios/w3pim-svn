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
       
    $m = 'Hello stranger calling himself <i>' . $u->get_login() . '</i>';
    $tpl = new Templates('templates');
    $tpl->set('main', 'body', $m);
    print $tpl->parse('main');         
?>
