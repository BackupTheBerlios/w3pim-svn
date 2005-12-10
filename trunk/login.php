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
        print $mode;
        // TODO: formularz rejestracji
        break;
    
    case 'register':
        print $mode;
        // TODO: zarejestrowanie usera
        break;

    default:
        $tpl = new Templates('templates');

        $page = $tpl->parse('login');
        print $page;
    
        break;
    }
?>
