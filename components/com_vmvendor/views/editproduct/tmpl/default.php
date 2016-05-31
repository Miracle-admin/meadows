<?php
/*
 * @component VMVendor view editproduct
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$user 			= JFactory::getUser();
$db 			= JFactory::getDBO();
$doc 			= JFactory::getDocument();
$juri 			= JURI::base();
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
$app 			= JFactory::getApplication();
if (!class_exists( 'VmConfig' ))
	require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
VmConfig::loadConfig();
$product_vendor_id		= $this->product_data[0];
$product_sku 			= $this->product_data[1];
$product_weight			= $this->product_data[2];
$product_weightunit		= $this->product_data[3];
$product_in_stock		= $this->product_data[4];
$published				= $this->product_data[5];
$created_on				= $this->product_data[6];
$product_s_desc			= $this->product_data[7];
$product_desc			= $this->product_data[8];
$product_name			= $this->product_data[9];
$virtuemart_category_id = $this->product_data[10];
$product_price 			= $this->product_data[11];
$virtuemart_vendor_id = $this->virtuemart_vendor_id;

echo '<h1>'.JText::_('COM_VMVENDOR_VMVENEDIT_FORM_TITLE').'</h1>';
if (!class_exists( 'VmConfig' ))
	requireJPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
	
$cparams 					= JComponentHelper::getParams('com_vmvendor');
$profileman 				= $cparams->get('profileman');
JHTML::_('behavior.tooltip');
$naming 				= $cparams->get('naming', 'username');

$forbidcatids 	= $cparams->get('forbidcatids');	
$onlycatids 	= $cparams->get('onlycatids');
$price_format	= $this->price_format;
$symbol 		= $price_format[7];
$currency_id	= $price_format[0];
$currency 		= $price_format[4];
$currency_decimal_place = $price_format[8];
				
$termsurl 		= $cparams->get('termsurl');
$vendordefcat 	= $cparams->get('vendordefcat', '6');
$populatemf 	= $cparams->get('populatemf', 0);
$mfdefcat 		= $cparams->get('mfdefcat', '1');
$vmitemid 		= $cparams->get('vmitemid', '103');
$profileitemid 	= $cparams->get('profileitemid', '2');
	
$multicat	 	= $cparams->get('multicat', 0);
$maxfilesize 	= $cparams->get('maxfilesize', '4000000');//4 000 000 bytes   =  4M
$max_imagefields= $cparams->get('max_imagefields', 4);
if( $this->plan_max_img )
{
    $max_imagefields = $this->plan_max_img;
}
$max_filefields	= $cparams->get('max_filefields', 4);
if( $this->plan_max_files )
{
    $max_filefields = $this->plan_max_files;
}
$maximgside 	= $cparams->get('maximgside', '600');
$thumbqual 		= $cparams->get('thumbqual', 70);
$show_sku		= $cparams->get('show_sku', 0);
$enable_sdesc 	= $cparams->get('enable_sdesc', 1);
$wysiwyg_prod 		= $cparams->get('wysiwyg_prod', 0);
$enablefiles 	= $cparams->get('enablefiles', 1);
$enableweight 	= $cparams->get('enableweight', 1);
$weightunits 	= $cparams->get('weightunits');
$enableprice	= $cparams->get('enableprice', 1);
$enablestock	= $cparams->get('enablestock', 1);
$enablemanufield = $cparams->get('enablemanufield', 1);
$filemandatory 	= $cparams->get('filemandatory', 1);
$allowedexts 	= $cparams->get('allowedexts', 'zip,mp3');
$minimum_price	= $cparams->get('minimum_price', '5');
$sepext 		= explode( "," , $allowedexts );
$countext 		= count($sepext);
$enable_corecustomfields	= $cparams->get('enable_corecustomfields', 1);
$enable_vm2tags				= $cparams->get('enable_vm2tags', 0);
$tagslimit					= $cparams->get('tagslimit', '5');
$enable_vm2geolocator		= $cparams->get('enable_vm2geolocator', 0);
$enable_embedvideo			= $cparams->get('enable_embedvideo', 0);
$image_path 				= VmConfig::get('media_product_path');
$safepath 					= VmConfig::get( 'forSale_path' );
$thumb_path = $image_path.'resized/';
$formfile='';

if($enablefiles && $safepath=='')
	JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_SAFEPATHREQUIRED') );
if(VmConfig::get('multix','none')!='admin')
	JError::raiseWarning( 100, JText::_('COM_VMVENDOR_VMVENADD_MULTIVENDORREQUIRED') );

echo '<script type="text/javascript">function validateForm(it){
	var warning = "'.JText::_('COM_VMVENDOR_VMVENADD_JS_FIXTHIS').' \n";
	var same = warning;
	if (it.formcat.value=="0" || it.formcat.value=="")
	{
		warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_CATREQUIRED').' \n";
		it.formcat.style.backgroundColor = \'#ff9999\';
	}
	if (it.formname.value=="")
	{
		warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_NAMEREQUIRED').' \n";
		it.formname.style.backgroundColor = \'#ff9999\';
	}';
	//if(!$wysiwyg_prod) // not checking description if wysiwyg is on
if ($enable_sdesc)
{
	echo 'if (it.form_s_desc.value=="")
		{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_DESCREQUIRED').' \n";
			it.form_s_desc.style.backgroundColor = \'#ff9999\';
		}';
}
if($enableprice)
{
	echo 'if (it.formprice.value=="")
		{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_PRICEREQUIRED').' \n";
			it.formprice.style.backgroundColor = \'#ff9999\';
		}
		if (isNaN (it.formprice.value))
		{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_UNVALIDPRICE').' \n";
			it.formprice.style.backgroundColor = \'#ff9999\';
		}';	
}
	
if($enablestock)
{
	echo ' if (it.formstock.value=="" || isNaN (it.formstock.value))
			{
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_STOCKREQUIRED').' \n";
				document.getElementById("formstock").style.backgroundColor = \'#ff9999\';
				
			} ';		
}
	
if($enableweight)
{
		echo ' if (it.formweight.value=="" || isNaN (it.formweight.value))
			{
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_WEIGHTREQUIRED').' \n";
				document.getElementById("formweight").style.backgroundColor = \'#ff9999\';
				
			} ';	
			echo ' if (it.formweightunit.value=="" )
			{
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_WEIGHTUNITREQUIRED').' \n";
				document.getElementById("formweightunit").style.backgroundColor = \'#ff9999\';
				
			} ';	
}
	
if($enablefiles)
{ 				
	for( $i=1 ; $i<= $max_filefields ; $i++ )
	{
		echo  ' if(  document.getElementById("fileinput'.$i.'") )
			{
				var thisfile = it.file'.$i.';';
		echo 'if( thisfile.value!="" ';
		for ( $j=0 ; $j < $countext ; $j++ )
		{
		echo  ' &&  thisfile.value.indexOf(".'.$sepext[$j].'") == -1';
		}
		echo  ')
			{ 
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_FILEMISSING').' \n"; 
				thisfile.style.backgroundColor = \'#ff9999\';
			}';
		echo  '}';
	}	
}
for( $i=1 ; $i<= $max_imagefields ; $i++)
{
		echo  'if(document.getElementById("imginput'.$i.'"))
		{
			var thisimage = it.image'.$i.';
			if ( thisimage.value!="" &&  thisimage.value.indexOf(".jpg") == -1  &&  thisimage.value.indexOf(".gif") == -1 &&  thisimage.value.indexOf(".png") == -1 
									&&  thisimage.value.indexOf(".JPG") == -1  &&  thisimage.value.indexOf(".GIF") == -1 &&  thisimage.value.indexOf(".PNG") == -1)
			{ 
				warning += " * '.JText::_('COM_VMVENDOR_IMAGETYPENOT').' \n";
				thisimage.style.backgroundColor = \'#ff9999\';
				
	
			}
		}';
}
	
if($enable_vm2geolocator)
{
	echo  ' if( document.getElementById("latitude").value=="" || document.getElementById("longitude").value=="" )
			{		
					warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_FORM_MISSINGCOORDS').' \n";
					document.getElementById("latitude").style.backgroundColor = \'#ff9999\';
					document.getElementById("longitude").style.backgroundColor = \'#ff9999\';			
				}';
}
				
echo 'if (it.formterms.checked==false)
		{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_ACCEPTTERMS').' \n";
			document.getElementById("checkboxtd").style.backgroundColor = \'#ff9999\';
			
		}
		if (warning == same)
		{
			it.loading.style.display = "";
			return true;
		}
		else
		{
			alert(warning);
			return false;
		}
	}
	</script>';
	
echo  '<form name="add" enctype="multipart/form-data" onsubmit="return validateForm(this);" method="post" class="form-inline" >
<table class="table table-striped table-condensed">
<tr ><th>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_PRODUCTINFO').'</th>
<th><b>*</b> '.JText::_('COM_VMVENDOR_VMVENADD_MANDATORYFIELDS').'</th></tr>';
				
echo  '<tr  ><td>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_PUBLICATION').'</td>';
echo  '<td>';

if($this->autopublish)
{				
	//echo $published;
	$script = "jQuery(function(jQuery) {
		jQuery('#published, #unpublished').change(function () {
			var val = jQuery('input[name=\"formpublished\"]:checked').val();
			if(val=='1')
				jQuery('#announcebox').show();
			else{							
				jQuery('#announceupdate').attr('checked', false);
				jQuery('#announcebox').hide();
			}
	});});";
	$doc->addScriptDeclaration($script);

				
	echo  '<div class="badge badge-warning"><input type="radio" name="formpublished" id="unpublished" value="0"';
	if(!$published) echo ' checked="checked" ';
	echo '> <label for="unpublished">'.JText::_('JUNPUBLISHED').'</label></div>
							<div class="badge badge-success"><input type="radio" name="formpublished" id="published" value="1" ';
	if($published) echo ' checked="checked" ';
	echo '> <label for="published">'.JText::_('JPUBLISHED').'</label></div>';
}
else{

	echo '<input type="hidden" name="formpublished" value="'.$published.'" />';

	if($published)
		echo  '<div class="badge badge-success"><i class="vmv-icon-ok"></i> '.JText::_('JPUBLISHED').'</div>';
	else
		echo '<div class="badge badge-warning"><i class="vmv-icon-clock"></i> '.JText::_('JUNPUBLISHED').'</div>';
}
echo '</td></tr>';
echo  '<tr  ><td>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_NAME').' <b>*</b></td>
<td><input type="text" name="formname" id="formname" size="50" onkeyup="this.style.backgroundColor = \'\'" value="'.$product_name.'" class="form-control" />';
echo '</td></tr>';
if($show_sku)
{
	echo  '<tr ><td>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_SKU').'</td>
	<td>'.$product_sku.'</td></tr>';
}
echo '<INPUT type="hidden" value="'.$product_sku.'" name="formsku">
	<input type="hidden" name="productid" value="'.$app->input->get('productid','','int').'" />';

echo  '<tr style="text-align:left;" ><td>';		
if($multicat)
	echo JText::_('COM_VMVENDOR_VMVENADD_FORM_CATS');
else
	echo JText::_('COM_VMVENDOR_VMVENADD_FORM_CAT');
echo '</td>';
echo  "<td>";
//////////////////////// Category select field
echo '<select id="formcat"  class="form-control" ';
if($multicat)
{
	echo ' name="formcat[]" multiple="multiple" ';
	$virtuemart_category_id = $this->product_cats;
}
else
	echo ' name="formcat" ';
echo 'onchange="this.style.backgroundColor = \'\'">
				<option value="0">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHOOSECAT').'</option>';
function traverse_tree_down( $class , $category_id , $level ,  $forbidcatids , $onlycatids , $virtuemart_category_id , $virtuemart_vendor_id , $multicat)
{
	$db 						= JFactory::getDBO();
	$banned_cats = explode(',',$forbidcatids);
	$prefered_cats = explode(',',$onlycatids);
	$level++;
	$q = "SELECT * FROM `#__virtuemart_categories_".VMLANG."` AS vmcl, `#__virtuemart_category_categories` AS vmcc,   `#__virtuemart_categories` AS vmc
		WHERE vmcc.`category_parent_id` = '".$category_id."' 
		AND vmcl.`virtuemart_category_id` = `category_child_id` 
		AND vmc.`virtuemart_category_id` = vmcl.`virtuemart_category_id` 
		AND vmc.`published`='1' 
		AND (vmc.`virtuemart_vendor_id`='1' OR vmc.`virtuemart_vendor_id` ='".$virtuemart_vendor_id."' OR vmc.`shared`='1' ) ";
	foreach($banned_cats as $banned_cat)
	{
		$q .= "AND vmc.`virtuemart_category_id` !='".$banned_cat."' ";
	}
	if($onlycatids !='')
					$q .= " AND vmc.`virtuemart_category_id` IN (". implode(',', $prefered_cats ) .") ";					
	$q .= "	ORDER BY vmc.`ordering` ASC ";
	$db->setQuery($q);
	$cats = $db->loadObjectList();
	foreach($cats as $cat)
	{
		echo '<option value="'.$cat->virtuemart_category_id.'" ';
		if(!$multicat && $cat->virtuemart_category_id == $virtuemart_category_id)
			echo ' selected="selected" ';
		elseif( $multicat &&  in_array($cat->virtuemart_category_id , $virtuemart_category_id ) )
			echo ' selected="selected" ';
		echo '>';
		$parent =0;
		for ($i=1; $i<$level; $i++)
		{
			echo ' . ';
		}
		if($level >1)
			echo '  |_ ';
		echo JText::_($cat->category_name).'</option>';
		traverse_tree_down($class , $cat->category_child_id , $level , $forbidcatids , $onlycatids, $virtuemart_category_id  , $virtuemart_vendor_id, $multicat );
	}
}
$traverse = traverse_tree_down('' , 0 , 0 , $forbidcatids, $onlycatids , $virtuemart_category_id , $virtuemart_vendor_id, $multicat );
echo '</select>';
///////////////////////////////// end Category select field
echo  '</td></tr>';
if($enablemanufield)
{
	echo  '<tr ><td>'.JText::_('COM_VMVENDOR_VMVENADD_MANUFACTURER').'</td><td><div class="form-group">
	<select id="formmanufacturer" name="formmanufacturer" class="form-control" onchange="this.style.backgroundColor = \'\'"><option value="0">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHOOSEMANUFACTURER').'</option>';
	$manufacturers = VmvendorModelEditproduct::getManufacturers();
	$manufacturerid = VmvendorModelEditproduct::getProductManufacturer();
	foreach($manufacturers as $manufacturer)
	{
		echo '<option value="'.$manufacturer->virtuemart_manufacturer_id.'"  ';
		if($manufacturerid==$manufacturer->virtuemart_manufacturer_id)
			echo ' selected="selected" ';
		echo '>'.$manufacturer->mf_name.'</option>';	
	}
	echo  '</td>';
	echo  '</tr>';
					
					
}
if ($enable_sdesc)
{
	echo  '<tr  >';
	echo  '<td >'.JText::_('COM_VMVENDOR_VMVENADD_FORM_SDESC').' <b>*</b>';
	echo '<br /><B><SPAN id=myCounter>'. ( 255 - strlen($product_s_desc) ).' </SPAN></B> '.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHARSREMAINING');
	echo '</td>';
		
	$counterscript ='maxL= 255;
					var bName = navigator.appName;
					function taLimit(taObj) {
						if (taObj.value.length==maxL) return false;
						return true;
					}
					
					function taCount(taObj,Cnt) { 
						objCnt=createObject(Cnt);
						objVal=taObj.value;
						if (objVal.length>maxL) objVal=objVal.substring(0,maxL);
						if (objCnt) {
							if(bName == "Netscape"){	
								objCnt.textContent=maxL-objVal.length;}
							else{objCnt.innerText=maxL-objVal.length;}
						}
						return true;
					}
					
					function createObject(objId) {
						if (document.getElementById) return document.getElementById(objId);
						else if (document.layers) return eval("document." + objId);
						else if (document.all) return eval("document.all." + objId);
						else return eval("document." + objId);
					}';
	$doc->addScriptDeclaration($counterscript);		
	echo  '<td><textarea name="form_s_desc" id="form_s_desc" cols="45" rows="5" onkeyup="this.style.backgroundColor = \'\';return taCount(this,\'myCounter\');"   onKeyPress="return taLimit(this)" class="form-control" >';
	echo $product_s_desc;
	echo  '</textarea></td></tr>';
}
				
echo  '<tr  >';
echo  '<td >'.JText::_('COM_VMVENDOR_VMVENADD_FORM_DESC').' <b>*</b></td>';
if($wysiwyg_prod)
{
	jimport( 'joomla.html.editor' );
	$editor = JFactory::getEditor();
	$editorhtml = $editor->display("formdesc", $product_desc, "100%;", '200', '5', '30', false);
	//  ; required after %
	//$editorhtml = JEditor::display( 'editor',  '' , 'description', '100%;', '150', '5', '30' );
	echo  '<td>'.$editorhtml.'</td>';
}else
{
	echo  '<td><textarea name="formdesc" id="formdesc" cols="45" rows="5" onkeyup="this.style.backgroundColor = \'\'" class="form-control">';
	echo $product_desc;
	echo  '</textarea></td>';
}
echo  '</tr>';
				
if($enableweight)
{
					echo  '<tr  ><td>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT').' <b>*</b></td>';
					echo  '<td>';
					echo '<div class="form-group">';
					echo '<input type="text" class="form-control" ';
					if($product_weight)
						echo 'value="'.$product_weight.'" ';
					echo 'name="formweight" id="formweight" onkeyup="this.style.backgroundColor = \'\';" />';
					echo '</div>';
					if(count($weightunits)<1)
					{
						if($product_weightunit)
						{			
							echo JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_'.$product_weightunit);
							echo '<input type="hidden" id="formweightunit" name="formweightunit" value="'.$product_weightunit.'" />';
						}
						else
						{
							echo '<br />'.JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_UNITNOTDEFINED');
						}
						
					}
					elseif( ( count($weightunits)==1 && !$product_weightunit ) OR ( count($weightunits)==1 && $product_weightunit == $weightunits[0]) )
					{
							echo JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_'.$weightunits[0]);
							echo '<input type="hidden" id="formweightunit" name="formweightunit" value="'.$weightunits[0].'" class="form-control"/>';
					}
					elseif( count($weightunits)>1  OR !in_array($product_weightunit,$weightunits) )
					{
						echo ' <div class="form-group">';
						echo '<select id="formweightunit" name="formweightunit" onchange="this.style.backgroundColor = \'\';" class="form-control" >';
						echo '<option value="" >'.JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_SELECTUNIT').'</option>';
						foreach($weightunits as $weightunit)
						{
							echo '<option value="'.$weightunit.'" ';
							if($product_weightunit == $weightunit)
								echo ' selected="selected" ';
							echo '>';
							echo JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_'.$weightunit);
							echo '</option>';
						}
						if (!in_array($product_weightunit,$weightunits) && $product_weightunit!='')
						{
							echo '<option value="'.$product_weightunit.'" ';
							echo ' selected="selected" ';
							echo '>';
							echo JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_'.$product_weightunit);
							echo '</option>';	
							
						}
							
						echo '</select>';
						echo '</div>';
					}
					
					echo '</div>';
					echo  '</td></tr>';
}
				
if($enablestock)
{
	echo  '<tr  ><td>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_STOCK').' <b>*</b></td>';
	echo  '<td><div class="form-group"><input type="text" size="6" name="formstock" id="formstock" value="'.$product_in_stock.'" onkeyup="this.style.backgroundColor = \'\';this.value=this.value.replace(/\D/,\'\')" class="form-control" /></div></td>';
	echo  '</tr>';
					
}
				
				
if($enableprice)
{
	echo  '<tr ><td>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_PRICE').' <b>*</b></td>';
	echo  '<td><div class="form-group"><input type="text" name="formprice" id="formprice" value="'.number_format($product_price , $currency_decimal_place , '.','').'" onkeyup="this.style.backgroundColor = \'\'" class="form-control"/></div>
	<div class="form-group"><label for="formprice">'.$symbol.' ( '.$currency.' )</label></div>
	<INPUT type="hidden" value="'.$currency.'" name="currency">
	<INPUT type="hidden" value="'.$product_price.'" name="oldprice">
	</td></tr>';
}
echo  '<tr><td colspan="2"></td></tr>';
echo  '<tr  ><th colspan="2">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_FILES').'</th></tr>';
echo  '<tr  >';
if($enablefiles)
{////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get upload_max_filesize from php.ini and 
	$umfs = ini_get('upload_max_filesize');
	// get it in bytes...
	$umfs = trim($umfs);
	$last = strtolower($umfs[strlen($umfs)-1]);
	switch($last)
	{
		case 'g':
		$umfs *= 1024;
		case 'm':
		$umfs *= 1024;
		case 'k':
		$umfs *= 1024;
	}
	//if smaller than $maxfilesize replace $maxfilesize
	if ($umfs < $maxfilesize)
		$maxfilesize = $umfs;
	$maxfilesizemega = $maxfilesize/(1024*1024);
	$maxfilesizemega = round($maxfilesizemega,1)."MB";
				
	echo  '<td>';
	echo  JText::_('COM_VMVENDOR_VMVENADD_FORM_FILE').' ';
	if($filemandatory)////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo  '<b>*</b>';
	echo '<i class="vmv-icon-info-sign hasTooltip" title="('.$allowedexts.') '.JText::_('COM_VMVENDOR_VMVENADD_FORM_MAX').': '.$maxfilesizemega.'"></i>';
	echo  '</td>';
					
	echo  "<input type='hidden' name='MAX_FILE_SIZE' value='".$maxfilesize."' />";
	echo  '<td>';
					
					
	$m = 1;
	foreach($this->product_files as $product_file)
	{
		echo '<div id="fileinput'.($m ).'_edit" class="fileclonedInput well well-sm">';
		echo '<img src="'.$juri.'components/com_vmvendor/assets/img/ext/file-'.JFile::getExt($product_file->file_title).'.png" height="20" title="'.$product_file->file_title.'" style="vertical-align:middle;padding:2px;" /> '.$product_file->file_title.'';
		echo ' <input type="file" name="file'. $m .'" id="file'. $m .'" onchange="this.style.backgroundColor = \'\'" class="form-control" style="margin-bottom:4px;width:310px;" /> ';
		echo '<div class="file_removal" style="float:right;width:50px;">';
		if($m>1 OR !$filemandatory)
		{
			echo '<div title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHECKTOREMOVEFILE').'" class="hasTooltip">';
			echo '<input type="checkbox" name="delfile'. $m .'" id="delfile'. $m .'" class="checkbox ">';
						
			echo '<i class="vmv-icon-trash"></i>';
			echo '</div>';
		}
		else
		{
			echo '<div  >';
			echo '<input type="checkbox"  class="checkbox hasTooltip" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CANTDELETEFIRSTFILE').'" disabled >';
			echo '<i class="vmv-icon-trash"></i>';
			echo '</div>';
		}
		echo '<input type="hidden" name="filemedia_id'. $m .'" id="filemedia_id'. $m .'" value="'.$product_file->virtuemart_media_id.'" >';
		echo '</div>';echo '</div>';
		$m++;
	}
	$file_ajax = "var jq = jQuery.noConflict();
					jq(document).ready(function() {
				jq('#fileAdd').click(function() {
					var num     = jq('.fileclonedInput').length;
					var newNum  = new Number(num + 1);
	 
					var newElem = jq('#fileinput' + num).clone().attr('id', 'fileinput' + newNum);
	 
					newElem.children(':first').attr('id', 'file' + newNum).attr('name', 'file' + newNum);
					jq('#fileinput' + num).after(newElem);
					jq('#fileDel').attr('disabled',false);
	 
					if (newNum == ".$max_filefields .")
					   jq('#fileAdd').attr('disabled',true);
				});
	 
				jq('#fileDel').click(function() {
					var num = jq('.fileclonedInput').length;
	 
					jq('#fileinput' + num).remove();
					jq('#fileAdd').attr('disabled',false);
	 
					if (num-1 == $m)
						jq('#fileDel').attr('disabled',true);
				});
	 
				jq('#fileDel').attr('disabled',true);
			});";
	$doc->addScriptDeclaration($file_ajax);
	echo '<div style="display:none;">'; //trick to have hidden image fields
	
	echo '</div>';
	if($max_filefields>1 && $m < $max_filefields)
	{
		echo '<div style="float:right;width:100px"> <i class="vmv-icon-upload"></i>
		<input type="button" id="fileAdd" value="+" class="btn btn-xs btn-primary"/>
		<input type="button" id="fileDel" value="-" class="btn btn-xs btn-primary"/>
		</div>';
	}
	if($max_filefields>1 && $m <= $max_filefields)
	{
		echo '<div id="fileinput'.$m.'" style="margin-bottom:4px;width:300px;" class="fileclonedInput">';
		echo ' <input type="file" name="file'.$m.'" id="file'.$m.'" onchange="this.style.backgroundColor = \'\'" class="form-control" />';
		echo '</div>';
	}
	echo '</td></tr>';
}
echo  '<tr  ><td>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_IMAGE');
echo ' <i class="vmv-icon-info-sign hasTooltip" title="(png,gif,jpg) '.JText::_('COM_VMVENDOR_VMVENADD_FORM_MAXSIDE').' '.$maximgside.'px"></i>';
echo '</td>';
echo  '<td>';
$k = 1;

foreach($this->product_images as $product_image)
{
	echo '<div id="imginput'.($k ).'_edit" class="imgclonedInput well well-sm" >';
	echo '<img src="'.$juri.$product_image->file_url_thumb.'" height="25" class="hasTooltip" title="<img src=\''.$juri.$product_image->file_url_thumb.'\' /><br />'.$product_image->file_title.'"/>';
	echo ' <input type="file" name="image'. $k .'" id="image'. $k .'" onchange="this.style.backgroundColor = \'\'" style="margin-bottom:4px;width:310px;" class="form-control" />';
	echo '<div class="file_removal" style="float:right;width:50px;">';
	if($k>1)
	{
		echo '<div title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHECKTOREMOVEIMAGE').'" class="hasTooltip">';
		echo '<input type="checkbox" name="delimg'. $k .'" id="delimg'. $k .'" >';
						
		echo '<i class="vmv-icon-trash"></i>';
		echo '</div>';
	}
	else
	{
		echo '<div  class="hasTooltip" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CANTDELETEFIRSTIMG').'">';
		echo '<input type="checkbox"   disabled >';
		echo '<i class="vmv-icon-trash"></i>';
		echo '</div>';
	}
	echo '<input type="hidden" name="media_id'. $k .'" id="media_id'. $k .'" value="'.$product_image->virtuemart_media_id.'" >';
	echo '</div>';
	echo '</div>';
	$k++;
}
				
$img_ajax = "var jq = jQuery.noConflict();
				jq(document).ready(function() {
				jq('#imgAdd').click(function() {
					var num     = (jq('.imgclonedInput').length ) ;
					var newNum  = new Number(num + 1 );	
					var newElem = jq('#imginput' + num).clone().attr('id', 'imginput' + newNum);
					newElem.children(':first').attr('id', 'image' + newNum).attr('name', 'image' + newNum);
					jq('#imginput' + num).after(newElem);
					jq('#imgDel').attr('disabled',false);
					if (newNum == $max_imagefields )
						jq('#imgAdd').attr('disabled',true);
				});
	 
				jq('#imgDel').click(function() {
					var num = jq('.imgclonedInput').length;
					jq('#imginput' + num).remove();
					jq('#imgAdd').attr('disabled',false);	 
					if (num-1 == $k )
						jq('#imgDel').attr('disabled',true);
				});
	 
				jq('#imgDel').attr('disabled',true);
			});";
$doc->addScriptDeclaration($img_ajax);
echo '<div style="display:none;"></div>'; //trick to have hidden image fields
if($max_imagefields>1 && $k < $max_imagefields)
{
	echo '<div style="float:right;width:100px"> <i class="vmv-icon-picture"></i>
	<input type="button" id="imgAdd" value="+" class="btn btn-xs btn-primary"/>
	<input type="button" id="imgDel" value="-" class="btn btn-xs btn-primary"/>
	</div>';
}
if($max_imagefields>1 && $k <= $max_imagefields)
{
	echo '<div id="imginput'.($k ).'" style="margin-bottom:4px;width:310px;" class="imgclonedInput" >';
	echo ' <input type="file" name="image'.($k ).'" id="image'.($k ).'" onchange="this.style.backgroundColor = \'\'" class="form-control"/> ';
	echo '<div style="clear:both" > </div>';
	echo '</div>';
}
echo '</td></tr>';
				
				
				
if($enable_vm2tags OR $enable_vm2geolocator OR $enable_corecustomfields OR $enable_embedvideo)
{
	echo  '<tr><td colspan="2"></td></tr>';
	echo  '<tr ><th colspan="2">'.JText::_('COM_VMVENDOR_VMVENADD_CUSTOMFIELDS').'</th></tr>';
}
if($enable_vm2tags)
{
	
	echo  '<tr >';
	echo '<td>';
	echo JText::_('COM_VMVENDOR_VMVENADD_FORM_TAGS').' <i class="vmv-icon-tags hasTooltip" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_TAGSLIMIT').'"></i>';
	echo  '</td>';
					
	echo '<td >';
	if( file_exists(JPATH_BASE.'/plugins/vmcustom/vm2tags/vm2tags.php') )
	{
		$doc->addScript($juri.'components/com_vmvendor/assets/js/jquery.tagsinput.min.js');
		$doc->addScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js');
		$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/jquery.tagsinput.css');
		$doc->addStylesheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css');
		$tag_script = "var jq = jQuery.noConflict();
									function onAddTag(tag) {
										alert(\"Added a tag: \" + tag);
									}
									function onRemoveTag(tag) {
										alert(\"Removed a tag: \" + tag);
									}
									
									function onChangeTag(input,tag) {
										alert(\"Changed a tag: \" + tag);
									}
									jq(function() {
										jq('#formtags').tagsInput({width:'auto'});
	
								// Uncomment this line to see the callback functions in action
								//			jq('input.tags').tagsInput({onAddTag:onAddTag,onRemoveTag:onRemoveTag,onChange: onChangeTag});		
								
								// Uncomment this line to see an input with no interface for adding new tags.
								//			jq('input.tags').tagsInput({interactive:false});
			});";
			
		$doc->addScriptDeclaration($tag_script);
		if($this->product_tags!='')
		{
			$tags = str_replace(array('product_tags="' , '"|') ,'', $this->product_tags);
		}
		else
			$tags ='';
		echo '<input type="text" size="50" name="formtags" id="formtags"  value="'.$tags.'" class="form-control tags" />';
	}
	else
	{
		echo '<p class="well bg-danger"><i class="vmv-icon-cancel"></i> VM2tags plugin and component missing. 
		Disable the option in <a target="_blank" href="administrator/index.php?option=com_config&view=component&component=com_vmvendor">VMVendor settings</a> or 
		<a class="btn btn-primary"  target="_blank"
		href="http://www.nordmograph.com/extensions/index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=56&virtuemart_category_id=4&Itemid=58">
		Download VM2Tags</a></p>';
	}				
	echo '</td></tr>';	
}
				
if($enable_vm2geolocator)
{
	echo  '<tr  class="geolocator" style="background-color:#f7f7f7;">
	<td>';
	echo JText::_('COM_VMVENDOR_VMVENADD_FORM_LOCATION').' <i class="vmv-icon-location hasTooltip" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_LOCATIONDESC').'"></i>';
	echo  '</td>
	<td >';
	if( file_exists(JPATH_BASE.'/plugins/vmcustom/vm2geolocator/vm2geolocator.php') )
	{
		// get the vm2geolocator custom plugin parameters
		$api_url = '//maps.googleapis.com/maps/api/js?key='.$this->js_key.'&sensor=false&libraries=places';
		if($this->js_client !='' && $this->js_signature !='' )
			$api_url .= '&client='.$this->js_client.'&signature='.$this->js_signature;	
		$doc->addScript($api_url);
		$mapscript ="function add_Event(obj_, evType_, fn_){ 
				if (obj_.addEventListener)
					obj_.addEventListener(evType_, fn_, false); 
				else
					obj_.attachEvent('on'+evType_, fn_);  
			};
			function initializemap(){
				directionsDisplay = new google.maps.DirectionsRenderer();
				var latlng = new google.maps.LatLng(".$this->be_lat.",".$this->be_lng.");
				var myOptions = {
					zoom: ".$this->be_zoom.",
					center: latlng,
					mapTypeId: google.maps.MapTypeId.".$this->be_maptype.",
					scrollwheel: false
				}
	
				var map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);					
				var input = document.getElementById('searchTextField');
				var autocomplete = new google.maps.places.Autocomplete(input);
				autocomplete.bindTo('bounds', map);
				var place_infowindow = new google.maps.InfoWindow();
					var place_marker = new google.maps.Marker({
					map: map
				});
								google.maps.event.addListener(autocomplete, 'place_changed', function() {
								   place_infowindow.close();
								  var place = autocomplete.getPlace();
								  if (place.geometry.viewport) {
									map.fitBounds(place.geometry.viewport);
								  } else {
									map.setCenter(place.geometry.location);
									map.setZoom(17);  // Why 17? Because it looks good.
								  }				
								 
								 
								});
								var product = new google.maps.LatLng(".$this->be_lat.",".$this->be_lng.");
								var marker = new google.maps.Marker({	";	
		if($this->geoparams_set)
			$mapscript .="position: product,";	
		$mapscript .="map: map,
									clickable: false,					
									title:'".JText::_('VMCUSTOM_VM2GEOLOCATOR_PRODUCTLOCATION')."'
								});
								google.maps.event.addListener(map, 'click', function(event) {
									place_infowindow.close();												 
									var PointTmp2 = event.latLng;
									marker.setPosition(PointTmp2);
									document.getElementById('latitude').value = PointTmp2.lat();
									document.getElementById('longitude').value = PointTmp2.lng();
									document.getElementById('latitude').style.backgroundColor = '';
									document.getElementById('longitude').style.backgroundColor = '';
								});	
								google.maps.event.addListener(map, 'zoom_changed', function(event) {
								document.getElementById('zoom').value = map.getZoom();
								});
								google.maps.event.addListener(map, 'maptypeid_changed', function(event) {
									var mapTypeID = map.getMapTypeId();
									document.getElementById('maptype').value = mapTypeID.toUpperCase();
								});
						}
							function initgmap() {
							//if (arguments.callee.done) GUnload();
							arguments.callee.done = true;
								initializemap();
							};
							add_Event(window, 'load', initgmap);";
		$doc->addScriptDeclaration($mapscript);
		echo '<div id="map_canvas" style="height:300px;">#map<div>
						<div style="clear:both;position:absolute;"></div>
						</td>';
		echo  '<tr  class="geolocator" style="background-color:#f7f7f7;">';
		echo '<td></td><td>';
		echo '<div style="padding-bottom:3px;"><input id="searchTextField" type="text" size="50" placeholder="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_PLACE_SEARCH').'" class="form-control" /></div>';
		echo '<div class=" form-group col-lg-3"><input title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_LAT').'" type="text" value="';
		if($this->geoparams_set)
			echo $this->be_lat;
		echo '" size="10" name="latitude" id="latitude" readonly class="form-control" /></div> ';
	
		echo '<div class=" form-group col-lg-3"><input title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_LNG').'" type="text" value="';
		if($this->geoparams_set)
			echo $this->be_lng;
		echo '" size="10" name="longitude" id="longitude" readonly class="form-control" /></div> ';
		echo '<div class=" form-group col-lg-2"><input title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_ZOOM').'" type="text" value="'.$this->be_zoom.'" size="2" name="zoom" id="zoom" readonly class="form-control" /></div> ';
		echo '<div class=" form-group col-lg-3"><input title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_MAPTYPE').'" type="text" value="'.$this->be_maptype.'" size="10" name="maptype" id="maptype" readonly class="form-control"/></div>';
		echo '<div class="form-group"><a href="javascript:initgmap();" class="btn btn-sm btn-default" hasTooltipt" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_RESET').'"> <i class="vmv-icon-refresh"></i></a></div>';
	}
	else
	{
		echo '<p class="well bg-danger"><i class="vmv-icon-cancel"></i> VM2Geolocator plugin missing. 
		Disable the option in <a target="_blank" href="administrator/index.php?option=com_config&view=component&component=com_vmvendor">VMVendor settings</a> or 
		<a class="btn btn-primary"  target="_blank"
		href="http://www.nordmograph.com/extensions/index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=45&virtuemart_category_id=4&Itemid=58">
		Download VM2Geolocator</a></p>';
	}	
	echo '</td></tr>';	
}

if($enable_embedvideo)
{
	foreach($this->getEmbedvideoFields as $vid_field)
	{
			echo '<tr>';
			echo  '<td>';
			echo '<label for="embedvideo_'.$vid_field->virtuemart_custom_id.'"><i class="vmv-icon-youtube-play" ></i> '.$vid_field->custom_title.' </label>';
			if($vid_field->custom_tip)
				echo ' <i class="vmv-icon-info-sign hasTooltip" title="'.$vid_field->custom_tip.'" ></i>';
			echo '</td>';
			echo  '<td>';
			if( file_exists(JPATH_BASE.'/plugins/vmcustom/embedvideo/embedvideo.php') )
			{
				echo '<input type="text" placeholder="http://" class="form-control" name="embedvideo_'.$vid_field->virtuemart_custom_id.'" id="embedvideo_'.$vid_field->virtuemart_custom_id.'" ';
				if($vid_field->customfield_params!='')
				{
					$value = 	str_replace( array('video_url="' , '"|') , '', $vid_field->customfield_params);
					echo ' value="'.$value.'" ';
				}
					
				
				echo '/>';
			}
			else
				echo '<i class="vmv-icon-cancel"></i> Embedvideo plugin for Virtuemart is not installed. Get it from Nordmograph.com/extensions';
			echo '</td>';
			echo  '</tr>';
			
	}		
}





////////////////////////////// Core Custom fields support Hasardous place as Virtuemart shared and multivendor custom fields is not totally done yet.	
				
if($enable_corecustomfields)
{
	$i = 0;
	foreach ($this->core_custom_fields as $core_custom_field)
	{
						$i++;
						echo  '<tr >';
						echo '<td>';
						//echo 'Under dev: ';
						echo JText::_($core_custom_field->custom_title);
						
						if($core_custom_field->custom_tip !='' OR $core_custom_field->custom_desc!='' )
							echo ' <i class="vmv-icon-info-sign hasTooltip" title="'.JText::_($core_custom_field->custom_tip).'"></i>';
						echo  '</td>';
						
						echo '<td >';
						
		switch($core_custom_field->field_type){
			case "S":  //string
			if($core_custom_field->is_list)
				{
					$ccfc_value = explode(';',$core_custom_field->custom_value);
					
					
					echo '<select name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'">';
					for( $ccfc_i=0; $ccfc_i<count($ccfc_value); $ccfc_i++)
					{
						echo '<option value="'.$ccfc_value[$ccfc_i].'"';
						if($core_custom_field->value == $ccfc_value[$ccfc_i] )
							echo ' selected="selected" ';
						echo ' >'.JText::_($ccfc_value[$ccfc_i]).'</option>';	
					}
					
					echo '</select>';
				}
				else
					{
						echo '<input name="corecustomfield_'.$i.'" type="text" value="'.$core_custom_field->value.'" size="50" class="form-control" />';
					}
					
					
					
			break;
			case "I": // integer
			echo '<input name="corecustomfield_'.$i.'" type="text" value="'.$core_custom_field->value.'" size="50"  class="form-control" />';
			break;
			case "B": // bolean
			echo '<div class="radio-inline"><input name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'_0" type="radio"   value="0" ';
			if($core_custom_field->value =='0' )
				echo ' checked="checked" ';
			echo  '/><label for="corecustomfield_'.$i.'_0">'.JText::_('JNo').'</label></div> 
			<div class="radio-inline"><input name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'_1" type="radio"    value="1"';
			if($core_custom_field->value =='1' )
				echo ' checked="checked" ';
			echo ' /> <label for="corecustomfield_'.$i.'_1">'.JText::_('JYes').'</label></div>';
			break;
			case "D": // date
						
			echo JHTML::calendar('','corecustomfield_'.$i ,'corecustomfield_'.$i,'%Y-%m-%d');
			break;
			case "T": // time
			echo '<input class="form-control" name="corecustomfield_'.$i.'" type="text" value="'.$core_custom_field->value.'" size="50"  />';
			break;
			case "M": // image
								
			break;
			case "V": // cart variant
			if(!$core_custom_field->is_list)
				echo '<input name="corecustomfield_'.$i.'" type="text" value="'.$core_custom_field->value.'" size="50" class="form-control" />';
			else{
				$exploded_cartvar = explode(';',$core_custom_field->custom_value);
				if(count($exploded_cartvar)>1){
					echo '<select name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'" class="form-control" >';
					echo '<option value="">'.JText::_('COM_VMVENDOR_SELECT_OPTION').'</option>';
					for($i = 0; $i<count($exploded_cartvar);$i++){
						echo '<option value="'.$exploded_cartvar[$i].'"';
						if($exploded_cartvar[$i] == $core_custom_field->value)
							echo ' selected ';
						echo '>'.JText::_($exploded_cartvar[$i]).'</option>';	
						}
						echo '</select>';
					}
				}
									
			break;
			case "A": // generic Child variant
								
			break;
			case "X": // editor
				jimport( 'joomla.html.editor' );
				$editor = JFactory::getEditor();
				$editor_customfield_html = $editor->display("corecustomfield_".$i , $core_custom_field->value , "100%;", '200', '5', '30', false);
				echo  $editor_customfield_html;
				
			break;
							
			case "Y": // textarea
				echo '<textarea name="corecustomfield_'.$i.'" class="form-control">';
				echo $core_custom_field->value;
				echo '</textarea>';
								
			break;
		}
		echo '</td>';
		echo  '</tr>';
	}
}
				
				
echo  '<tr ><td>
</td><td ><div id="checkboxtd" class="checkbox inline" style="float:left;">
<input type="checkbox" name="formterms" id="formterms" onchange="document.getElementById(\'checkboxtd\').style.backgroundColor = \'\';"/> <label for="formterms">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_IAGREE').' '; 
if($termsurl !=NULL)
	echo  '<a href="'.$termsurl.'" target="_blank" >'.JText::_('COM_VMVENDOR_VMVENADD_FORM_TERMS').'</a>';
else
	echo  JText::_('COM_VMVENDOR_VMVENADD_FORM_TERMS');	
echo  ' <b>*</b></label></div>';
				
if( ( $profileman=='js' || $profileman=='es') && $this->autopublish && $created_on !='0000-00-00 00:00:00')
{
	echo '<div style="float:right;text-align:right;';
	if(!$published)
		echo 'display:none;';
	echo '" class="checkbox inline" id="announcebox">';	
	echo '<input type="checkbox" name="announceupdate" id="announceupdate"  /> <label for="announceupdate" >'.JText::_('COM_VMVENDOR_VMVENADD_ANNOUNCEUPDATE').'</label>';
	echo '</div>';
}

	
echo '</td></tr>';

if($flickr_autopost && $created_on =='0000-00-00 00:00:00')
	{
		echo  '<tr  ><td style="text-align:right"><i class="vmv-icon-flickr" ></i>';
	echo  '</td><td ><div id="checkboxtd" class="checkbox"><input type="checkbox" name="flickrcheckbox" id="flickrcheckbox" checked/> '.JText::_('COM_VMVENDOR_VMVENADD_FORM_PROMOTEONFLICKR'); //
    echo  ' </div></td></tr>';
		
	}
	
	
echo  '<tr >';
echo  '<td></td>';
echo  '<td>';
if ($user->id !=0)
{			
	echo '<button type="submit" name="update" id="button" value="'.JText::_('COM_VMVENDOR_VMVENADD_BTTN_UPDATE').'" class="btn btn-primary">'.JText::_('COM_VMVENDOR_VMVENADD_BTTN_UPDATE').'</button>';				
	echo ' <input type="button" name="cancel" id="cancelbutton" value="'.JText::_('JCANCEL').'" onclick="history.go(-1)" class="btn"/> ';
	echo  ' <img src="'.$juri.'components/com_vmvendor/assets/img/loader.gif" alt="" width="200" height="19" border="0" name="loading" id="loading"  align="absmiddle" style="display: none;" />';
}
else
	JError::raiseWarning( 100, '<font color="red"><b>'.JText::_('COM_VMVENDOR_VMVENADD_ONLYLOGGEDIN').'</b></font>');
echo '</td>';
echo  '</tr>';
echo  '</table>';
echo '<input type="hidden" name="option" value="com_vmvendor" />
	<input type="hidden" name="controller" value="updateproduct" />
	<input type="hidden" name="task" value="updateproduct" />';
echo  '</form>';
echo '<div style="clear:both;"> </div>';
?>