<?php
/**
* An example usage of the PHP session controller class.
*
* @author 	Blake <zen.fed96@gmail.com>
* @link		https://github.com/bmcculley/session-controller
*/

require_once('../session_control.php');

$sc = new SessionControl();

$ip = $_SERVER['REMOTE_ADDR'];

echo 'Session id is: '.($_COOKIE['sess']!='' ? $_COOKIE['sess'] : 'Not set yet.').'<br/>';

if ( !isset($_COOKIE['sess']) )  {
	$sc->start_session($ip);
}
else {
	echo 'Is a session set: '.$sc->get_session($_COOKIE['sess'],$ip);
}

?>