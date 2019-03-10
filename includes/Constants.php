<?php

// Database Connection
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cesroads');

// Retrieve Operation
define('RETRIEVE_SUCCESSFUL', '101');
define('DATA_NOT_FOUND', '102');
define('RETRIEVE_UNSUCCESSFUL', '103');

// User Login
define('USER_AUTHENTICATED', 201);
define('USER_NOT_FOUND', 202);
define('USER_PASSWORD_DO_NOT_MATCH', 203);

// Update Password
define('PASSWORD_CHANGED', 301);
define('PASSWORD_DO_NOT_MATCH', 302);
define('PASSWORD_NOT_CHANGED', 303);