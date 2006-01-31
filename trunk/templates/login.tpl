<script type="text/javascript">
	function validate_field(field, msg)
	{
		with (field) {
			if (value == null || value == "") {
				alert('Pole [ ' + msg ' ] jest puste.');
				return false;
			} else {
				return true;
			}
		}
	}

	function validate_form(thisform)
	{
		with (thisform) {
			if (validate_field(login, 'Identyfikator u¿ytkownika') == false) {
				login.focus();
				return false;
			}
			if (validate_field(passwd, 'Has³o') == false) {
				passwd.focus();
				return false;
			}
		}
	}
</script>

<form action="?m=enter" onsubmit="return validate_form(this);" method="post">
	<div>	
		<label for="login">Identyfikator u¿ytkownika:</label><br />
		<input type="text" id = "login" name="login" /><br />
		<label for="passwd">Has³o:</label></br />
		<input type="password" id="passwd" name="passwd" /><br />
		<br />
		<input type="submit" name="submit" value="Enter" />
	</div>
</form>
<p><a href="?m=new">Zarejestruj</a></p>
