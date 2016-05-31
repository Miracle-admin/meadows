<?php
/**
 * 
 * @author George
 *
 */ 
class AttributeService{
	
	public static function renderAttributes($attributes, $enablePackages, $packageFeatures) {
		//dump($attributes);
		$renderedContent="";
		if(count($attributes)>0)
			foreach($attributes as $attribute) {
			$class = "";
			if(!isset($attribute->attributeValue)){
				$attribute->attributeValue ="";
			}
	
			if($attribute->is_mandatory==1 )
				$class = "validate[required]";
			
			if(isset($packageFeatures) && in_array($attribute->code,$packageFeatures) || !$enablePackages){
				switch ($attribute->attributeTypeCode){
					case "header":
						break;
					case "input":
						$inputValue= $attribute->attributeValue;
						 
						if($attribute->is_mandatory==1 )
							$class = "validate[required] text-input";
		
						$renderedContent.= '<div class="detail_box">';
						if($attribute->is_mandatory)
							$renderedContent.= '<div  class="form-detail req"></div>';
						$renderedContent.= '<label id="details-lbl" for="attribute_'.$attribute->id.'" class="hasTip" title="'.$attribute->name.'">'.$attribute->name.'</label>';
						$renderedContent.= '<input type="text" size="50" name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" value="'.$inputValue.'"  class="input_txt '.$class.'"/>';
						$renderedContent.= '<div class="clear"></div>';
						$renderedContent.= '</div>';
						
						break;
					case "textarea":
						$inputValue= $attribute->attributeValue;
							
						if($attribute->is_mandatory==1 )
							$class = "validate[required] text-input";
					
						$renderedContent.= '<div class="detail_box">';
						if($attribute->is_mandatory)
							$renderedContent.= '<div  class="form-detail req"></div>';
						$renderedContent.= '<label id="details-lbl" for="attribute_'.$attribute->id.'" class="hasTip" title="'.$attribute->name.'">'.$attribute->name.'</label>';
						$renderedContent.= '<textarea cols="10" name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" class="input_txt '.$class.'">'.$inputValue.'</textarea>';
						$renderedContent.= '<div class="clear"></div>';
						$renderedContent.= '</div>';
					
						break;
					case "select_box":
						$attributeOptions = explode(",",$attribute->options);
						$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
		
						if($attribute->is_mandatory==1 )
							$class = "validate[required] select";
						
						$renderedContent.= '<div class="detail_box">';
						if($attribute->is_mandatory)
							$renderedContent.= '<div  class="form-detail req"></div>';
						$renderedContent.= '<label id="details-lbl" for="attribute_'.$attribute->id.'" class="hasTip" title="'.$attribute->name.'">'.$attribute->name.'</label>';
						$renderedContent.= '<select name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" class="input_sel '.$class.'">';
						$renderedContent.= '<option value="" selected="true">'.JText::_("LNG_SELECT").'</option>';
						foreach ($attributeOptions as $key=>$option){
							if($attributeOptionsIDS[$key] == $attribute->attributeValue)
								$renderedContent.='<option value="'.$attributeOptionsIDS[$key].'" selected="selected">'.$option.'</option>';
							else
								$renderedContent.='<option value="'.$attributeOptionsIDS[$key].'">'.$option.'</option>';
						}
						$renderedContent.= '</select>';
						$renderedContent.= '<div class="clear"></div>';
						$renderedContent.= '</div>';
						break;
					case "checkbox":
						$attributeOptions = explode(",",$attribute->options);
						$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
						$attributeValues = explode(",",$attribute->attributeValue);
						if($attribute->is_mandatory==1 )
							$class = "validate[minCheckbox[1]] checkbox";
		
						$renderedContent.= '<div class="detail_box">';
						if($attribute->is_mandatory)
							$renderedContent.= '<div  class="form-detail req"></div>';
						$renderedContent.= '<label id="details-lbl" for="attribute_'.$attribute->id.'" class="hasTip" title="'.$attribute->name.'">'.$attribute->name.'</label>';
						foreach ($attributeOptions as $key=>$option){
							$renderedContent.="<div>";
							$option = "<span class='option'>".$option."</span>";
							if( in_array($attributeOptionsIDS[$key] , $attributeValues))
								$renderedContent.= '<input type="checkbox" name="attribute_'.$attribute->id.'[]" id="attribute_'.$attribute->id.'" value="'.$attributeOptionsIDS[$key].'"  class="'.$class.'" checked="true"/>'.$option;
							else
								$renderedContent.= '<input type="checkbox" name="attribute_'.$attribute->id.'[]" id="attribute_'.$attribute->id.'" value="'.$attributeOptionsIDS[$key].'"  class="'.$class.'"/>'.$option;
							$renderedContent.="</div>";
						}
						$renderedContent.= '<div class="clear"></div>';
						$renderedContent.= '</div>';
						break;
					case "radio":
						$attributeOptions = explode(",",$attribute->options);
						$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
						if($attribute->is_mandatory==1 )
							$class = "validate[required] radio";
		
						$renderedContent.= '<div class="detail_box">';
						if($attribute->is_mandatory)
							$renderedContent.= '<div  class="form-detail req"></div>';
						$renderedContent.= '<label id="details-lbl" for="attribute_'.$attribute->id.'" class="hasTip" title="'.$attribute->name.'">'.$attribute->name.'</label>';
						foreach ($attributeOptions as $key=>$option){
							
							$option = "<span class='option'>".$option."</span>";
							if($attributeOptionsIDS[$key] == $attribute->attributeValue)
								$renderedContent.= '&nbsp;<input type="radio" name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" value="'.$attributeOptionsIDS[$key].'"  class="'.$class.'" checked="true"/>&nbsp;&nbsp;'.$option;
							else
								$renderedContent.= '&nbsp;<input type="radio" name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" value="'.$attributeOptionsIDS[$key].'"  class="'.$class.'"/>&nbsp;&nbsp;'.$option;
						}
						$renderedContent.= '<div class="clear"></div>';
						$renderedContent.= '</div>';
						break;
					default:
						echo "";
				}
			}
		}
		return $renderedContent;
	}
	
