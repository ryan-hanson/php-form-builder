<?
//  VALIDATION CLASS   	/////////////////////////////////////////////////////////////////////////////////////////
class DataValidation extends Messages/*{{{*/
{
	// ensure value is not blank
	function required($value, $label)/*{{{*/
	{
		if ( $value == '' )
		{
			$this->newMessage('The '.$label.' field is required','error');
			return false;
		}
		return true;
	}/*}}}*/

	// human names, cities, states, countries
	function name($value,$label)/*{{{*/
	{
		if ( !preg_match("/[^a-zA-ZÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïñðòóôõöùúûüýÿ '-]/", $value) !== TRUE )
		{
			$this->newMessage('Please enter only letters, dashes, or single quotes for the '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/
	
	// email address
	function email($value,$label)/*{{{*/
	{
		if ( filter_var($value, FILTER_VALIDATE_EMAIL) === FALSE )
		{
			$this->newMessage('Please enter a valid '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/
	
	// url - returns newly formatted url
	function url($value,$label)/*{{{*/
	{
		if ( strstr($value,'http') === FALSE )
		{
			$value = 'http://'.$value;
		}
		if ( filter_var($value, FILTER_VALIDATE_URL) === FALSE )
		{
			$this->newMessage('Please enter a valid '.$label, 'error');
		}
		return $value;
	}/*}}}*/

	// phone
	function phone($value,$label)/*{{{*/
	{
		if ( !preg_match("/[^0-9 ()-]/", $value) !== TRUE )
		{
			$this->newMessage('Please enter a valid '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/

	// date - returns mysql formatted timestamp
	function date($value,$label)/*{{{*/
	{
		$stamp = strtotime( $value ); 
		$month = date( 'm', $stamp );
		$day   = date( 'd', $stamp ); 
		$year  = date( 'Y', $stamp );
		
		if ( !checkdate($month, $day, $year) || !is_numeric($stamp) )
		{
			$this->newMessage('Please enter a valid '.$label, 'error');
			return false;
		}
		else
		{
			return date('Y-m-d h:i:s', strtotime($value));
		}
		return true;
	}/*}}}*/

	// alpha
	function alpha($value,$label)/*{{{*/
	{
		if ( !preg_match("/[^a-zA-ZÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]/", $value) !== TRUE )
		{
			$this->newMessage('Please enter only letters for the '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/

	// numeric
	function numeric($value,$label)/*{{{*/
	{
		if ( !is_numeric($value) )
		{
			$this->newMessage('Please enter only numbers for the '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/

	// alpha numeric
	function alphanumeric($value,$label)/*{{{*/
	{
		if ( !preg_match("/[^a-zA-Z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]/", $value) !== TRUE )
		{
			$this->newMessage('Please enter only numbers and letters for the '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/

	// alpha numeric dash & underscore - good for usernames
	function alphanumericdash($value,$label)/*{{{*/
	{
		if ( !preg_match("/[^a-zA-Z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ _-]/", $value) !== TRUE )
		{
			$this->newMessage('Please enter only numbers, letters, dashes, and underscores for the '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/

	// integer
	function integer($value,$label)/*{{{*/
	{
		if ( filter_var($value, FILTER_VALIDATE_INT) === FALSE )
		{
			$this->newMessage('Please enter only integers for the '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/

	// float
	function float($value,$label)/*{{{*/
	{
		if ( filter_var($value, FILTER_VALIDATE_FLOAT) === FALSE )
		{
			$this->newMessage('Please enter only numbers for the '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/

	// credit card
	function creditcard($value,$label)/*{{{*/
	{
		$value = preg_replace('/\D/', '', $value);

		if ( is_numeric($value) )
		{
			$value_length = strlen($value);
			$parity = $value_length % 2;

			$total = 0;
			for ( $i = 0; $i < $value_length; $i++ )
			{
				$digit = $value[$i];
				if ( $i % 2 == $parity )
				{
					$digit *= 2;
					if ( $digit > 9)
					{
						$digit -= 9;
					}
				}
				$total += $digit;
			}

			if ( $total % 10 != 0 )
			{
				$invalid = 1;	
			}
		}
		else
		{
			$invalid = 1;
		}

		if ( $invalid )
		{
			$this->newMessage('Please enter a valid '.$label, 'error');
			return false;
		}
		return true;
	}/*}}}*/

	// ensure there's no duplicate in the database
	function unique($value, $label, $table, $column, $required)/*{{{*/
	{
		if ( $this->alphanumericdash($value, $label, $required) )
		{
			$sql = 'SELECT count(*) as total from '.$table.' where '.$column.' = "'.$value.'"';
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res);
			if ( $row['total'] )
			{
				$this->newMessage('That '.$label.' is already in use. Please try again','error');
				return false;
			}
		}
		return true;
	}/*}}}*/

	// password
	function password($password1,$password2)/*{{{*/
	{
		if ( $password1 == '' && $password2 == '' && $required )
		{
			$this->newMessage('The password field is required','error');
			return false;
		}
		if ( !$password1 ^ !$password2)
		{
			$this->newMessage('Please enter password twice','error');
			return false;
		}
		else if ( $password1 != $password2)
		{
			$this->newMessage('The passwords do not match','error');
			return false;
		}
		return true;
	}/*}}}*/

	// file of certain MIME type, accepts array of accepted types
	function file($file,$label,$mime_types,$upload_dir)/*{{{*/
	{
		if ( !is_uploaded_file($file['tmp_name']) )
		{
			if ( $required )
			{
				$this->newMessage('The '.$label.' upload field is required','error');
				return false;
			}
		}
		else
		{
			// Get Mime type of file
			$mimeMagicFile = get_cfg_var('mime_magic.magicfile');
			$f = finfo_open(FILEINFO_MIME, $mimeMagicFile);
			$mime_type = finfo_file($f, $file['tmp_name']);
			finfo_close($f);

			if ($mime_type=='')
			{
				$f = finfo_open(FILEINFO_MIME_TYPE);
				$mime_type = finfo_file($f, $file['tmp_name']);
				finfo_close($f);
			}
			if ( !in_array($mime_type,$mime_types) )
			{
				$this->newMessage('Please upload only '.$label.'(s)','error');
				return false;
			}
			else if ( $upload_dir != '' )
			{
				$file_name = uniqueFileName($upload_dir,$file['name']);
				if ( move_uploaded_file($file['tmp_name'],$upload_dir.$file_name) )
				{
					return $file_name;
				}
				else
				{
					$this->newMessage('Upload failed, please try again','error');
					return false;
				}	
			}
		}
		return true;
	}/*}}}*/

	// return an array of common mime types for a particular upload type
	function mimeTypes($upload_type)/*{{{*/
	{
		switch ( $upload_type )
		{
			case 'image':
			{
				$mimes = 	array
							(
								'image/png',
								'image/jpeg',
								'image/gif'
							);
				break;
			}
			case 'document':
			{
				$mimes = 	array
							(
								'text/plain',
								'text/x-c++',
								'application/pdf',
								'application/msword',
								'application/zip',
								'application/excel',
								'application/x-excel',
								'application/x-msexcel',
								'application/msaccess',
								'application/x-msaccess'
							);
				break;
			}
		}
		return $mimes;
		return true;
	}/*}}}*/
}/*}}}*/
?>
