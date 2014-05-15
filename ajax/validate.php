<?
session_start();
require_once('../classes/message.class.php');
require_once('../classes/validation.class.php');
require_once('../classes/formbuilder.class.php');

$form = unserialize($_SESSION['formbuilder']['form'][$_GET['id']]);
$form->validateForm(array('redirect'=>0));

ob_start();
$form->printMessages();
$messages_html = preg_replace("/\s+/", " ", ob_get_clean());
ob_end_flush();

if ( count($form->error_field_keys) )
{
	echo json_encode(array('error_field_names'=>$form->error_field_keys, 'messages_html'=>$messages_html));
}
?>
