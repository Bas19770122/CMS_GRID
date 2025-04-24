<?php

session_start();

echo
'
<!DOCTYPE HTML>
<html>
<head>

<title>samples of data grid </title>

<meta name="keywords" content="grid,data,php" />  

<link rel="stylesheet" type="text/css" href="../source/lib/grid.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="../source/lib/grid.js"></script>
<script src="sample_4_list.js"></script>
<script src="sample_5_tree.js"></script>
<script src="sample_6_list.js"></script>
<script src="sample_6_tree.js"></script>
<script src="../source/lib/file.js"></script>


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



echo '<h1>Examples of data tables (php data grid)</h1>';

$txt = 
'<link rel="stylesheet" type="text/css" href="source/lib/grid.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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


echo 'Sample 6:';
include 'sample_6.php';
$txt = file_get_contents('sample_6.php');
echo 'Code:';
echo '<textarea class=code-editor readonly>' . $txt . '</textarea>';
echo '<br><br><br>';

echo '<div style = "position: sticky;bottom: 0;height-min:50px;padding:20px;background-color: #eacbcb;
  margin-bottom: -70px;
  margin-left: -60px;
  margin-right: -70px;">Обратная связь: '
. '<script>'
        . 'document.write("<a href=mailto:spam")+'
        . 'document.write(String.fromCharCode(64))+document.write("autotrade.ru>письмо</a>")'
        . '</script> </div>';

echo '

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(83794330, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/83794330" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->


</body>
';

?>