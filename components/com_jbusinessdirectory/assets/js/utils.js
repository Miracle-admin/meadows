function validateField( objField, type, accept_empty, msg )
{
	if( accept_empty == null )
		accept_empty = true;
	if( msg == null )
		msg = '';
	if( objField == null )
		return false;
	if( accept_empty == true && objField.value == '')
		return true;
	
	var ret = true;

	if( type == 'numeric' )
	{
		ret = isNumeric(objField.value);
	}
	else if( type == 'string' )
	{
		ret = objField.value == ''? false : true ;
	}
	else if( type =='email')
	{
		var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
		ret = filter.test(objField.value); 
	}
	else if( type =='date')
	{
		ret = checkDate(objField);
	}

	
	if( ret == false && msg != '' )
	{
		alert(msg);
		if( objField.focus )
			objField.focus();
	}
	//myRegExpPhoneNumber = /(\d\d\d) \d\d\d-\d\d\d\d/
	return ret;
	
}

function isNumeric(str)
{
	mystring = str;
	if (mystring.match(/^\d+$|^\d+\.\d{2}$/ ) ) 
	{
		return true;
	}
	return false;

}

function classOf(o) 
{
	if (undefined === o) 
		return "Undefined";
	if (null === o) 
		return "Null";
	return {}.toString.call(o).slice(8, -1);
}

function checkDate(field) 
{ 
	var allowBlank 	= !true; 
	var minYear 	= 1902; 
	var maxYear 	= (new Date()).getFullYear(); 
	var errorMsg 	= ""; 
	// regular expression to match required date format 
	re = /^(\d{1,4})\-(\d{1,2})\-(\d{2})$/; 
	re2 = /^\d{1,2}\-\d{1,2}\-\d{4}$/; 
	if(field.value != '') 
	{ 
		
		if(regs = field.value.match(re)) 
		{ 
			if(regs[3] < 1 || regs[3] > 31) 
			{ 
				errorMsg = "Invalid value for day: " + regs[3]; 
			} 
			else if(regs[2] < 1 || regs[2] > 12) 
			{ 
				errorMsg = "Invalid value for month: " + regs[2]; 
			} 
			else if(regs[1] < minYear /*|| regs[1] > maxYear*/) 
			{ 
				errorMsg = "Invalid value for year: " + regs[1];//+ " - must be between " + minYear + " and " + maxYear; 
			} 
		}
		else if (regs= field.value.match(re2))
		{ 
			if(regs[1] < 1 || regs[1] > 31) 
			{ 
				errorMsg = "Invalid value for day: " + regs[3]; 
			} 
			else if(regs[2] < 1 || regs[2] > 12) 
			{ 
				errorMsg = "Invalid value for month: " + regs[2]; 
			} 
			else if(regs[3] < minYear /*|| regs[1] > maxYear*/) 
			{ 
				errorMsg = "Invalid value for year: " + regs[1];//+ " - must be between " + minYear + " and " + maxYear; 
			} 
		}	
		else{
			errorMsg = "Invalid date format: " + field.value; 
		} 
	} 
	else if(!allowBlank) 
	{ 
		errorMsg = "Empty date not allowed!"; 
	} 
	
	if(errorMsg != "") 
	{ 
		alert(errorMsg);return false; 
	} 
	return true; 
}


function compareDate(field1, field2,msg) 
{ 
	// regular expression to match required date format 
	re = /^(\d{1,4})\-(\d{1,2})\-(\d{2})$/; 
	if(field1.value != '' && field2.value != '') 
	{ 
		regs1 = field1.value.match(re);
		regs2 = field2.value.match(re);
		if(
			regs1 
			&&
			regs2 
		) 
		{ 
			date1 = new Date(regs1[1],regs1[2],regs1[3]);
			date2 = new Date(regs2[1],regs2[2],regs2[3]);
			ret = date1 <= date2;
		}
	} 
	
	if( ret == false && msg != '' )
	{
		alert(msg);
		if( field1.focus )
			field1.focus();
	}
	
	return ret;

}
