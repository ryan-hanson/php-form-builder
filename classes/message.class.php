<?
//  MESSAGES    	/////////////////////////////////////////////////////////////////////////////////////////
class Messages/*{{{*/
{	
	// Add new message to the array
	function newMessage($message,$type)
	{
		$this->messageList[$type][] = $message;
	}

	// Clear messages
	function clearMessages()
	{
		unset($this->messageList);
		unset($_SESSION['formbuilder']['success_message']);
	}

	// Print messages
	function printMessages()
	{
		if ( $_SESSION['formbuilder']['success_message'] != '' )
		{
			$this->newMessage($_SESSION['formbuilder']['success_message'], 'success');
		}

		ksort($this->messageList);
		foreach ( $this->messageList as $type=>$messages )
		{
		?>
			<div class="message <?=$type?>">	
			<?
			foreach($messages as $message)
			{
			?>
				<p>
					<?=$message?>
				</p>
			<?
			}
			?>
			</div>
		<?
		}

		$this->clearMessages();
	}
}/*}}}*/
?>
