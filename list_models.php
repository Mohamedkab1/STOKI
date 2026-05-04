<?php
$key = 'AIzaSyCioDrGKYU98My_djQOpFRhIVoiZhQv0CQ';
$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$key}";

$res = file_get_contents($url);
echo $res;
