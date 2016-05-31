/**
 * @version 2.2
 * @package Joomla 3.x
 * @subpackage RS-Monials
 * @copyright (C) 2013-2022 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}
window.onload = function() {
	var e = document.getElementsByClassName('comment-form-ant-spm', 'comment-form-ant-spm-2');
	for(var i=0; i<e.length; i++) {
		e[i].style.display='none';	
	}
	document.getElementById('ant-spm-q').value = document.getElementById('ant-spm-a').value;
};