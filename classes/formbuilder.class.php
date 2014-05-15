<?
//  FORM BUILDER  	/////////////////////////////////////////////////////////////////////////////////////////
class FormBuilder extends DataValidation/*{{{*/
{
	function randomID($length = 10)
	{
    	$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        	$randomString .= $chars[rand(0, strlen($chars) - 1)];
    	}
    	return $randomString;
	}

	function FormBuilder()
	{
		// Random ID for form
		if ( !isset($this->id) )
		{
			$this->id = $this->randomID();
		}
	}

	/*-----------------------
	| 	Add Field to Form	|
	| -----------------------
	@param array settings
	setting keys:
	- name (required): Used in the label field and message reporting
	- key (required): Used as the name key for inputs
	- type: Used to determine input type and validation [name, text, email, url, phone, date, alpha, numeric, alphanumeric, alphanumbericdash, integer, float, creditcard, textarea, select]
	- required: Boolean to make an input required
	- options: Option array to use as Name/Value pairs for select menu
	- autofill: Boolean to determine whether the input will be filled in automatically with original input (used for sensitive data)
	- break: Boolean to create a break between fields
	*/
	function addField($settings)/*{{{*/
	{
		// Default Settings
		$this->settings = 	array
							(
								'key' => strtolower(str_replace(' ','_',$settings['name'])),
								'type' => 'text',
								'required' => 0,
								'options' => null,
								'autofill' => 1,
								'break' => 0
							);

		// Apply Passed Settings
		foreach($settings as $setting=>$value)
		{
			$this->settings[$setting] = $value;
		}

		$this->fields[] = $this->settings;
		unset($this->settings);
	}/*}}}*/

	/*-----------------------
	| 	Create HTML Form	|
	| -----------------------
	@param array settings
	setting keys:
	- method: Form method for submitting data
	- submit_value: Used as the value for the submit button
	- submit_key: Used as the name key for detecting form submission
	- ajax: boolean for whether to use client side validation
	- wrapper: boolean for whether a wrapper will be used around each field
	- wrapperStart: opening tag for field wrapper
	- wrapperEnd: closing tag for field wrapper
	*/
	function createForm($settings)/*{{{*/
	{
		// Default Settings
		$this->settings = 	array
							(
								'method' => 'post',
								'submit_value' => 'Submit',
								'submit_key' => 'submit_form',
								'ajax' => 0,
								'wrapper' => 1,
								'wrapperStart' => '<p>',
								'wrapperEnd' => '</p>'
							);

		// Apply Passed Settings
		foreach($settings as $setting=>$value)
		{
			$this->settings[$setting] = $value;
		}

		$this->data = $_REQUEST;

		$this->printMessages();

		// Placeholder for ajax messages
		if ( $this->settings['ajax'] )
		{
		?>
			<div class="message" style="display:none;"></div>
		<?
		}
		?>
		<form method="<?=$this->settings['method']?>" id="<?=$this->id?>" class="formBuilder">
			<?
			foreach($this->fields as $field)
			{
				// Print opening tag wrapper tag
				if ( $this->settings['wrapper'] )
				{
				?>
					<?=$this->settings['wrapperStart']?>
				<?
				}

				// Print label
				?>
				<label<?=$field['required'] ? ' class="required"' : ''?>><?=$field['name']?></label><br />
				<?

				// Print field
				switch($field['type'])
				{
					case 'text':
					case 'name':
					case 'email':
					case 'url':
					case 'phone':
					case 'date':
					case 'alpha':
					case 'alphanumeric':
					case 'numeric':
					case 'alphanumericdash':
					case 'integer':
					case 'float':
					case 'creditcard':
					{
					?>
						<input type="text" name="<?=$field['key']?>" value="<?=$field['autofill'] ? $this->data[$field['key']] : ''?>" class="<?=in_array($field['key'], $this->error_field_keys) ? 'error' : ''?> <?=$field['type']?> <?=$field['required'] ? 'required' : ''?>" />
					<?
						break;
					}

					case 'textarea':
					{
					?>
						<textarea name="<?=$field['key']?>" class="<?=in_array($field['key'], $this->error_field_keys) ? 'error' : ''?> <?=$field['required'] ? 'required' : ''?>"><?=$field['autofill'] ? $this->data[$field['key']] : ''?></textarea>
					<?
						break;
					}

					case 'select':
					{
						if ( count($field['options']) )
						{
						?>
							<select name="<?=$field['key']?>" class="<?=in_array($field['key'], $this->error_field_keys) ? 'error' : ''?> <?=$field['required'] ? 'required' : ''?>" >
							<?
							foreach($field['options'] as $value=>$name)
							{
							?>
								<option value="<?=$value?>" <?=$this->data[$field['key']] == $value && $field['autofill'] ? 'SELECTED' : ''?>><?=$name?></option>
							<?
							}
							?>
							</select>
						<?
						}
						break;
					}
				}
					
				// Print closing wrapper tag
				if ( $this->settings['wrapper'] )
				{
				?>
					<?=$this->settings['wrapperEnd']?>
				<?
				}
				
				// Break to next line
				if ( $field['break'] )
				{
				?>
					<div class="clear"></div>
				<?
				}
			}
			?>
			<div class="clear"></div>
			<input type="hidden" name="<?=$this->settings['submit_key']?>" value="1" />
			<input type="submit" value="<?=$this->settings['submit_value']?>" <?=$this->settings['ajax'] ? 'class="ajax"' : '' ?> />
		</form>
		<?
		unset($this->settings);
		$_SESSION['formbuilder']['form'][$this->id] = serialize($this);
	}/*}}}*/

	/*-----------------------
	| 	Validate the Form	|
	| -----------------------
	@param array settings
	setting keys:
	- redirect: Boolean for whether to redirect
	- url: URL to redirect to have a successful form submission
	- message: Message to display after successful form submission
	*/
	function validateForm($settings)
	{
		// Default Settings
		$this->settings = 	array
							(
								'redirect' => 0,
								'url' => $_SERVER['PHP_SELF'],
								'message' => 'The form was submitted successfully'
							);

		// Apply Passed Settings
		foreach($settings as $setting=>$value)
		{
			$this->settings[$setting] = $value;
		}

		$this->data = $_REQUEST;		
		foreach($this->fields as $field)
		{		
			$good = true;
			if ( $field['required'] )
			{
				$good = $this->required($this->data[$field['key']],$field['name']);
			}
		
			if ( $good )
			{
				if ( $_POST[$field['key']] != '' && method_exists('DataValidation', $field['type']) )
				{
					// Validate using class functions correlating to the field type
					if ( !$this->$field['type']($this->data[$field['key']],$field['name']) )
					{
						// Keep track of the all fields with errors (for ajax validation)
						$this->error_field_keys[] = $field['key'];
					}
				}
			}
			else
			{
				$this->error_field_keys[] = $field['key'];
			}
		}

		// If no errors
		if ( !count($this->error_field_keys) )
		{
			$_SESSION['formbuilder']['success_message'] = $this->settings['message'];
			if ( $this->settings['redirect'] )
			{
				header( 'Location:'.$this->settings['url'] );
				unset($this->settings);
				exit;
			}
		}
		unset($this->settings);
	}
}
/*}}}*/
?>
