$(document).ready(function()
{
	$('form.formBuilder :input.required').bind('keyup change',function()
	{
		if ( $(this).val() == '' )
		{
			$(this).removeClass('error').addClass('error');
		}
	});

	$('form.formBuilder input[type="submit"]').on('click',function(e)
	{
		e.preventDefault();
		var error = 0;

		var form = $(this).closest('form');

		// Normal submission
		$(':input.required').removeClass('error');
		$.each($(':input.required'), function()
		{
			if ( $(this).val() == '' )
			{
				$(this).addClass('error');
				error = 1;
			}
		});

		if ( !error && $(this).hasClass('ajax') )
		{
			var post = form.serialize();
    		$.ajax(
    		{
        		url: 'ajax/validate.php?id='+form.attr('id'),
        		type: 'post',
        		data: post,
        		success: function(d)
        		{
        			if ( d.trim() == '' )
        			{
        				form.submit();
        			}
        			else
        			{
        				var data = $.parseJSON(d);
        				form.find(':input').removeClass('error');
						$.each(data.error_field_names, function(index,value)
						{
							form.find(':input[name="'+value+'"]').addClass('error');
						});

						if ( data.messages_html.trim() != '' )
						{	
							form.siblings('div.message').replaceWith(data.messages_html);
						}
					}
                },
                error: function()
                {
                	// If ajax fails, let the server handle the validation
					form.submit();
                }
    		});
		}
		else if ( !error )
		{
			form.submit();
		}
	});
});


