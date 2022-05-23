<?php
session_start();
unset($_SESSION["s_Usuario"]);
session_destroy();
header("Location: ../../index.php");
?>