<?php
setcookie('c_id', '', time() - 1800);
setcookie('c_name', '', time() - 1800);

header('Location: ./index.php');