	public static function renderAttributesSearch($attributes, $enablePackages, $packageFeatures) {
		//dump($attributes);
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$renderedContent="";
		if(!empty($attributes)){
			if($appSettings->enable_multilingual){
				JBusinessDirectoryTranslations::updateAttributesTranslation($attributes);
			}
			
			foreach($attributes as $attribute) {
			$class = "";
			if(!isset($attribute->attributeValue)){
				$attribute->attributeValue ="";
			}
	
			if($attribute->is_mandatory==1 )
				$class = "validate[required]";
				
			if(isset($packageFeatures) && in_array($attribute->code,$packageFeatures) || !$enablePackages){
				switch ($attribute->attributeTypeCode){
					case "header":
						break;
					case "input":
						$inputValue= $attribute->attributeValue;
						$renderedContent.= '<div class="form-field">';
						$renderedContent.= '<input type="text" placeholder="'.$attribute->name.'" size="50" name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" value="'.$inputValue.'"  class="input_txt '.$class.'"/>';
						$renderedContent.= '</div>';
						break;
					case "textarea":
						$inputValue= $attribute->attributeValue;
						$renderedContent.= '<div class="form-field">';
						$renderedContent.= '<label id="details-lbl" for="attribute_'.$attribute->id.'" class="hasTip" title="'.$attribute->name.'">'.$attribute->name.'</label>';
						$renderedContent.= '<textarea cols="10" name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" class="input_txt '.$class.'">'.$inputValue.'</textarea>';
						$renderedContent.= '</div>';
						break;
					case "select_box":
						$attributeOptions = explode(",",$attribute->options);
						$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
						$renderedContent.= '<div class="form-field">';
						$renderedContent.= '<select name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" class="input_sel '.$class.'">';
						$renderedContent.= '<option value="" selected="true">'.JText::_("LNG_SELECT").' '.$attribute->name.'</option>';
						foreach ($attributeOptions as $key=>$option){
							if($attributeOptionsIDS[$key] == $attribute->attributeValue)
								$renderedContent.='<option value="'.$attributeOptionsIDS[$key].'" selected="selected">'.$option.'</option>';
							else
								$renderedContent.='<option value="'.$attributeOptionsIDS[$key].'">'.$option.'</option>';
						}
						$renderedContent.= '</select>';
						$renderedContent.= '</div>';
						break;
					case "checkbox":
						$attributeOptions = explode(",",$attribute->options);
						$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
						$attributeValues = explode(",",$attribute->attributeValue);
						if($attribute->is_mandatory==1 )
							$class = "validate[minCheckbox[1]] checkbox";
	
						$renderedContent.= '<div class="form-field">';
						$renderedContent.= '<label id="details-lbl" for="attribute_'.$attribute->id.'" class="hasTip" title="'.$attribute->name.'">'.$attribute->name.'</label>';
						foreach ($attributeOptions as $key=>$option){
							$renderedContent.="<div class='custom-div'>";
							$option = "<span class='dir-check-lbl'>".$option."</span>";
							if( in_array($attributeOptionsIDS[$key] , $attributeValues))
								$renderedContent.= '<input type="checkbox" name="attribute_'.$attribute->id.'[]" id="attribute_'.$attribute->id.'" value="'.$attributeOptionsIDS[$key].'"  class="'.$class.'" checked="true"/>'.$option;
							else
								$renderedContent.= '<input type="checkbox" name="attribute_'.$attribute->id.'[]" id="attribute_'.$attribute->id.'" value="'.$attributeOptionsIDS[$key].'"  class="'.$class.'"/>'.$option;
							$renderedContent.="</div>";
						}
						$renderedContent.= '</div>';
						break;
					case "radio":
						$attributeOptions = explode(",",$attribute->options);
						$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
						if($attribute->is_mandatory==1 )
							$class = "validate[required] radio";
	
						$renderedContent.= '<div class="form-field">';
						$renderedContent.= '<label id="details-lbl" for="attribute_'.$attribute->id.'" class="hasTip" title="'.$attribute->name.'">'.$attribute->name.'</label>';
						foreach ($attributeOptions as $key=>$option){
								
							$option = "<span class='dir-check-lbl'>".$option."</span>";
							if($attributeOptionsIDS[$key] == $attribute->attributeValue)
								$renderedContent.= '&nbsp;<input type="radio" name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" value="'.$attributeOptionsIDS[$key].'"  class="'.$class.'" checked="true"/>&nbsp;&nbsp;'.$option;
							else
								$renderedContent.= '&nbsp;<input type="radio" name="attribute_'.$attribute->id.'" id="attribute_'.$attribute->id.'" value="'.$attributeOptionsIDS[$key].'"  class="'.$class.'"/>&nbsp;&nbsp;'.$option;
						}
						$renderedContent.= '</div>';
						break;
					default:
						echo "";
					}
				}
			}
		}
		return $renderedContent;
	}
	
