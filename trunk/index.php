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
       
    print 'Hello stranger calling himself <i>' . $u->get_login() . '</i>'; 
?>
