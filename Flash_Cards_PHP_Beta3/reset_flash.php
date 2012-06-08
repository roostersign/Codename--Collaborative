<?php
//load the DB functions...
require_once './functions/flat.php';
clear_file("./data/".$_REQUEST['table'].".db");
?>
Tables reset, <a href="flash.php?table=<?php echo $_REQUEST['table'] ; ?>">Return to program</a>.
