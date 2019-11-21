<?php
session_start();
//--- Creating CSRF token for the security reason of the ajax requests.
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // it requires PHP 7.0+

    } catch (Exception $e) {

        die('Failed to generate the CSRF token!');
    }
}

require __DIR__ . '/vendor/autoload.php';

require_once('Routes.php');


