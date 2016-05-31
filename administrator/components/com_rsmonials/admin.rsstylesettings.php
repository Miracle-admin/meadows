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
	case 'editall':
		editall();
		break;
	case 'save':
		save();
		break;
	case 'saveall':
		saveall();
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
	
	$database->setQuery("select count(*) as tot from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where `ordering` > 0");
	$cnt = $database->loadObject();
	$total_page = ceil($cnt->tot/$limit2);
	
	$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where `ordering` > 0 order by `ordering` limit ".$limit1.",".$limit2."");
	$items = $database->loadObjectList();
?>
<div style="text-align:right;"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&view=<?php echo $_REQUEST['view']; ?>&action=editall&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>" title="Edit All Settings"><button type="button" class="btn hasTooltip js-stools-btn-clear">Edit All Settings</button></a></div>
<hr />
<table class="table table-striped" id="testisettingsList" style="position: relative;">
<thead>
    <tr>
        <th>#</th>
        <th class="title" style="text-align:left;" nowrap="nowrap">Parameter Name</th>
        <th class="title" style="text-align:left;">Parameter Description</th>
        <th class="title" style="text-align:left;" nowrap="nowrap">Parameter Value</th>
        <th nowrap="nowrap">ID</th>
        <th class="title">Edit</th>
    </tr>
</thead>
<tfoot>
    <tr>
        <td colspan="9">
            <script type="text/JavaScript">
            <!--
            function MM_jumpMenu(targ,selObj,restore){ //v3.0
              eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
              if (restore) selObj.selectedIndex=0;
            }
            //-->
            </script>
            <del class="container"><div class="pagination">

<div class="limit">Display #:
<select name="limit" id="limit" class="inputbox" size="1" onchange="MM_jumpMenu('parent',this,0)">
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=5" <?php if ($limit2 == '5') : ?>selected="selected"<?php endif; ?>>5</option>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=10" <?php if ($limit2 == '10') : ?>selected="selected"<?php endif; ?>>10</option>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=15" <?php if ($limit2 == '15') : ?>selected="selected"<?php endif; ?>>15</option>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=20" <?php if ($limit2 == '20') : ?>selected="selected"<?php endif; ?>>20</option>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=25" <?php if ($limit2 == '25') : ?>selected="selected"<?php endif; ?>>25</option>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=30" <?php if ($limit2 == '30') : ?>selected="selected"<?php endif; ?>>30</option>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=50" <?php if ($limit2 == '50') : ?>selected="selected"<?php endif; ?>>50</option>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=100" <?php if ($limit2 == '100') : ?>selected="selected"<?php endif; ?>>100</option>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=1&limit=999999" <?php if ($limit2 == '999999') : ?>selected="selected"<?php endif; ?>>all</option>
</select> | Page:
<select name="page" id="page" class="inputbox" size="1" onchange="MM_jumpMenu('parent',this,0)">
<?php for($i=1;$i<=$total_page;$i++) { ?>
<option value="index.php?option=<?php echo $_REQUEST['option'] ?>&view=<?php echo $_REQUEST['view'] ?>&page=<?php echo $i; ?>&limit=<?php echo $limit2; ?>" <?php if ($i == $pa) : ?>selected="selected"<?php endif; ?>><?php echo $i; ?></option>
<?php } ?>
</select>
</div>
</div></del>			</td>
    </tr>
</tfoot>
    <tbody>
        <?php
        if(count($items) > 0) {
            $cnt = 1;
        foreach ($items as $item) {
        
        ?>
        <tr class="row<?php echo ($cnt%2); ?>">
            <td align="center"><?php echo $limit1+$cnt; ?></td>
            <td nowrap="nowrap"><?php echo $item->param_name; ?></td>
            <td><?php echo nl2br($item->param_description); ?></td>
            <td nowrap="nowrap" style="color:green; font-weight:bold;"><?php echo safeHTML($item->param_value); ?></td>
            <td align="center"><?php echo $item->id; ?></td>
            <td align="center" nowrap="nowrap"><a href="index.php?option=<?php echo $_REQUEST['option']; ?>&view=<?php echo $_REQUEST['view']; ?>&action=edit&id=<?php echo $item->id; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>" title="Edit Item"><button type="button" class="btn hasTooltip js-stools-btn-clear">Edit Item</button></a></td>
        </tr>
        <?php
            $cnt++;
        }
        } else {
        ?>
        <tr><td colspan="9">No Item Found.</td></tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
	###############	
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}

