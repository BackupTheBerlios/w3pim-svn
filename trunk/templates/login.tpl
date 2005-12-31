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
			if (validate_field(passwd, 'Password') == false) {
				passwd.focus();
				return false;
			}
		}
	}
</script>

<form action="?m=enter" onsubmit="return validate_form(this);" method="post">
	<div>	
		<label for="login">Username:</label><br />
		<input type="text" id = "login" name="login" /><br />
		<label for="passwd">Password:</label></br />
		<input type="password" id="passwd" name="passwd" /><br />
		<br />
		<input type="submit" name="submit" value="Enter" />
	</div>
</form>
<p><a href="?m=new">Register</a></p>
