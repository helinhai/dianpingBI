<?php
session_start();
session_destroy();
echo "<script language=\"JavaScript\">self.location='index.php'</script>";
?>