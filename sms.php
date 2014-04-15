<?php

define('IN_MYBB', 1); 
define('THIS_SCRIPT', 'sms.php');
require_once "./global.php";
include_once('nusoap.php');

$message = $mybb->settings['dp_message'];

// No permission for guests
$Groups = explode(',', $mybb->settings['dp_groups']);
if(!in_array($mybb->user['usergroup'], $Groups))
{
error_no_permission();
}


if(isset($_POST['phone'])){
	$flash = false;
	$numbers = $_POST['phone'];
	$content = $_POST['content'];
	
	if ($mybb->settings['dp_soap'] == 0)
	{
		$sms_client = new SoapClient('http://www.novinpayamak.com/services/SMSBox/wsdl', array('encoding'=>'UTF-8'));
		$res = $sms_client->Send(array(
															'Auth' 	=> array('number' => $mybb->settings['dp_number'],'pass' => $mybb->settings['dp_pass']),
															'Recipients' => array('string' => array($numbers)),
															'Message' => array('string' => array($content)),
															'Flash' => flash
														));
	}
	
	if ($mybb->settings['dp_soap'] == 1)
	{
		 $sms_client = new nusoap_client('http://www.novinpayamak.com/services/SMSBox/wsdl', 'wsdl'); 
		$sms_client->soap_defencoding = 'UTF-8';
		
		
		
		$res = $sms_client->call('Send', array(
													array(
															'Auth' 	=> array('number' => $mybb->settings['dp_number'],'pass' => $mybb->settings['dp_pass']),
															'Recipients' => array('string' => array($numbers)),
															'Message' => array('string' => array($content)),
															'Flash' => flash
														)
													)
	);
	}

	if ($res !== -11 && $rse != -22){
	 $credits .= "<center><b style='color:red'> با تشکر از شما پیا مک شما ارسال شد</b></center>";
	}
}
else{
$credits = "<form method='post'>
<table width='800' height='200px' align='center' class='tbl' ><tr style='background: #f1f1f1 url(http://upload.novinpayamak.com/slideshows/box.png)no-repeat left;'><td class='right'>
<div class='fname'><tr><td class='right'><label for='lname'><span style='color: rgb(0, 0, 205);'>شماره تلفن همراه</span> : </label></td><td class='left'><input name='phone' class='input-eng' type='text'></td></tr></div>
<div class='fname'><tr><td class='right'><label for='lname'><span style='color: rgb(0, 0, 205);'>متن پيامک</span> : </label></td><td class='left'><textarea name='content' cols='45' rows='7'></textarea></td></tr></div>
<div class='submit'><tr><td class='right'><input value='ارسال پيامک' type='submit'></div></td></tr></td></tr></table>
<table width='800' align='center' class='tb2' ><tr><td class='center'><center>برنامه نويسي: <a href='http://www.aryan-translators.ir/index.php'> Hamed.Ramzi </a> | پشتيباني سيستم: <a href=http://www.aryan-translators.ir/'>مترجمين اريايي</a> | درگاه ارسال پیامک <a href='https://www.novinpayamak.com/'>نوین پیامک</a> </center></td></tr></table>
</form>";
}
$title = $mybb->settings['dp_title'];

add_breadcrumb($title, "sms.php");

eval("\$sms = \"".$templates->get("sms_page")."\";"); 
output_page($sms); 
?>