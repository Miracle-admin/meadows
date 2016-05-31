<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

require_once('utils.php');
require_once('defines.php');
require_once('class.resizeImage.php');

$_target		= '';
$is_error		= false;

if( !extension_loaded('gd') && !extension_loaded('gd2') )
{
	$p=$n='';
	$i='GD is not loaded !';
	$e=3;
	$is_error		= true;
} 

if($is_error==false )
{
	if( 
		!isset( $_GET['_target'] ) 
		|| 
		$_GET['_target']=='' 
		||
		!isset( $_GET['_root_app'] ) 
		|| 
		$_GET['_root_app']=='' 
	)
	{
		$p=$n='';
		$i='Invalid params !';
		$e=2;
		$is_error		= true;
	}

	if($is_error==false )
	{
		$_root_app	= $_GET['_root_app'];
		$_target	= $_GET['_target'];
		$ex			= array();
		$ex			+= explode('/', $_target);

		if( $_root_app[ strlen( $_root_app )-1 ] != '/' )
			$_root_app .= '/';
		$_target_tmp	= JBusinessUtil::makePathFile($_root_app);
		
		foreach( $ex as $e )
		{
			if( $e == '' )
				continue;
			$dir = $_target_tmp.$e;
			echo($dir);
			echo "\n";
			if( !is_dir( $dir ) )
			{
				echo($dir);
				echo "\n";
				if( !@mkdir($dir) )
				{
					$p=$n='';
					$i='Error create directory '.$_target_tmp.DIRECTORY_SEPARATOR.$e.' !';
					$e=2;
					$is_error		= true;
					break;
				}
				
				/*if (is_dir($dir)) 
				{
					if ($dh = opendir($dir)) {
						while (($file = readdir($dh)) !== false) {
							echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
						}
						closedir($dh);
					}
				}
				*/

			}
			else
			{
				//dbg('Am '.$dir);
			}
			
			$_target_tmp.=$e.DIRECTORY_SEPARATOR;
		}
		
		if( $is_error == false  )
		{
			
			$identifier = 'uploadLogo';
			if(isset($_FILES['uploadfile']))
				$identifier = 'uploadfile';
			if(isset($_FILES['markerfile']))
				$identifier = 'markerfile';
			
			echo($identifier);
			echo "\n";
			$imageName = substr($_FILES[$identifier]['name'], 0, strrpos($_FILES[$identifier]['name'], '.'));
			echo ($imageName);
			echo "\n";
			$imageExt =substr($_FILES[$identifier]['name'], strrpos($_FILES[$identifier]['name'], '.'));;
			$resultFileName = $imageName."-".time().$imageExt;
			
			$_target = $_root_app.$_target . basename($resultFileName );
				
			
			$file_tmp = JBusinessUtil::makePathFile($_target);
			
			/* if( is_file($file_tmp) )
			{
				$p	=	'';
				$n	= 	basename( $file_tmp);
				$i	=	'This file exist !';
				$e	=	1;
			}
			else
			{ */
			
				$pictureType = 	$_GET['picture_type'];
				if(empty($pictureType)){
					$pictureType = PICTURE_TYPE_COMPANY;
				}
				echo "\n";
				echo($pictureType);
				echo "\n";
				if(strcmp($pictureType, PICTURE_TYPE_COMPANY)==0){
					$maxPictureWidth =  MAX_COMPANY_PICTURE_WIDTH;
					$maxPictureHeight = MAX_COMPANY_PICTURE_HEIGHT;
				}else if(strcmp($pictureType, PICTURE_TYPE_OFFER)==0){
					$maxPictureWidth =  MAX_OFFER_PICTURE_WIDTH;
					$maxPictureHeight = MAX_OFFER_PICTURE_HEIGHT;
				}else if(strcmp($pictureType, PICTURE_TYPE_LOGO)==0){
					$maxPictureWidth =  MAX_LOGO_WIDTH;
					$maxPictureHeight = MAX_LOGO_HEIGHT;
				}else if(strcmp($pictureType, PICTURE_TYPE_EVENT)==0){
					$maxPictureWidth =  MAX_OFFER_PICTURE_WIDTH;
					$maxPictureHeight = MAX_OFFER_PICTURE_HEIGHT;
				}else{
					$maxPictureWidth =  MAX_OFFER_PICTURE_WIDTH;
					$maxPictureHeight = MAX_OFFER_PICTURE_HEIGHT;
				}

				echo($file_tmp);
				echo "\n";
				echo($_FILES[$identifier]['tmp_name']);
				echo "\n";
				if(move_uploaded_file($_FILES[$identifier]['tmp_name'], $file_tmp)) 
				{
					$image = new Resize_Image;
					$image->ratio = true;
					
					$ratio = $maxPictureWidth/$maxPictureHeight;
					$size = getimagesize($file_tmp);
					$imageRatio = $size[0]/$size[1];
					
					if(!isset($maxPictureWidth)){
						$maxPictureWidth = $size[0];
						$maxPictureHeight = $size[1];
					}
					$resizeImage = false;
					dump($size);
					//set new height or new width depending on image ratio
					if($size[1] > $maxPictureHeight  || $size[0] > $maxPictureWidth){
						if($ratio<$imageRatio)
							$image->new_width 	= $maxPictureWidth;
						else
							$image->new_height 	= $maxPictureHeight;
						$resizeImage = true;
					}
					
					dump($resizeImage);
					
					dump($image->new_width);
					dump($image->new_height);
						
					$image->image_to_resize = $file_tmp; 	// Full Path to the file
					
					$image->new_image_name 	= basename($file_tmp);
					$image->save_folder 	= dirname($file_tmp).DIRECTORY_SEPARATOR;
					
					if($resizeImage){
						dump("resize");
						$process 			= $image->resize();
						if($process['result'] && $image->save_folder)
						{
							$p	=	basename( $file_tmp );
							$n	= 	basename( $file_tmp);
							$i	=	$file_tmp;
							$e	=	0;
						}
						else
						{
							unlink($file_tmp);
							$p=$n='';
							$i='Error resize uploaded file';
							$e=4;
						}
					}else{
						$p	=	basename( $file_tmp );
						$n	= 	basename( $file_tmp);
						$i	=	$file_tmp;
						$e	=	0;
					}
	
				} 
				else
				{
					$p=$n='';
					$i='Error move uploaded file';
					$e=2;
				}
			//}
		}
	}
}

echo '<?xml version="1.0" encoding="utf-8" ?>';
echo '<uploads>';
echo '<picture path="'.$p.'" info="'.$i.'" name="'.$n.'" error="'.$e.'" />';
echo '</uploads>';
echo '</xml>';

?>