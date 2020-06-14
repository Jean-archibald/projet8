<?php
$link = mysqli_connect("localhost", "root", "root");
printf("Version du serveur : %s\n", mysqli_get_server_info($link));
mysqli_close($link);
?>