<?php
include('classes/DB.php');
include('classes/login.php');

if (login::isLoggedIn()) {
        echo 'Logged In';
        echo login::isLoggedIn();
} else {
        echo 'Not logged in';
}
