<?php
header("content-type:image/jpeg");
echo file_get_contents($_GET['imgurl']);
?>