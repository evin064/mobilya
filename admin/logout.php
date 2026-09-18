<?php
require_once __DIR__ . '/includes/auth.php';
logoutAdmin();
redirect('login.php');
