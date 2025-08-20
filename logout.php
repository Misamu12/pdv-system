<?php
require_once 'includes/auth.php';

$auth = new Auth(null); // La déconnexion ne nécessite pas la base
$auth->logout();
?>
