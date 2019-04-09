<?php
    /* Get request returns url's content*/

    $url = $_GET['url'];
    echo file_get_contents($url);
?>