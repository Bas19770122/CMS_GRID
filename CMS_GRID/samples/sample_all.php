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
<script src="sample_4_list.js"></script>
<script src="sample_5_tree.js"></script>

</head>
<body>
';

include_once 'source/lib/grid.php';


include 'sample_1.php';
echo '<br>';

include 'sample_2.php';
echo '<br>';

include 'sample_3.php';
echo '<br>';

include 'sample_4.php';
echo '<br>';

include 'sample_5.php';
echo '<br>';



?>