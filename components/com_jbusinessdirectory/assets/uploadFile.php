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
require_once( 'class.resizeImage.php');

$_target		= '';
$is_error		= false;
$i = '';

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
				
			}
			else
			{
				//dbg('Am '.$dir);
			}
			
			$_target_tmp.=$e.DIRECTORY_SEPARATOR;
		}
		
		if( $is_error == false  )
		{
			
			$identifier = 'uploadAttachment';
			
			echo($identifier);
			echo "\n";
			$fileName = substr($_FILES[$identifier]['name'], 0, strrpos($_FILES[$identifier]['name'], '.'));
			echo ($fileName);
			echo "\n";
			$fileExt =substr($_FILES[$identifier]['name'], strrpos($_FILES[$identifier]['name'], '.'));;
			$resultFileName = $fileName."-".time().$fileExt;
			
			$_target = $_root_app.$_target . basename($resultFileName );
				
			
			$file_tmp = JBusinessUtil::makePathFile($_target);
		
			$p	=	basename( $file_tmp );
			$n	= 	basename( $file_tmp);
			$i	=	$file_tmp;
			$e	=	0;
			if(!move_uploaded_file($_FILES[$identifier]['tmp_name'], $file_tmp))	{
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
echo '<attachment path="'.$p.'" info="'.$i.'" name="'.$n.'" error="'.$e.'" />';
echo '</uploads>';
echo '</xml>';

?>