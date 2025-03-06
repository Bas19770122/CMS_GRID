<?php

session_start();

echo
'
<!DOCTYPE HTML>
<html>
<head>

<title>Sample_1 - simple data list </title>

<link rel="stylesheet" type="text/css" href="source/lib/grid.css" />

<script src="source/lib/jquery-3.6.0.min.js"></script>
<script src="source/lib/grid.js"></script>

</head>
<body>
';

include_once 'source/lib/grid.php';


include 'sample_1.php';

include 'sample_2.php';

include 'sample_3.php';


?>