<?php
	class Templates
	{		
		function Templates($dir)
		{
			$this->dir = $dir;
		}
		
		function set($tpl, $var, $val = 0)
		{
			if (is_array($var)) {
				foreach(array_keys($var) as $key)
					$this->vars[$tpl][$key] = $var[$key];
			} else {
				$this->vars[$tpl][$var] = $val;
			}							
		}
		
		function read_tpl($tpl) 
		{			
			$fname = realpath($this->dir . '//' . $tpl . '.tpl');
			if (is_file($fname)) {
				$f = fopen($fname, "r");
				$res = fread($f, filesize($fname));
				fclose($f);
			} else 
				$res = '';			
			return $res;
		}
		
		function parse($tpl)
		{
			$res = $this->read_tpl($tpl);
			if (strcmp($res, '') != 0) {
										
				foreach(array_keys($this->vars[$tpl]) as $key) {
					$p = "/\{$key\}/";														
					$res = preg_replace($p, $this->vars[$tpl][$key], $res);				  
				}
			} else
				$res = '';
			return $res;			
		}
	};		
?>
