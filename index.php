<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="index,follow" name="robots" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link href="pics/homescreen.gif" rel="apple-touch-icon" />
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />
<link href="css/style.css" rel="stylesheet" media="screen" type="text/css" />
<script src="javascript/functions.js" type="text/javascript"></script>
<title>Ultimaker Design Catalog</title>
<link href="pics/startup.png" rel="apple-touch-startup-image" />
<meta content="iPod,iPhone,Webkit,iWebkit,Website,Create,mobile,Tutorial,free" name="keywords" />
<meta content="Try out all the new features of iWebKit 5 with a simple touch of a finger and a smooth screen rotation!" name="description" />
</head>

<body>

<div id="topbar">
	<div id="title">Ultimaker Design Catalog</div>
	<div id="leftbutton"><a href="index.php" class="noeffect">Ultimaker</a> </div>
</div>

<div id="content">
	<span class="graytitle">Designs</span>
	<ul class="pageitem">
		
<?php
require("php/php_serial.class.php");

$serial = new phpSerial();

$serial->deviceSet("/dev/tty.usbmodem621");
$serial->confBaudRate(115200);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();

if (isset($_GET["select"])) {
	
	$filename = strtolower($_GET["select"]);
	
	?>
	<li class="textbox"><span class="header">Ultimaker</span><p>Printing... "<?php echo $filename?>"</p></li>
	<li class="menu"><a href="index.php?stop=1"><img alt="list" src="thumbs/ultimaker.png" /><span class="name">Stop printing</span><span class="comment"></span><span class="arrow"></span></a></li>

	<?php
	
	
	$serial->sendMessage("M23 $filename\r\n");
	$serial->sendMessage("M24 $filename\r\n");
	
} else if (isset($_GET["stop"])) {
	
	$serial->sendMessage("G28"); //home
	$serial->sendMessage("M25"); //pause
	$serial->sendMessage("M25"); //pause
	
	?>
	<li class="textbox"><span class="header">Ultimaker</span><p>Printing cancelled...</p></li>
	<?php
	
} else {
	
	?>
	<li class="textbox"><span class="header">Ultimaker</span><p>Please select a file from the list to print with the Ultimaker.</p></li>
	<?php
	
	$serial->sendMessage("M20\r\n");
	
	$read = "";
	
	while (strpos($read,"End file list")===FALSE) {
		$read .= $serial->readPort();
	}
	
	$serial->deviceClose();
	
	$arr = explode("\n", $read);
	
	for($i=0; $i<sizeof($arr); $i++) {
		$filename = $arr[$i];
		
		if (strpos($filename, "/")>0) {
			$filename = substr($filename, strrchr($filename, "/"));
		}
		
		?>
		<li class="menu"><a href="index.php?select=<?php echo $filename;?>"><img alt="list" src="thumbs/ultimaker.png" /><span class="name"><?php echo $filename;?></span><span class="comment"></span><span class="arrow"></span></a></li>
		
		<?php
	}
}
?>
	</ul>
</div>

</body>

</html>

