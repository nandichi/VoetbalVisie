<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/Auth.php';

session_start();

$auth = new Auth();
$auth->logout();

header('Location: login.php');
exit; 