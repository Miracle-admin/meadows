jQuery(function(){

jQuery("#developerRegistration").validate(
{
    rules: {
            username: {
			required: true,
			minlength: 2,
			remote: {
					url:"index.php?option=com_jblance&task=developer.checkUserName",
					type:"post"
					} 
			},
			email:
			{
			required:true,
			email: true,
			remote: {
					url:"index.php?option=com_jblance&task=developer.checkUserEmail",
					type:"post"
					} 
			},
			password: {
					required: true,
					minlength: 5
				},
			password2: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			url:
				{
				required:true,				
				remote: {
						url:"index.php?option=com_jblance&task=developer.checkUrl",
						type:"post"
						} 
				},
			dev_type:{
					required: true
			},
			pj_count:{
					required: true
			},
			pj_state:{
					required:true
			}/* ,
			pj_city:{
					required:true
			} */
			,
			terms:{
				required:true
			}
			
	},
    messages: {           
		   username: {
			   required: Joomla.JText._('COM_JBLANCE_ENTER_USERNAME'),
			   minlength: jQuery.validator.format("Enter at least {0} characters"),
			   remote:Joomla.JText._('COM_JBLANCE_USERNAME_AEXISTS')
			},
			email:
		   {
				required: Joomla.JText._('COM_JBLANCE_ENTER_EMAIL'),
				email: Joomla.JText._('COM_JBLANCE_ENTER_VALID_EMAIL'),
				remote:Joomla.JText._('COM_JBLANCE_EMAIL_AEXISTS')
		   },
		   password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
			},
			password2: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
		  url:
		   {
			  required: Joomla.JText._('COM_JBLANCE_ENTER_URL'),
			  remote:Joomla.JText._('COM_JBLANCE_URL_AEXISTS')
		   },
	   dev_type:
		  {
		  required: Joomla.JText._('COM_JBLANCE_SELECT_DEVELOPER_SKILL')
		  },
		pj_count:
		  {
		  required: Joomla.JText._('COM_JBLANCE_CHOOSE_COUNTRY')
		  },
		pj_state:{
			required: Joomla.JText._('COM_JBLANCE_CHOOSE_STATE')
		} /*  ,
		pj_city:{
			required: Joomla.JText._('COM_JBLANCE_CHOOSE_CITY')
		}   */
		,
		terms:{
			required: Joomla.JText._('COM_JBLANCE_CHOOSE_TERMS')
		} 
    }
}
);

//update countries and states and cities
jQuery("#country_select").on('change',function(){
var field=jQuery(this).val();
var state=jQuery("#state_select");
var city=jQuery("#city_select");
var loading=jQuery("#loading_loc");
loading.show();
jQuery.post("index.php?option=com_jblance&task=developer.getnewlocationAjax",{location_id:field},function(a){
state.empty();

var data=JSON.parse(a);
if(data!=0)
{
state.append("<option value=''>--Select state--</option>");
}
else
{
state.append("<option value=''>--No information--</option>");
city.empty();
city.append("<option value=''>--No information--</option>");
}
for(var x in data){
 //alert( 'state value ' +  data[x].title)
 if(typeof  data[x].title != "undefined")
 {
	state.append("<option value='"+data[x].id+"'>"+data[x].title+"</option>");
	city.empty();
	city.append("<option value=''>--Select city--</option>");
 }



}
loading.hide();
})
})
//update city
jQuery("#state_select").on('change',function(){
var field=jQuery(this).val();

var city=jQuery("#city_select");
var loading=jQuery("#loading_state");
loading.show();
jQuery.post("index.php?option=com_jblance&task=developer.getnewlocationAjax",{location_id:field},function(a){
city.empty();
var data=JSON.parse(a);
if(data!=0)
{
city.append("<option value=''>--Select city--</option>");
}
else{
city.append("<option value=''>--No information--</option>");
}

for(var x in data){
if(typeof  data[x].title != "undefined")
 {
	city.append("<option value='"+data[x].id+"'>"+data[x].title+"</option>");
 }
}
loading.hide();
})
})


jQuery('#dname').keyup(function(){	
	var dname = jQuery('#dname').val();
	jQuery('#url').val(generateURLValue(dname));		
}); 
})


function filterURLValue(value){
	return value.toLowerCase().replace(/[àáâãäåæǝāÀÁÄ]/gi, 'a').replace(/[èéêëÉÈË]/gi, 'e').replace(/[ìíîïıīÍÌÏ]/gi, 'i').replace(/[(ðòóôõöøœÓÒÖ)]/gi, 'o').replace(/[ùúûüÚÙÜ]/gi, 'u').replace(/[þß]/gi, 'b').replace(/[ç]/gi, 'c').replace(/[ğ]/gi, 'g').replace(/[ñ]/gi, 'n').replace(/[ş]/gi, 's').replace(/[ýÿ]/gi, 'y').replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' ');
}

function generateURLValue(value){
	new_value = filterURLValue(value.toLowerCase()).replace(/[ ]/gi,'-');
	return new_value;
} 