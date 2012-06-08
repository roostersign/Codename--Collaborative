<?php
//load the DB functions...
require_once './functions/flat.php';
clear_file("./data/settings.db");
$data = $_POST['x_num'] . "|" . $_POST['y_num'] . "|" . $_POST['seconds'] . "|1";
append_file("./data/settings.db", $data); 
?>
Tables updated, <a href="flash.php?table=<?php echo $_REQUEST['table'] ; ?>">Return to program</a>.
