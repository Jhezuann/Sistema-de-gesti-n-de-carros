<?php
session_start();
session_unset();
session_destroy();
header("Location: ../paginas/index.php");
exit();
?>
