<?php
/**
 * @version 2.2
 * @package Joomla 3.x
 * @subpackage RS-Monials
 * @copyright (C) 2013-2022 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$action = $_REQUEST['action'];

switch($action) {
	case 'edit':
		edit();
		break;
	case 'save':
		save();
		break;
	case 'restore':
		restore();
		break;
	default:
		display();
}

function display() {
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsheader.php");
	###############
	global $app;
	$limit1 = 0;
	$limit2 = 0;
	$pa = 0;
	if($_REQUEST['limit'] > 0) {
		$limit2 = $_REQUEST['limit'];
	}
	else {
		$limit2 = $app->getCfg('list_limit');
	}
	if($_REQUEST['page'] > 0) {
		$pa = $_REQUEST['page'];
	}
	else {
		$pa = 1;
	}
	$limit1 = $limit2 * ($pa-1);
	$database = JFactory::getDBO();
	
	$database->setQuery("select count(*) as tot from `#__".RSWEBSOLS_TABLE_PREFIX."_param` where `ordering` > 0");
	$cnt = $database->loadObject();
	$total_page = ceil($cnt->tot/$limit2);
	
	$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."_param` where `ordering` > 0 order by `ordering` limit ".$limit1.",".$limit2."");
	$items = $database->loadObjectList();
?>
<table class="table table-striped" id="testisettingsList" style="position: relative;">
<thead>
    <tr>
        <th class="title" style="text-align:left;" nowrap="nowrap">Language File</th>
        <th class="title">Edit</th>
        <th class="title">Restore Default</th>
    </tr>
</thead>
    <tbody>
        <?php
        $dir = JPATH_BASE.DS.'..'.DS.'language';
        $display_path = 'language';
        $dh = opendir($dir);
        $cntr = 0;
        while (($file = readdir($dh)) !== false) {
            if(!is_dir($dir .DS. $file)) {
                continue;
            }
            else {
                $dname = trim($file);
                if(checkLangDir($dname)) {
                    $langfiledir = $dir.DS.$dname.DS;
                    $langfilename = $langfiledir.$dname.'.com_rsmonials.ini';
                    $langdisplaypath = $display_path.DS.$dname.DS.$dname.'.com_rsmonials.ini';
                    if(!file_exists($langfilename)) {
                        $lfp = fopen($langfilename, 'a');
                        fwrite($lfp, RSWEBSOLS_DEFAULT_LANGUAGE_FILE_CONTENT);
                        fclose($lfp);
                    }
        ?>
                    <tr class="row<?php echo $cntr; ?>">
                        <td valign="top" nowrap="nowrap"><strong><?php echo $langdisplaypath; ?></strong></td>
                        <td align="center" valign="top"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&view=<?php echo $_REQUEST['view']; ?>&action=edit&filepath=<?php echo $langdisplaypath; ?>" title="Edit Item"><button type="button" class="btn hasTooltip js-stools-btn-clear">Edit</button></a></td>
                        <td align="center" valign="top"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&view=<?php echo $_REQUEST['view']; ?>&action=restore&filepath=<?php echo $langdisplaypath; ?>" title="Restore Default" onclick="javascript:if(confirm('Restore default value! Are you sure? This will remove all your previous changes.')){return true;}else{return false;}"><button type="button" class="btn hasTooltip js-stools-btn-clear">Restore Default</button></a></td>
                    </tr>
        <?php
                    $cntr++;
                }
            }
        }
        closedir($dh);
        ?>
    </tbody>
</table>
<?php
	###############	
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}

function edit() {
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsheader.php");
	$langfile = JPATH_BASE.DS.'..'.DS.$_REQUEST['filepath'];
	$fp = fopen($langfile, "r");
	$cont = stripslashes(fread($fp, filesize($langfile)));
	fclose($fp);
	###############
?>
<fieldset class="adminform">
				<script type="text/javascript">
				function cancelFormRS() {
					window.location.href="index.php?option=<?php echo $_REQUEST['option']; ?>&view=<?php echo $_REQUEST['view']; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>";
				}
				</script>
				<form action="index.php" method="post" name="adminFormRS" id="adminFormRS">
				<input type="hidden" name="option" id="option" value="<?php echo $_REQUEST['option']; ?>" />
				<input type="hidden" name="view" id="view" value="<?php echo $_REQUEST['view']; ?>" />
				<input type="hidden" name="action" id="action" value="save" />
				<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page']; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo $_REQUEST['limit']; ?>" />
				<input type="hidden" name="filepath" id="filepath" value="<?php echo $_REQUEST['filepath']; ?>" />
					<table class="table table-striped" id="testisettingsList" style="position: relative;">
                    <tr>
                    	<th>File to Edit:</th>
						<th><?php echo $_REQUEST['filepath']; ?></th>
                    </tr>
					<tr>
						<td colspan="2">Edit File Content Below:</td>
                    </tr>
                    <tr>
						<td valign="top" colspan="2"><textarea style="width:100%; height:400px;" name="lang_file_content"><?php echo $cont; ?></textarea></td>
					</tr>
					<tr>
						<td></td><td><input type="submit" name="submit" value="Submit" class="button" /> <input type="button" name="cancel" value="Cancel" class="button" onclick="return cancelFormRS();" /></td>
					</tr>
				</table>
				</form>
</fieldset>
<?php
	###############
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}

function save() {
	//safePost();
	$fp = fopen(JPATH_BASE.DS.'..'.DS.$_POST['filepath'], "w");
	$val = $_POST['lang_file_content'];
	if(trim($val) == '') {
		$val = RSWEBSOLS_DEFAULT_LANGUAGE_FILE_CONTENT;
	}
	fwrite($fp, stripslashes($val));
	fclose($fp);
	header("location:index.php?option=".$_REQUEST['option']."&view=".$_REQUEST['view']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=4");
	exit();
}

function restore() {
	$cont = RSWEBSOLS_DEFAULT_LANGUAGE_FILE_CONTENT;
	$fp = fopen(JPATH_BASE.DS.'..'.DS.$_REQUEST['filepath'], "w");
	fwrite($fp, $cont);
	fclose($fp);
	header("location:index.php?option=".$_REQUEST['option']."&view=".$_REQUEST['view']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=5");
	exit();
}
?>