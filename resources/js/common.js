function userNotification(message,type)
{
	jQuery("#user_notification").addClass(type);
	jQuery("#user_notification_message").html(message);
	jQuery("#user_notification").fadeIn('slow');
	setTimeout("$('#user_notification').fadeOut('slow');", 10000);
}

function addProductToBasket(id)
{
	var quantity = $('#quantity').attr('value');
	if( quantity > 0 )
	{
		jQuery.post( 
			'/Basket/Add/'+id+'?ajax=1',
			{ 'quantity': quantity },
			function(data) 
			{
				$('#basket').html(data);
			}, 
			'html' 
		);
	}
	return false;
}
