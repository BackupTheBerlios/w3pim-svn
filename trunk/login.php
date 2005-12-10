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
        print $mode;
        // TODO: zarejestrowanie usera
        break;

    default:
        $tpl = new Templates('templates');

        $page = $tpl->parse('login');
        $tpl->set('main', 'body', $page);        
        print $tpl->parse('main');    
        break;
    }
?>