	public static function renderAttributesFront($attributes, $enablePackages, $packageFeatures) {
		//dump($attributes);
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$renderedContent="";
		if(!empty($attributes)){
			//update the translations
			if($appSettings->enable_multilingual){
				JBusinessDirectoryTranslations::updateAttributesTranslation($attributes);
			}
			
			foreach($attributes as $attribute) {
				if($attribute->show_in_front != 1){
					continue;
				}
				
				if(isset($packageFeatures) && in_array($attribute->code,$packageFeatures) || !$enablePackages){
					switch ($attribute->attributeTypeCode){
						case "header":
							$renderedContent.="<h3 class='attribute-header'>".$attribute->name."</h3>";
							break;
						case "input":
							$inputValue= $attribute->attributeValue;
							if(!empty($inputValue)){
								$renderedContent.='<ul class="business-properties">';
								$renderedContent.= '<li><div>'.$attribute->name.': </div></li>';
								$renderedContent.= '<li>'.$inputValue.'</li>';
								$renderedContent.= '</ul>';
							}
							break;
						case "textarea":
								$inputValue= $attribute->attributeValue;
								if(!empty($inputValue)){
									$renderedContent.='<ul class="business-properties">';
									$renderedContent.= '<li><div>'.$attribute->name.': </div></li>';
									$renderedContent.= '<li>'.$inputValue.'</li>';
									$renderedContent.= '</ul>';
								}
								break;
						case "select_box":
							$attributeOptions = explode(",",$attribute->options);
							$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
							$inputValue="";
							foreach ($attributeOptions as $key=>$option){
								if($attributeOptionsIDS[$key] == $attribute->attributeValue){
									$inputValue = $option;
									break;
								}
							}
							if(!empty($inputValue)){
								$renderedContent.='<ul class="business-properties">';
								$renderedContent.= '<li><div>'.$attribute->name.': </div></li>';
								$renderedContent.= '<li>'.$inputValue.'</li>';
								$renderedContent.='</ul>';
							}
							break;
						case "checkbox":
							$attributeOptions = explode(",",$attribute->options);
							$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
							$attributeValues = explode(",",$attribute->attributeValue);
							if($attributeValues[0]=="") 
								break;
							$renderedContent.='<ul class="business-properties">';
							$renderedContent.= '<li><div>'.$attribute->name.': </div></li>';
							foreach ($attributeOptions as $key=>$option){
								if( in_array($attributeOptionsIDS[$key] , $attributeValues)){
									$renderedContent.= '<li>'.$option.', </li>';
								}
							}
							$renderedContent= rtrim($renderedContent,', </li>');
							$renderedContent.= '</li>';
							$renderedContent.='</ul>';
							break;
						case "radio":
							$attributeOptions = explode(",",$attribute->options);
							$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
							$inputValue="";
							foreach ($attributeOptions as $key=>$option){
								if($attributeOptionsIDS[$key] == $attribute->attributeValue){
									$inputValue = $option;
									break;
								}
							}
							if(!empty($inputValue)){
								$renderedContent.='<ul class="business-properties">';
								$renderedContent.= '<li><div>'.$attribute->name.': </div></li>';
								$renderedContent.= '<li>'.$inputValue.'</li>';
								$renderedContent.='</ul>';
							}
							break;
						default:
							echo "";
					}
				}
			}
		}
		return $renderedContent;
	}
	
