
<form action="?mode=supply" method="post">
	<div>
		<label for="name">Name:</label><br />
		<input type="text" id="name" name="name" value="{name}" /><br />
		<label for="email">E-mail:</label><br />
		<input type="text" name="email" value="{email}" /><br />
		<br />
		<input type="submit" value="Apply" />
		<!-- hidden -->
		<input type="hidden" name="id" value="{id}" />
	</div>
</form>

