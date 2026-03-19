<?php
session_start();
session_abort();
header("Location: /dashboard/login.php");
exit;
