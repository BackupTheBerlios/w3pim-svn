<script type="text/javascript">
	function validate_field(field, msg)
	{
		with (field) {
			if (value == null || value == "") {
				alert(msg + ' is empty.');
				return false;
			} else {
				return true;
			}
		}
	}

	function validate_form(thisform)
	{
		with (thisform) {
			if (validate_field(login, 'Username') == false) {
				login.focus();
				return false;
			}
			if (validate_field(pass_main, 'Password') == false) {
				pass_main.focus();
				return false;
			}
			if (validate_field(pass_conf, 'Confirm password') == false) {
				pass_conf.focus();
				return false;
			}
			if (pass_main.value != pass_conf.value) {
				alert('Passwords do not match.');
				return false;
			}
		}
	}
</script>

<form action="?m=register" method="post" onsubmit="return validate_form(this);">
	<div>
		<label for="login">Username:</label><br />
		<input type="text" id="login" name="login" /><br />
		
		<label for="pass_main">Password:</label><br />
		<input type="password" id="pass_main" name="pass_main" /><br />

		<label for="pass_conf">Confirm password:</label><br />
		<input type="password" id="pass_conf" name="pass_conf" /><br />

		<label for="email">E-mail:</label><br />
		<input type="text" id="email" name="email" /><br />
		
		<br />
		<input type="submit" name="submit" value="Register" />
	</div>
</form>