jQuery(document).ready(function() 
{
 
  jQuery('#hpbc_form').submit(function(e) 
  {
    
    jQuery('#menssage_error').empty();
    jQuery('#error').hide();

    var category = jQuery('#hpbc_field_categories option:selected').val();
    var checkbox = jQuery("#hpbc_form input[type='checkbox']").is(":checked");

    if (category == "")
    {
    	e.preventDefault();
    	jQuery('#menssage_error').append(error_category);
    	jQuery('#error').show();
    }

    if (checkbox == "")
    {
    	e.preventDefault();
    	jQuery('#menssage_error').append(error_local);
    	jQuery('#error').show();
    }
 
  });
			 
});