	static function getAttributeValues($attribute){
		switch ($attribute->attributeTypeCode){
			case "header":
				return "";
			case "input":
				$inputValue= $attribute->attributeValue;
				if(!empty($inputValue)){
					return $inputValue;
				}else{
					return "";
				}
				break;
			case "textarea":
				$inputValue= $attribute->attributeValue;
				if(!empty($inputValue)){
					return $inputValue;
				}else{
					return "";
				}
				break;
			case "select_box":
				$attributeOptions = explode(",",$attribute->options);
				$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
				$inputValue="";
				foreach ($attributeOptions as $key=>$option){
					if($attributeOptionsIDS[$key] == $attribute->attributeValue){
						$inputValue = $option;
						break;
					}
				}
				if(!empty($inputValue)){
					return $inputValue;
				}else{
					return "";
				}
				break;
			case "checkbox":
				$attributeOptions = explode(",",$attribute->options);
				$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
				$attributeValues = explode(",",$attribute->attributeValue);
				if($attributeValues[0]=="") 
					break;
				$renderedContent="";
				foreach ($attributeOptions as $key=>$option){
					if( in_array($attributeOptionsIDS[$key] , $attributeValues)){
						$renderedContent.= $option.',';
					}
				}
				$inputValue= rtrim($renderedContent,',');
				if(!empty($inputValue)){
					return $inputValue;
				}else{
					return "";
				}
				break;
			case "radio":
				$attributeOptions = explode(",",$attribute->options);
				$attributeOptionsIDS = explode(",",$attribute->optionsIDS);
				$inputValue="";
				foreach ($attributeOptions as $key=>$option){
					if($attributeOptionsIDS[$key] == $attribute->attributeValue){
						$inputValue = $option;
						break;
					}
				}
				if(!empty($inputValue)){
					return $inputValue;
				}else{
					return "";
				}
				break;
			default:
				echo "";
		}
				
	}
}

?>