<?php


if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br />
<br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("build_friendly_wol_location_end", "smspage_online");

// Plugin Information
function smspage_info()
{
    return array(
        "name"        => "ارسال پیامک توسط درگاه نوین پیامک",
        "description" => "توسط اين پلاگين شما قادر به ارسال اس ام اس با سامانه نوين پيامک",
        "website"     => "http://www.aryan-translators.ir",
        "author"      => "Hamed.Ramzi",
        "authorsite"  => "http://www.aryan-translators.ir",
        "version"     => "1.0",
		"compatibility" => "*",
		"guid"        => "",
        );
}

function smspage_is_installed()
{
	global $db;

if ($db->num_rows($db->simple_select("settings","name","name='dp_title'")) >= 1)	
{
		return true;
	}

	return false;
}

// Install and Activate
function smspage_activate() {

global $db;

    $dp_group = array(
        "gid" => "NULL",
        "name" => "smspage",
        "title" => "تنظیمات پیامک",
        "description" => "محل تنظیمات و ورود مشخصات نوین پیامک",
        "disporder" => "35",
        "isdefault" => "no",
        );
    $db->insert_query("settinggroups", $dp_group);
    $gid = $db->insert_id();
    
    $dp_1 = array(
        "sid" => "NULL",
        "name" => "dp_title",
        "title" => "عنوان پنل",
        "description" => "عنوان پنل و نام صفحه",
        "optionscode" => "text",
        "value" => "ارسال پيامک",
        "disporder" => "1",
        "gid" => intval($gid),
        );
    $db->insert_query("settings", $dp_1);

    $dp_2 = array(
        "sid" => "NULL",
        "name" => "dp_soap",
        "title" => "SOAP",
        "description" => "ایا سواپ برروی سرور شما نصب است",
        "optionscode" => "yesno",
        "value" => "1",
        "disporder" => "2",
        "gid" => intval($gid),
        );
    $db->insert_query("settings", $dp_2);

    $dp_3 = array(
        "sid" => "NULL",
        "name" => "dp_groups",
        "title" => "دسترسي گروهها",
        "description" => "گروههایی که میخواهید برای انان نمایش داده شود را انتخاب نمائید (با کاما [,]جدا نمائید.)",
        "optionscode" => "text",
        "value" => "4",
        "disporder" => "3",
        "gid" => intval($gid),
        );
    $db->insert_query("settings", $dp_3);
  
    $dp_4 = array(
        "sid" => "NULL",
        "name" => "dp_number",
        "title" => "شماره سرويس",
        "description" => "شماره خط و سرويس شما رد سايت نوين پيامک",
        "optionscode" => "text",
        "value" => "",
        "disporder" => "4",
        "gid" => intval($gid),
        );
    $db->insert_query("settings", $dp_4);

  
    $dp_5 = array(
        "sid" => "NULL",
        "name" => "dp_pass",
        "title" => "رمز وبسرويس",
        "description" => "رمز وبسرويس شما در سايت نوين پيامک",
        "optionscode" => "text",
        "value" => "",
        "disporder" => "5",
        "gid" => intval($gid),
        );
    $db->insert_query("settings", $dp_5);

  
    $dp_6 = array(
        "sid" => "NULL",
        "name" => "dp_message",
        "title" => "پيام",
        "description" => "يک پيام نمونه براي نمايش (استفاده از اچ تی ام ال مجاز است)",
        "optionscode" => "textarea",
        "value" => "نوين پيامک با هدف ارتقاي فرهنگ استفاده از فناوري اطلاعات در کشور شروع به فعاليت نموده و به طور تخصصي فعاليت خود را در زمينه ارائه درگاه هاي ارسال پيامک انبوه متمرکز ساخت است.<br /> نوين پيامک در زمينه ارائه سرويس‌ها و راه‌حل‌هاي پيشرفته‌‌ پيام‌رساني بر پايه‌ي فناوري پيام‌کوتاه آغازنموده است . <br />اين مرکز با امکان اتصال به مرکز پيام‌ کوتاه شرکت ارتباطات سيار به عنوان نماينده اپراتور اصلي تلفن همراه در ايران و با بهره گيري از دانش و تخصص و انگيزه کادري مجرب به دنبال ارائه ايده هاي نوين تجاري در صنعت پيشرفته‌‌ پيام‌رساني به مشتريان محترم مي باشد.<br />",
        "disporder" => "6",
        "gid" => intval($gid),
        );
    $db->insert_query("settings", $dp_6);

$insert_array = array(
		'title' => 'sms_page',
		'template' => $db->escape_string('<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$mybb->settings[\'dp_title\']}</title>
{$headerinclude}
<link rel="stylesheet" type="text/css" href="css.css" />
</head>
<body>
{$header}
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}">
<tr>
<td class="thead">
<strong>{$mybb->settings[\'dp_title\']}</strong>
</td>
</tr>
<tr>
<td width="100%" class="trow1">
{$message}
<br /><hr><br />
{$credits}
</td>
</tr>
</table>
{$footer}
</body>
</html>'),
		'sid' => '-1',
		'version' => '',
		'dateline' => TIME_NOW
	);
	
	$db->insert_query("templates", $insert_array);


rebuildsettings();

}


// Deactivate and Uninstall
function smspage_deactivate() {

global $db, $mybb;
    require "../inc/adminfunctions_templates.php";
    $query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='smspage'");
    $g = $db->fetch_array($query);
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE gid='".$g['gid']."'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid='".$g['gid']."'");
    $db->delete_query("templates","title='sms_page'");

rebuildsettings();

global $db;

}

function smspage_online(&$plugin_array)
{
if (preg_match('/sms\.php/',$plugin_array['user_activity']['location']))
{
$plugin_array['location_name'] = "<a href=\"sms.php\">اس ام اس رايگان</a>";
}

return $plugin_array;
}

?>