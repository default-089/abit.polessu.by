<?php
require __DIR__.'/../libs/db.php'; 
unset($_SESSION['logged_user']);
header('Location: ../index.php');
?>﻿