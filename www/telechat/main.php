<?php
// Parametros
$color = "#000000";
$text  = "#FFFFFF";

$first_run = "LISTADO INICIAL";
$mensaje = " ";

?>
<html>
 <body bgcolor="<?php echo $color ?>" text="<?php echo $text ?>">
  <input id="TICKER2"    type="hidden" value="<?php echo $first_run; ?>" />
  <input id="MENSAJE2"   type="hidden" value="<?php echo $first_run; ?>" />
  <input id="MSG_BCOLOR" type="hidden" value="#000000" />
  <input id="MSG_FCOLOR" type="hidden" value="#FFFFFF" />
  <input id="MSG_FONT"   type="hidden" value="Arial"   />
  <input id="MSG_SIZE"   type="hidden" value="30"      />
  <input id="TKR_BCOLOR" type="hidden" value="#000000" />
  <input id="TKR_FCOLOR" type="hidden" value="#FFFFFF" />
  <input id="TKR_FONT"   type="hidden" value="Arial"   />
  <input id="TKR_SIZE"   type="hidden" value="40"      />
  <?php if ( $mensaje <> "" ){ ?>
   <div id='MENSAJE' style="overflow: hidden; width: 1000px; background-color: #000000; font-family: Arial; font-size: 30px; color:#FFFFFF"><?php echo $mensaje ?></div>
  <?php } ?>
  <div id="TICKER" style="overflow: hidden; width: 1000px; background-color: #000000"><?php echo $first_run; ?></div><p></p><p></p>
  <script language="javascript1.2" type="text/javascript">
   TICKER_CONTENT = document.getElementById("TICKER").innerHTML;
  <?php if ($mensaje <> ""){ ?>
   MENSAJE_CONTENT = document.getElementById("MENSAJE").innerHTML;
  <?php } ?>

  TICKER_RIGHTTOLEFT = false;
  TICKER_SPEED = 5;
  TICKER_STYLE = "font-family:Arial; font-size: 40px; color:#FFFFFF";
  MENSAJE_STYLE = "font-family:Arial; font-size: 30px; color:#FFFFFF";
  TICKER_PAUSED = false;

  ticker_start();

  function ticker_start() {
   var tickerSupported = false;
   TICKER_WIDTH = document.getElementById("TICKER").style.width;
   var img = "<img src=/images/spacer.gif width="+TICKER_WIDTH+" height=0>";

   // Firefox
   if (navigator.userAgent.indexOf("Firefox")!=-1 || navigator.userAgent.indexOf("Safari")!=-1) {
    document.getElementById("TICKER").innerHTML = "<TABLE  cellspacing='0' cellpadding='0' width='100%'><TR><TD nowrap='nowrap'>"+img+"<SPAN style='"+TICKER_STYLE+"' ID='TICKER_BODY' width='100%'>&nbsp;</SPAN>"+img+"</TD></TR></TABLE>";
    <?php if ($mensaje <> ""){ ?>
     document.getElementById("MENSAJE").innerHTML = "<TABLE  cellspacing='0' cellpadding='0' width='100%'><TR><TD nowrap='nowrap'>" + img + "<SPAN style='"+MENSAJE_STYLE + "' ID='MENSAJE_BODY' width='100%'>&nbsp;</SPAN>"+img+"</TD></TR></TABLE>";
    <?php } ?>
    tickerSupported = true;		
   }

   // IE
   if (navigator.userAgent.indexOf("MSIE")!=-1 && navigator.userAgent.indexOf("Opera")==-1) {
    document.getElementById("TICKER").innerHTML = "<DIV nowrap='nowrap' style='width:100%;'>"+img+"<SPAN style='"+TICKER_STYLE+"' ID='TICKER_BODY' width='100%'></SPAN>"+img+"</DIV>";
    <?php if ($mensaje <> ""){ ?>
     document.getElementById("MENSAJE").innerHTML = "<DIV nowrap='nowrap' style='width:100%;'>"+img+"<SPAN style='"+MENSAJE_STYLE+"' ID='MENSAJE_BODY' width='100%'></SPAN>"+img+"</DIV>";
    <?php } ?>
    tickerSupported = true;
   }

   if( !tickerSupported ){
    document.getElementById("TICKER").outerHTML = ""; 
   } else {
    document.getElementById("TICKER").scrollLeft = TICKER_RIGHTTOLEFT ? document.getElementById("TICKER").scrollWidth - document.getElementById("TICKER").offsetWidth : 0;
    document.getElementById("TICKER_BODY").innerHTML = TICKER_CONTENT;
    document.getElementById("TICKER").style.display  = "block";
    <?php if ($mensaje <> ""){ ?>
     document.getElementById("MENSAJE_BODY").innerHTML   = MENSAJE_CONTENT;
     document.getElementById("MENSAJE").style.display    = "block";
    <?php } ?>
    TICKER_tick();
   }
  }

  function TICKER_tick() {
   if ( !TICKER_PAUSED )
    document.getElementById("TICKER").scrollLeft += TICKER_SPEED * (TICKER_RIGHTTOLEFT ? -1 : 1);
   if ( TICKER_RIGHTTOLEFT && document.getElementById("TICKER").scrollLeft <= 0)
    document.getElementById("TICKER").scrollLeft = document.getElementById("TICKER").scrollWidth - document.getElementById("TICKER").offsetWidth;
   if( !TICKER_RIGHTTOLEFT && document.getElementById("TICKER").scrollLeft >= document.getElementById("TICKER").scrollWidth - document.getElementById("TICKER").offsetWidth){
    document.getElementById("TICKER_BODY").innerHTML = document.getElementById("TICKER2").value;
    document.getElementById("TICKER").scrollLeft  = 0;

    document.getElementById("TICKER").style.background      = document.getElementById("TKR_BCOLOR").value;
    document.getElementById("TICKER_BODY").style.color      = document.getElementById("TKR_FCOLOR").value;
    document.getElementById("TICKER_BODY").style.fontFamily = document.getElementById("TKR_FONT").value;
    document.getElementById("TICKER_BODY").style.fontSize   = document.getElementById("TKR_SIZE").value;
    <?php if ($mensaje <> ""){ ?>
     document.getElementById("MENSAJE").innerHTML        = document.getElementById("MENSAJE2").value;
     document.getElementById("MENSAJE").style.background = document.getElementById("MSG_BCOLOR").value;
     document.getElementById("MENSAJE").style.color      = document.getElementById("MSG_FCOLOR").value;
     document.getElementById("MENSAJE").style.fontFamily = document.getElementById("MSG_FONT").value;
     document.getElementById("MENSAJE").style.fontSize   = document.getElementById("MSG_SIZE").value;
    <?php } ?>
   } 
   //alert(document.getElementById("TICKER_BODY").style.fontFamily);
   window.setTimeout("TICKER_tick()", 30);
  }
  </script>
 </body>
</html>