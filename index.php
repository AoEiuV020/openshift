<!DOCTYPE html>
<html>
<body>

<form>
IP:<br>
<input type="text" name="ip"><br>
PORT:<br>
<input type="text" name="port"><br>
<input type="radio" name="action" value="start" checked>Start<br>
<input type="radio" name="action" value="stop" checked>Stop<br>
<input type="radio" name="action" value="status" checked>Status<br>
<input type="submit" value="Submit"><br>
</form>
<p>
<?php
if($_GET['action'] == '') {
    $_GET['action'] = 'status';
}
include("api.php");
?>
</p>
</body>
</html>
