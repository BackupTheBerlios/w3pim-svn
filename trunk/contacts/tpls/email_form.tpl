
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
			if (validate_field(subj 'Title') == false) {
				subj.focus();
				return false;
			}
			if (validate_field(email, 'E-mail') == false) {
				email.focus();
				return false;
			}

		}
	}
</script>

<form action="?mode=send" method="post" onsubmit="return validate_form(this);">
	<label for="email">E-mail:</label><br />
	<input type="text" id="email" name="email" value="{email}" /><br />
	
	<label for="subj">Title:</label><br />
	<input type="text" id="subj" name="subj" /><br />
	
	<label for="content">Content:</label><br />
	<textarea id="content" name="content" cols="60" rows="10"></textarea><br />
	<br />
	<input type="submit" value="Send" />
</form>
