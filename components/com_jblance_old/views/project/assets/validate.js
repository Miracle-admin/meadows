
jQuery(function(){

jQuery("#editprojectcustom").validate(

{
        rules: {
            pj_title: {
      required: true
    },
	pz_desc:
	{
	required: true
	},
	pj_platform:
	{
	required:true
	},
	pj_budget:{
	required:true
	
	},
	dev_type:{
	required:true
	},
	u_email:
	{
	required:true,
	email: true,
	remote: {
	        url:"index.php?option=com_jblance&task=project.checkUser",
			type:"post"
			}
	},
	pj_count:{
	required:true
	
	},
	term_cond:{
	required:true
	},
	pj_expires:{
	required:true,
	digits: true
	
	
	}
	
	
        },
        messages: {
           
           pj_title: {
               required: Joomla.JText._('COM_JBLANCE_ENTER_PJ_TITLE')
            },
			pz_desc:
	         {
	        required: Joomla.JText._('COM_JBLANCE_ENTER_PJ_DESC')
	         },
			 pj_platform:
	         {
	         required: Joomla.JText._('COM_JBLANCE_SELECT_PLATFORM')
	         },
			 pj_budget:{
	        required: Joomla.JText._('COM_JBLANCE_SELECT_BUDGET')
	
	          },
			  dev_type:
			  {
			  required: Joomla.JText._('COM_JBLANCE_SELECT_DEVELOPER_TYPE')
			  },
			  u_email:
	           {
	          required: Joomla.JText._('COM_JBLANCE_ENTER_EMAIL'),
			  email: Joomla.JText._('COM_JBLANCE_ENTER_VALID_EMAIL'),
			  remote:Joomla.JText._('COM_JBLANCE_EMAIL_AEXISTS')
	           },
			   pj_count:
			   {
			   required:Joomla.JText._('COM_JBLANCE_CHOOSE_COUNTRY')
			   },
			   term_cond:{
	            required:Joomla.JText._('COM_JBLANCE_TANDC_AGREE')
	                  },
			    pj_expires:{
	                    required:Joomla.JText._('COM_JBLANCE_ENTER_NUMBER_DAYS'),
	                    digits:Joomla.JText._('COM_JBLANCE_ENTER_VALID_NUMBER')
	                      }
    }
}
);

for(x=0;x<parseInt(jQuery("#filelimit").val());x++)
{
jQuery("input[name='uploadFile"+x+"']").rules( "add", {
   extension: jQuery("#allowedExts").val(),
    messages: {
    extension: "Invalid file type"
    
  }
});
}

//check email
var emData=jQuery("input[name='u_email']").attr('email-data');

if(emData=="0")
{
jQuery("input[name='u_email']").rules("remove");
}




//update countries and states and cities
jQuery("#country_select").on('change',function(){
var field=jQuery(this).val();
var state=jQuery("#state_select");
var city=jQuery("#city_select");
var loading=jQuery("#loading_loc");
loading.show();
jQuery.post("index.php?option=com_jblance&task=project.getnewlocationAjax",{location_id:field},function(a){
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
state.append("<option value='"+data[x].id+"'>"+data[x].title+"</option>");
city.empty();
city.append("<option value=''>--Select city--</option>");
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
jQuery.post("index.php?option=com_jblance&task=project.getnewlocationAjax",{location_id:field},function(a){
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
city.append("<option value='"+data[x].id+"'>"+data[x].title+"</option>");
}
loading.hide();
})
})

})

