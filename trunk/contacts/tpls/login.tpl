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
			if (validate_field(user, 'Username') == false) {
				user.focus();
				return false;
			}
			if (validate_field(pass, 'Password') == false) {
				pass.focus();
				return false;
			}
		}
	}
</script>

<form action="?mode=enter" method="post" onsubmit="return validate_form(this);">
	<div>
		<label for="user">Username:</label><br />
		<input type="text" id="user" name="user" /><br />
		<label for="pass">Password:</label><br />
		<input type="password" id="pass" name="pass" /><br />
		<br />
		<input type="submit" value="Enter" />
	</div>
</form>
