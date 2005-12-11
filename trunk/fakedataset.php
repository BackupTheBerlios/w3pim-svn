<?php
	class fakedataset
	{		
		var $srv = "localhost";
		var $db = "webpim";
		var $login = "marcin";
		var $passwd = "";		
		
		var $conn;
        var $query;
        var $result;
		
		function fakedataset($q, $all=true)
		{
			$this->conn = null;
            $this->query = $q;
			if ($all) {
                $this->open();
                $this->select_db();
			}
            $this->execute();            			
		}
		
		function open()
		{
			$this->conn = mysql_connect($this->srv, $this->login,
            $this->passwd) or die ("CONNECTION ERROR: " . mysql_error());
			return $this->conn;				
		}
		
        function close()
        {
            if (isset($this->result))
                mysql_free_result($this->result);
            if (isset($this->conn))
                mysql_close($this->conn);
        }
		
		function select_db()
		{
			return mysql_select_db($this->db) or die ("DATABASE ERROR: " 
                . mysql_error());
		}
		
		function execute()
		{
			$this->result = mysql_query($this->query) or die ("QUERY ERROR: " . mysql_error());				
		}
		
		function fetch()
		{
			$row = mysql_fetch_array($this->result, MYSQL_ASSOC);
			if ($row == null)
				mysql_free_result($this->result);
			return $row;	
		}	
	};
?>
