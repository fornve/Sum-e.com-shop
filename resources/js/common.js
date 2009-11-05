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
				object = eval("("+data+")");
				if(object.user_notification)
				{
					for( var i in object.user_notification)
						userNotification(object.user_notification[i]['text'],object.user_notification[i]['type']);
				}
				else
				{
					userNotification('Product added to basket.','notice');
				}
				
				jQuery('#basket').html(object['basket_html']);
			}, 
			'html' 
		);
	}
	return false;
}

function showCategory(id)
{
	var category_menu;
	var content;
	$.get( '/Category/GetMenu/'+ id +'?ajax=1', null, function(data) {
		$('#content').load('/Category/Index/'+ id +'/1/?ajax=1');
		$('#category_menu').html(data);
		$('#breadcrumbs').load( '/Category/GetBreadcrumbs/'+ id +'?ajax=1' );
	} );

	return false;
}

function var_dump(obj) {
   if(typeof obj == "object") {
      return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + obj;
   } else {
      return "Type: "+typeof(obj)+"\nValue: "+obj;
   }
}
