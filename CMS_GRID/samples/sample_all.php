<?php

session_start();

echo
'
<!DOCTYPE HTML>
<html>
<head>

<title>Sample_1 - simple data list </title>

<link rel="stylesheet" type="text/css" href="../source/lib/grid.css" />

<script src="../source/lib/jquery-3.6.0.min.js"></script>
<script src="../source/lib/grid.js"></script>
<script src="sample_4_list.js"></script>
<script src="sample_5_tree.js"></script>


<style>
body { 
 background-image: url("../pic/textur.png");
 padding: 50px;
 color: brown;
}
.code-editor {
 width: 100%;
 min-height: 100px;
 max-height: 250px;
 color: brown;
 background-color: WhiteSmoke;
 }
</style>


</head>
<body>
';


$path = dirname(__FILE__).'/../source/db/conn.php';
include_once $path; 

$path = dirname(__FILE__).'/../source/lib/grid.php';
include_once $path; 
 
 
$txt = 
'<link rel="stylesheet" type="text/css" href="source/lib/grid.css" />
<script src="source/lib/jquery-3.6.0.min.js"></script>
<script src="source/lib/grid.js"></script>
<script src="source/list/list.js"></script>
<script src="source/tree/tree.js"></script>
<script src="source/lib/file.js"></script>
<?php
 session_start(); 
 include_once "source/db/conn.php";
 include_once "source/lib/grid.php";
?>';
echo 'For use need add code:';
echo '<textarea class=code-editor readonly>' . $txt . '</textarea>';
echo '<br><br><br>';

echo 'Sample 1:';
include 'sample_1.php';
$txt = file_get_contents('sample_1.php');
echo 'Code:';
echo '<textarea class=code-editor readonly>' . $txt . '</textarea>';
echo '<br><br><br>';

echo 'Sample 2:';
include 'sample_2.php';
$txt = file_get_contents('sample_2.php');
echo 'Code:';
echo '<textarea class=code-editor readonly>' . $txt . '</textarea>';
echo '<br><br><br>';


echo 'Sample 3:';
include 'sample_3.php';
$txt = file_get_contents('sample_3.php');
echo 'Code:';
echo '<textarea class=code-editor readonly>' . $txt . '</textarea>';
echo '<br><br><br>';


echo 'Sample 4:';
include 'sample_4.php';
$txt = file_get_contents('sample_4.php');
echo 'Code:';
echo '<textarea class=code-editor readonly>' . $txt . '</textarea>';
echo '<br><br><br>';


echo 'Sample 5:';
include 'sample_5.php';
$txt = file_get_contents('sample_5.php');
echo 'Code:';
echo '<textarea class=code-editor readonly>' . $txt . '</textarea>';
echo '<br><br><br>';

echo '</body>';

?>