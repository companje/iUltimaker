<?php
require("php/php_serial.class.php");

$serial = new phpSerial();

$serial->deviceSet("/dev/tty.usbmodem621");
$serial->confBaudRate(115200);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();

$serial->sendMessage("M20\r\n");

$read = "";

while (strpos($read,"End file list")===FALSE) {
	$read .= $serial->readPort();
}

$serial->deviceClose();

$arr = explode("\n", $read);

print_r($arr);


?>