function edit() {
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsheader.php");
	$id = $_REQUEST['id'];
	if($_REQUEST['action'] == 'edit') {
		$database = JFactory::getDBO();
		$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where `id`='".$_REQUEST['id']."'");
		$row = $database->loadObject();
	}
	###############
?>
<fieldset class="adminform">
				<script type="text/javascript">
				function submitFormRS() {
					var f = document.adminFormRS;
					if(f.param_value.value == '') {
						alert("Please enter value.");
						f.param_value.focus();
						return false;
					}
					else {
						return true;
					}
				}
				
				function cancelFormRS() {
					window.location.href="index.php?option=<?php echo $_REQUEST['option']; ?>&view=<?php echo $_REQUEST['view']; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>";
				}
				</script>
				<form action="index.php" method="post" name="adminFormRS" id="adminFormRS" onsubmit="return submitFormRS();">
				<input type="hidden" name="option" id="option" value="<?php echo $_REQUEST['option']; ?>" />
				<input type="hidden" name="view" id="view" value="<?php echo $_REQUEST['view']; ?>" />
				<input type="hidden" name="action" id="action" value="save" />
				<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page']; ?>" />
				<input type="hidden" name="limit" id="limit" value="<?php echo $_REQUEST['limit']; ?>" />
					<table class="table table-striped" id="testisettingsList" style="position: relative;">
					<tr>
						<th class="key" nowrap="nowrap">Parameter Name:</th>
						<th><?php echo $row->param_name; ?></th>
					</tr>
					<tr>
						<td colspan="2"><em><?php echo nl2br($row->param_description); ?></em></td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">Parameter Value:</td>
						<td><input class="text_area" type="text" name="param_value" id="param_value" value="<?php echo $row->param_value; ?>" size="50" /></td>
					</tr>
					<tr>
						<td class="key">&nbsp;</td>
						<td><input type="submit" name="submit" value="Submit" class="button" /> <input type="button" name="cancel" value="Cancel" class="button" onclick="return cancelFormRS();" /></td>
					</tr>
				</table>
				</form>
</fieldset>
<?php
	###############
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}

function save() {
	safePost();
	$database = JFactory::getDBO();
	$database->setQuery("update `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` set `param_value`='".$_POST['param_value']."' where `id`='".$_POST['id']."'");
	$database->query();
	header("location:index.php?option=".$_REQUEST['option']."&view=".$_REQUEST['view']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=1");
	exit();
}

function editall() {
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsheader.php");
	$database = JFactory::getDBO();
	$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where 1 order by `ordering`");
	$rows = $database->loadObjectList();
	###############
?>
<script type="text/javascript">
function submitFormRS() {
	var f = document.adminFormRS;
	for(var i=0; i<f.elements.length; i++) {
		if(f.elements[i].type == 'text') {
			if(f.elements[i].value == '') {
				alert("Please enter value of : "+f.elements[i].name+"");
				f.elements[i].focus();
				return false;
			}
		}
	}
	return true;
}

function cancelFormRS() {
	window.location.href="index.php?option=<?php echo $_REQUEST['option']; ?>&view=<?php echo $_REQUEST['view']; ?>&page=<?php echo $_REQUEST['page']; ?>&limit=<?php echo $_REQUEST['limit']; ?>";
}
</script>
<form action="index.php" method="post" name="adminFormRS" id="adminFormRS" onsubmit="return submitFormRS();">
<input type="hidden" name="option" id="option" value="<?php echo $_REQUEST['option']; ?>" />
<input type="hidden" name="view" id="view" value="<?php echo $_REQUEST['view']; ?>" />
<input type="hidden" name="action" id="action" value="saveall" />
<input type="hidden" name="page" id="page" value="<?php echo $_REQUEST['page']; ?>" />
<input type="hidden" name="limit" id="limit" value="<?php echo $_REQUEST['limit']; ?>" />
<div style="text-align:right;"><input type="submit" name="submit" value="Submit" class="button" /> <input type="button" name="cancel" value="Cancel" class="button" onclick="return cancelFormRS();" /></div>
<hr />
<table class="table table-striped" id="testisettingsList" style="position: relative;">
<?php
$ri = 0;
foreach($rows as $row) {
	if($ri != 0 && $ri%2 == 0) {
		echo '</tr><tr>';
	}
?>
<div>
<fieldset class="adminform">
	<tr><th nowrap="nowrap"><?php echo strtoupper($row->param_name); ?> :</th><td><input class="text_area" type="text" name="<?php echo $row->param_name; ?>" id="<?php echo $row->param_name; ?>" value="<?php echo $row->param_value; ?>" size="50" /><br /><em><?php echo nl2br($row->param_description); ?></em></td></tr>
</fieldset>
</div>
<?php
	$ri++;
}
?>
</table>
<hr />
<div style="text-align:right;"><input type="submit" name="submit" value="Submit" class="button" /> <input type="button" name="cancel" value="Cancel" class="button" onclick="return cancelFormRS();" /></div>
</form>
<?php
	###############
	include_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfooter.php");
}

function saveall() {
	safePost();
	$database = JFactory::getDBO();
	$database->setQuery("select * from `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` where 1");
	$rows = $database->loadObjectList();
	foreach($rows as $row) {
		$database->setQuery("update `#__".RSWEBSOLS_TABLE_PREFIX."_param_style` set `param_value`='".$_POST[''.$row->param_name.'']."' where `id`='".$row->id."'");
		$database->query();
	}
	header("location:index.php?option=".$_REQUEST['option']."&view=".$_REQUEST['view']."&page=".$_REQUEST['page']."&limit=".$_REQUEST['limit']."&result=1");
	exit();
}
?>