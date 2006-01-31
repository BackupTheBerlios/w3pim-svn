<script type="text/javascript">
	function validate_field(field, msg)
	{
		with (field) {
			if (value == null || value == "") {
				alert('Pole [ ' + msg + ' ] jest puste.');
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
			if (validate_field(pass_main, 'Has³o') == false) {
				pass_main.focus();
				return false;
			}
			if (validate_field(pass_conf, 'Potwierdzenie has³a') == false) {
				pass_conf.focus();
				return false;
			}
			if (pass_main.value != pass_conf.value) {
				alert('Has³a nie sa jednakowe.');
				return false;
			}
		}
	}
</script>

<form action="?m=register" method="post" onsubmit="return validate_form(this);">
	<div>
		<label for="login">Identyfikator u¿ytkownika:</label><br />
		<input type="text" id="login" name="login" /><br />
		
		<label for="pass_main">Has³o:</label><br />
		<input type="password" id="pass_main" name="pass_main" /><br />

		<label for="pass_conf">Potwierdzenie has³a:</label><br />
		<input type="password" id="pass_conf" name="pass_conf" /><br />

		<label for="email">E-mail:</label><br />
		<input type="text" id="email" name="email" /><br />
		
		<br />
		<input type="submit" name="submit" value="Register" />
	</div>
</form>
