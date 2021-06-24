<?php
setcookie('a_id', '', time() - 1800, '/');
setcookie('a_name', '', time() - 1800, '/');

header('Location: ./index.php');