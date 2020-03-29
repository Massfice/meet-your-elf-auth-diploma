<form method="{$method}" action="{$endpoint}" enctype="{$type}">
Username: <input type="text" name="{$username}"> Password: <input type="password" name="{$password}">
<input type="submit" value="Zaloguj się">
<input type="hidden" name="{$redirect}" value="{$redirect_value}">
<input type="hidden" name="{$sid}" value="{$sid_value}">
</form>