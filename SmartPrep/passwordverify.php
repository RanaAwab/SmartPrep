<?php

$pass = "123456";

$newpass = password_hash($pass, PASSWORD_DEFAULT);
echo $newpass;

?>