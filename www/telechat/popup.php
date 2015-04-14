<?php

session_start();
if (!isset($_SESSION['idTelechat'])){
 $insertGoTo = "telechat.html";
 header(sprintf("Location: %s", $insertGoTo));
}

 $desde    = (isset($_GET['desde']))    ? $_GET['desde']    : strftime("%Y-%m-%d %H:%M:%S",time() - 60 * 60); 
 $hastaStr = (isset($_GET['hasta']))    ? $_GET['hasta']    : strftime("%Y-%m-%d %H:%M:%S",time()); 
 $hasta    = (isset($_GET['hasta']))    ? $_GET['hasta']    : strftime("%Y-%m-%d %H:%M:%S",time());
 $texto    = (isset($_GET['texto']))    ? $_GET['texto']    : ""; 
 $mensaje  = (isset($_GET['mensaje']))  ? $_GET['mensaje']  : ""; 
 $mbcolor  = (isset($_GET['mbcolor']))  ? $_GET['mbcolor']  : $color;
 $mfcolor  = (isset($_GET['mfcolor']))  ? $_GET['mfcolor']  : $text;
 $mfont    = (isset($_GET['msg_font'])) ? $_GET['msg_font'] : "Arial";
 $msize    = (isset($_GET['msg_size'])) ? $_GET['msg_size'] : "30";
 $tbcolor  = (isset($_GET['tbcolor']))  ? $_GET['tbcolor']  : $color;
 $tfcolor  = (isset($_GET['tfcolor']))  ? $_GET['tfcolor']  : $text;
 $tfont    = (isset($_GET['tkr_font'])) ? $_GET['tkr_font'] : "Arial";
 $tsize    = (isset($_GET['tkr_size'])) ? $_GET['tkr_size'] : "40";
 $popup    = (isset($_GET['popup']))    ? $_GET['popup']    : 0;

 $mbcolor  = str_replace("#", "", $mbcolor );
 $mfcolor  = str_replace("#", "", $mfcolor );
 $tbcolor  = str_replace("#", "", $tbcolor );
 $tfcolor  = str_replace("#", "", $tfcolor );

 $fullURL  = "?desde=$desde&hasta=$hastaStr&mensaje=$mensaje&mbcolor=$mbcolor&mfcolor=$mfcolor&msg_font=$mfont&msg_size=$msize&tbcolor=$tbcolor&tfcolor=$tfcolor&tkr_font=$tfont&tkr_size=$tsize&popup=$popup";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Telechats</title>
 </head>
 <frameset rows="0,*,120" framespacing="0" frameborder="no" border="1">
  <frame src="popup.control.php<?php echo $fullURL ?>" name="controlFrame" id="controlFrame" title="controlFrame" />
  <frame src="blank.php" name="tickerFrame" id="tickerFrame" title="tickerFrame" />
  <frame src="main.php" name="tickerFrame" id="tickerFrame" title="tickerFrame" scrolling="no" />
 </frameset>
 <noframes>
  <body>
  </body>
 </noframes>
</html>
