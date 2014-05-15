<?
session_start();
require_once('classes/message.class.php');
require_once('classes/validation.class.php');
require_once('classes/formbuilder.class.php');

// Create form
$form = new FormBuilder();
$form->addField( array('name' => 'First Name', 'type' => 'name', 'required' => 1) );
$select_options = array('male'=>'Male', 'female'=>'Female');
$form->addField( array('name' => 'Gender', 'type' => 'select', 'required' => 1, 'options' => $select_options ) );
$form->addField( array('name' => 'Email', 'type' => 'email', 'required' => 1) );  
$form->addField( array('name' => 'Message', 'type' => 'textarea', 'required' => 1) );

// Post form
if ( isset($_POST['submit_form']) )
{
	if ( $form->validateForm() )
	{
		$form-newMessage('Success!','success',1);
	}
}
?>
<!DOCTYPE html>
<!--
=============================================
.--.               .   .                    
|   )              |   |                    
|--'.  ..-. .--.   |---|.-. .--..--..-..--. 
|  \|  (   )|  |   |   (   )|  |`--(   |  | 
'   `--|`-'`'  `-  '   '`-'`'  ``--'`-''  `-
       ;                                    
    `-'     
* Ryan Hanson - Web Developer and Designer
* ryan-hanson.com
=============================================
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Form Builder (with Ajax Validation)</title>

	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900' rel='stylesheet' type='text/css'>
	<style>
	body
	{
		font-family:'Source Sans Pro';
		margin:25px;
	}
	.error
	{
		background-color:#ECACAC;
	}

	.success
	{
		background-color:#ACECB6;
	}

	.message p
	{
		margin:5px;
	}
	</style>
</head>

<body>

<h2>Form Builder (with Ajax Validation)</h2>

<? 
// Generate form
$form->createForm(array('ajax'=>1));
?>

<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>

</body>

</html>
