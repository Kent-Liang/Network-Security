<?

// Installation script for the Txt-Db-API (www.c-worker.ch)
//
// This script will search for Txt-Db-API zip-packages in the current
// directory and will guide you through the installation. If there are
// more versions of Txt-Db-API packages in the current directory the
// most current version will be installed.
//
// Author: Mario Sansone
// Email: msansone@gmx.de

$VERSION = "1.0.1";

checkForImages();

session_start();

$SID = '';
if(!ini_get('session.use_trans_sid'))
	$SID = "&".session_name()."=".session_id();


$lang = isset($_GET['lang'])?$_GET['lang']:'de';
initLanguages($lang);

$step = isset($_GET['step'])?$_GET['step']:0;
$lastStep = isset($_GET['laststep'])?$_GET['laststep']:0;

if ($lastStep == 6 && $step == 5 && @$_SESSION['webgui'] == 0) {
	$step = 4;
}

if (isset($_POST['install']) && $lastStep == 4 && $step == 5) {
		if (!in_array('webgui', $_POST['install']))
			$step = 6;
}

$nextStep = $step + 1;
$prevStep = (($step - 1) < 0) ? 0 : ($step - 1);

if ($step == 1 && checkVersion() == 0) {
	printHeader();
	print "<td colspan=\"2\" valign=\"top\" style=\"padding: 15px;\">";
	print "<br><font color=\"red\">".$text['error'].":</font><br><br>".$text['download'];
	print "</td>";
	printFooter();
	exit;
}

if ($step == 0) {
//--------------------------- step 0 - begin -------------------------------

	$_SESSION['APIDIR'] = 'txt-db-api';
	$_SESSION['DBDIR'] = 'databases';
	$_SESSION['version'] = '';
	$_SESSION['examples'] = 1;
	$_SESSION['webgui'] = 1;
	$_SESSION['doc'] = 1;
	$_SESSION['wgusername'] = '';
	$_SESSION['wgpassword'] = '';
	
	printHeader();
?>
	<td colspan="2" valign="top" align="center" style="padding: 20px;">
		<br><br><br><br>Bitte w&auml;hlen Sie die Sprache.<br>Please, choose the language.<br><br>
		<b><a href="<?=$_SERVER['PHP_SELF']?>?step=<?=$nextStep?>&laststep=<?=$step?>&lang=de<?=$SID?>">Deutsch</a>&nbsp;&nbsp;|&nbsp;&nbsp;  
		<a href="<?=$_SERVER['PHP_SELF']?>?step=<?=$nextStep?>&laststep=<?=$step?>&lang=en<?=$SID?>">English</a></b>
	</td>
<?
//--------------------------- step 0 - end ---------------------------------
} else if ($step == 1) {
//--------------------------- step 1 - begin -------------------------------
printHeader();
?>
	<td colspan="2" valign="top" style="padding: 15px;">
		<?=$text['welcome']?><br>
		<table border="0"><tr valign="top">
		<td width="20"><img src="<?=$_SERVER['PHP_SELF']?>?image=bullet.gif"></td><td><?=$text['feat1']?></td>
		</tr><tr valign="top">
		<td width="20"><img src="<?=$_SERVER['PHP_SELF']?>?image=bullet.gif"></td><td><?=$text['feat2']?></td>
		</tr><tr valign="top">
		<td width="20"><img src="<?=$_SERVER['PHP_SELF']?>?image=bullet.gif"></td><td><?=$text['feat3']?></td>
		</tr>
		</table>
	</td>
<?
	printNextButton();
//--------------------------- step 1 - end ---------------------------------
} else if ($step == 2) {
//--------------------------- step 2 - begin -------------------------------
printHeader();

	$apidir = @$_SESSION['APIDIR'];
	$dbdir = @$_SESSION['DBDIR'];

?>
	<td colspan="2" valign="top" style="padding: 15px;">
		<?=$text['selectpaths']?><br><br>
		
		<form id="paths" action="<?=$_SERVER['PHP_SELF']?>?step=<?=$nextStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>" method="post">
		<?=$text['apidir']?><br>
		<?=$_SERVER['DOCUMENT_ROOT']?>/ <input type="text" name="apidir" value="<?=$apidir?>" style="width: 200px; vertical-align : middle;"><br>
		<br><?=$text['dbdir']?><br>
		<?=$_SERVER['DOCUMENT_ROOT']?>/ <input type="text" name="dbdir" value="<?=$dbdir?>" style="width: 200px; vertical-align : middle;"><br>
		</form>
	</td>
</tr></table>
<table width="550" height="15" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom" align="right">
		<a href="<?=$_SERVER['PHP_SELF']?>?step=<?=$prevStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['back']?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript: document.getElementById('paths').submit();">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['next']?></b></a>&nbsp;&nbsp;&nbsp;
	</td>
<?
//--------------------------- step 2 - end ---------------------------------
} else if ($step == 3) {
//--------------------------- step 3 - begin -------------------------------	
printHeader();
	
	$apidir = isset($_POST['apidir']) ? $_POST['apidir'] : $_SESSION['APIDIR'];
	$dbdir = isset($_POST['dbdir']) ? $_POST['dbdir'] : $_SESSION['DBDIR'];

	// prepare paths
	$apidir = preg_replace("/^((\.){0,2}\/)*/i", "", $apidir);
	$dbdir = preg_replace("/^((\.){0,2}\/)*/i", "", $dbdir);
	
	$apidir = preg_replace("/(\/(\.){0,2}(\/){0,1})*$/i", "", $apidir);
	$dbdir = preg_replace("/(\/(\.){0,2}(\/){0,1})*$/i", "", $dbdir);

	$apidir = str_replace(array("/../", "/./", "//"), array("/", "/", "/"), $apidir);
	$dbdir = str_replace(array("/../", "/./", "//"), array("/", "/", "/"), $dbdir);

	$_SESSION['APIDIR'] = $apidir;
	$_SESSION['DBDIR'] = $dbdir;
	
	$apidirParts = array($_SERVER['DOCUMENT_ROOT']);
	$dbdirParts = array($_SERVER['DOCUMENT_ROOT']);

	foreach (explode('/', $apidir) as $part) {
		$apidirParts[] = end($apidirParts) . '/' . $part;
	}
	
	foreach (explode('/', $dbdir) as $part) {
		$dbdirParts[] = end($dbdirParts) . '/' . $part;
	}
	
	$apidirFailed = '';
	$dbdirFailed = '';
	
	foreach ($apidirParts as $dir) {
		clearstatcache();
		if (!file_exists($dir)) {
			clearstatcache();
			if(!is_writeable(dirname($dir))) {
				$apidirFailed = dirname($dir);
				break;
			}
			break;
		}
	}
	
	foreach ($dbdirParts as $dir) {
		clearstatcache();
		if (!file_exists($dir)) {
			clearstatcache();
			if(!is_writeable(dirname($dir))) {
				$dbdirFailed = dirname($dir);
				break;
			}
			break;
		}
	}

?>
	<td colspan="2" valign="top" style="padding: 15px;">
		<?=$text['paths_checked']?><br><br>
		<?=$text['apidir']?><br>
		<?
			if ($apidirFailed != '') {
				?><img src="<?=$_SERVER['PHP_SELF']?>?image=notok.gif" align="absmiddle" hspace="5" border="0"><?
			} else {
				?><img src="<?=$_SERVER['PHP_SELF']?>?image=ok.gif" align="absmiddle" hspace="5" border="0"><?			
			}
			echo $_SERVER['DOCUMENT_ROOT']."/".$apidir."<br>";
			if ($apidirFailed != '')
				print "<font color=\"red\">".$text['nopermission'].$apidirFailed."</font>";

		?>
		<br><br>
		<?=$text['dbdir']?><br>
		<?
			if ($dbdirFailed != '') {
				?><img src="<?=$_SERVER['PHP_SELF']?>?image=notok.gif" align="absmiddle" hspace="5" border="0"><?
			} else {
				?><img src="<?=$_SERVER['PHP_SELF']?>?image=ok.gif" align="absmiddle" hspace="5" border="0"><?			
			}
			echo $_SERVER['DOCUMENT_ROOT']."/".$dbdir."<br>";
			if ($dbdirFailed != '')
				print "<font color=\"red\">".$text['nopermission'].$dbdirFailed."</font>";
		?>
		
	</td>
<?
	if ($apidirFailed != '' || $dbdirFailed != '')
		printBackNextButton(1,0);
	else
		printBackNextButton();
//--------------------------- step 3 - end ---------------------------------
} else if ($step == 4) {
//--------------------------- step 4 - begin -------------------------------
printHeader();

	$examples_checked = ($_SESSION['examples'] == 1) ? "checked=\"checked\"" : "";
	$webgui_checked = ($_SESSION['webgui'] == 1) ? "checked=\"checked\"" : "";
	$doc_checked = ($_SESSION['doc'] == 1) ? "checked=\"checked\"" : "";

?>

	<td colspan="2" valign="top" style="padding: 15px;">
		<?=$text['selectoptions']?><br><br>
		<form id="installoptions" action="<?=$_SERVER['PHP_SELF']?>?step=<?=$nextStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>" method="post">
		&nbsp;&nbsp;&nbsp;<input type="checkbox" name="install[]" value="examples" <?=$examples_checked?>>&nbsp;<?=$text['examples']?><br>
		&nbsp;&nbsp;&nbsp;<input type="checkbox" name="install[]" value="webgui" <?=$webgui_checked?>>&nbsp;<?=$text['webgui']?><br>
		&nbsp;&nbsp;&nbsp;<input type="checkbox" name="install[]" value="doc" <?=$doc_checked?>>&nbsp;<?=$text['doc']?><br>
		</form>
	</td>
</tr></table>
<table width="550" height="15" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom" align="right">
		<a href="<?=$_SERVER['PHP_SELF']?>?step=<?=$prevStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['back']?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript: document.getElementById('installoptions').submit();">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['next']?></b></a>&nbsp;&nbsp;&nbsp;
	</td>
<?
//--------------------------- step 4 - end ---------------------------------
} else if ($step == 5) {
//--------------------------- step 5 - begin -------------------------------
printHeader();

	if (isset($_POST['install'])) {
		$install = $_POST['install'];
		$_SESSION['examples'] = in_array('examples', $install) ? 1 : 0;
		$_SESSION['webgui'] = in_array('webgui', $install) ? 1 : 0;
		$_SESSION['doc'] = in_array('doc', $install) ? 1 : 0;
	}
	
	$wgusername = $_SESSION['wgusername'];
	$wgpassword = $_SESSION['wgpassword'];

?>
	<td colspan="2" valign="top" style="padding: 15px;">
		<?=$text['webguilogin']?><br><br>
		
		<form id="paths" action="<?=$_SERVER['PHP_SELF']?>?step=<?=$nextStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>" method="post">
		<table border="0"><tr>
		<td width="80"><?=$text['username']?>:</td>
		<td><input type="text" name="wgusername" value="<?=$wgusername?>" style="width: 150px; vertical-align : middle;"></td>
		</tr><tr>
		<td width="80"><?=$text['password']?>:</td>
		<td><input type="text" name="wgpassword" value="<?=$wgpassword?>" style="width: 150px; vertical-align : middle;"></td>
		</tr></table></form>
	</td>
</tr></table>
<table width="550" height="15" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom" align="right">
		<a href="<?=$_SERVER['PHP_SELF']?>?step=<?=$prevStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['back']?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript: document.getElementById('paths').submit();">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['next']?></b></a>&nbsp;&nbsp;&nbsp;
	</td>
<?
//--------------------------- step 5 - end ---------------------------------
} else if ($step == 6) {
//--------------------------- step 6 - begin -------------------------------
printHeader();

		if (isset($_POST['install'])) {
			$install = $_POST['install'];
			$_SESSION['examples'] = in_array('examples', $install) ? 1 : 0;
			$_SESSION['webgui'] = in_array('webgui', $install) ? 1 : 0;
			$_SESSION['doc'] = in_array('doc', $install) ? 1 : 0;
		}
		
		$_SESSION['wgusername'] = isset($_POST['wgusername']) ? $_POST['wgusername'] : '';
		$_SESSION['wgpassword'] = isset($_POST['wgpassword']) ? $_POST['wgpassword'] : '';
		
			
?>
	<td colspan="2" valign="top" style="padding: 15px;">
	<?=$text['installtext']?>
	</td>
</tr></table>
<table width="550" height="15" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom" align="right">
		<a href="<?=$_SERVER['PHP_SELF']?>?step=<?=$prevStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['back']?></b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="<?=$_SERVER['PHP_SELF']?>?step=<?=$nextStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['install']?></b></a>&nbsp;&nbsp;&nbsp;
	</td>

<?
//--------------------------- step 6 - end ---------------------------------
} else if ($step == 7) {
//--------------------------- step 7 - begin -------------------------------
printHeader();
?>
	<td colspan="2" valign="top" style="padding: 15px;">
<?
		// Here the actual installation begins
		/////////////////////////////////////////
		flush();
		$unzip = new unzipfile("php-txt-db-api-".$_SESSION['version'].".zip");
		$unzip->extract();


		$apidir = @$_SESSION['APIDIR'];
		$dbdir = @$_SESSION['DBDIR'];
		
		$apidirParts = array($_SERVER['DOCUMENT_ROOT']);
		$dbdirParts = array($_SERVER['DOCUMENT_ROOT']);

		foreach (explode('/', $apidir) as $part) {
			$apidirParts[] = end($apidirParts) . '/' . $part;
		}

		foreach (explode('/', $dbdir) as $part) {
			$dbdirParts[] = end($dbdirParts) . '/' . $part;
		}
		
		foreach ($apidirParts as $dir) {
			if (!file_exists($dir))
				mkdir($dir);
		}
		
		foreach ($dbdirParts as $dir) {
			if (!file_exists($dir))
				mkdir($dir);
		}
		
		$exclude = array();
		if ($_SESSION['examples'] == 0) $exclude[] = 'examples';
		if ($_SESSION['webgui'] == 0) $exclude[] = 'webgui';
		if ($_SESSION['doc'] == 0) $exclude[] = 'manual';
		
		$finalApiDir = end($apidirParts);
		$finalDbDir = end($dbdirParts);
		
		// copy all files to the final location
		if (file_exists("php-txt-db-api-".$_SESSION['version']."/php-txt-db-api-".$_SESSION['version']))
			cp("php-txt-db-api-".$_SESSION['version']."/php-txt-db-api-".$_SESSION['version'], $finalApiDir, $exclude);
		else		
			cp("php-txt-db-api-".$_SESSION['version'], $finalApiDir, $exclude);
			
		deldir("php-txt-db-api-".$_SESSION['version']);
		
		// fix directory settings in txt-db-api.php
		if (file_exists($finalApiDir . "/txt-db-api.php")) {
			$content = implode('', file($finalApiDir . "/txt-db-api.php"));
			$content = preg_replace("/^.API_HOME_DIR=[^\n]*\n/m", "\$API_HOME_DIR=\"".$finalApiDir."/\";\r\n", $content);
			$content = preg_replace("/^.DB_DIR=[^\n]*\n/m", "\$DB_DIR=\"".$finalDbDir."/\";\r\n", $content);
			$fp = fopen($finalApiDir."/txt-db-api.php", "w");
			fwrite($fp, $content);
			fclose($fp);
		}
		
		// set entered username/password to webgui config file, if necessary
		if ($_SESSION['webgui'] == 1 && file_exists($finalApiDir . "/webgui/protect.php")) {
			$content = implode('', file($finalApiDir . "/webgui/protect.php"));
			
			$content = preg_replace("/^.user\[0\]\['username'\][^\n]*\n/m", "\$user[0]['username'] = \"".$_SESSION['wgusername']."\";\r\n", $content);
			$content = preg_replace("/^.user\[0\]\['password'\][^\n]*\n/m", "\$user[0]['password'] = \"".$_SESSION['wgpassword']."\";\r\n", $content);
			$fp = fopen($finalApiDir."/webgui/protect.php", "w");
			fwrite($fp, $content);
			fclose($fp);
		}
		
		$examplesLink = "http://".$_SERVER['HTTP_HOST']."/".$apidir."/examples/";
		$webguiLink = "http://".$_SERVER['HTTP_HOST']."/".$apidir."/webgui/";
		$tbeditLink = "http://".$_SERVER['HTTP_HOST']."/".$apidir."/webgui/tbedit.php";
		
		
?>
		<img src="<?=$_SERVER['PHP_SELF']?>?image=ok.gif" align="absmiddle" hspace="5" border="0">
		<?=$text['installcompleted']?><br><br><br>
		<?=$text['removeinstaller']?><br><br><br>
<?		
		if ($_SESSION['examples'] == 1) {
			print $text['exampleslink']."<br>\n";
			print "<b><a href=\"$examplesLink\" target=\"_blank\">$examplesLink</a></b><br><br>\n";
		}
		
		if ($_SESSION['webgui'] == 1) {
			print $text['webguilink']."<br>\n";
			print "<b><a href=\"$webguiLink\" target=\"_blank\">$webguiLink</a></b><br><br>\n";
			print $text['tbeditlink']."<br>\n";
			print "<b><a href=\"$tbeditLink\" target=\"_blank\">$tbeditLink</a></b><br><br>\n";
			
		}
		
?>
	</td>
<?
//--------------------------- step 7 - end ---------------------------------

}



printFooter();

function checkVersion()
{
	$zips = array();
	if ($dh = opendir(".")) { 
		while (($file = readdir($dh)) !== false) { 
			if (preg_match("/php-txt-db-api-(.*)\.zip/i", $file, $matches))
				$zips[] = array($file, $matches[1]); 
		}
		closedir($dh);
	} 
	if (count($zips) == 0) {
		return 0;
	} else {
		$maxVersion = 0;
		$installVersion = '';
		foreach ($zips as $zip) {
			if (preg_match("/([0-9]+)\.([0-9]+)\.([0-9]+)[-]{0,1}(alpha|beta|pre){0,1}[-]{0,1}([0-9]*)/i", $zip[1], $matches)) {
				$version = 10000000*$matches[1] + 100000*$matches[2] + 1000*$matches[3];
				if ($matches[4] == 'pre')
					$version += 100 * 1;
				else if ($matches[4] == 'alpha')
					$version += 100 * 2;
				else if ($matches[4] == 'beta')
					$version += 100 * 3;
				else
					$version += 100 * 9;

				if ($matches[5] != '')
					$version += $matches[5];
				else
					$version += 99;

				if ($version > $maxVersion) {
					$maxVersion = $version;
					$installVersion = $zip[0];
					$_SESSION['version'] = $matches[0];
				}
			}
		}
		return 1;
	}
}

// cp() copies a file or directory
function cp($wf, $wto, $exclude = array())
{
	if (!file_exists($wf))
		return;
	if (!file_exists($wto))
		mkdir($wto);
	$arr = ls_a($wf);
	foreach ($arr as $fn){
		if (in_array($fn, $exclude))
			continue;
		if ($fn) {
			$fl = "$wf/$fn";
			$flto = "$wto/$fn";
			if (is_dir($fl))
				cp($fl, $flto);
			else
				copy($fl, $flto);
		}
	}
}

// ls_a() lists a directory
function ls_a($wh)
{ 
	$files = '';
	if ($handle = opendir($wh)) {
		while (false !== ($file = readdir($handle))) { 
			if ($file != "." && $file != ".." ) { 
				if($files == '')
					$files = "$file";
				else
					$files = "$file\n$files"; 
			} 
		}
		closedir($handle); 
	}
	$arr = explode("\n",$files);
	return $arr;
}

function deldir($dir) {
	$current_dir = opendir($dir);
	while($entryname = readdir($current_dir)) {
		if(($entryname != "." && $entryname != "..") && is_dir("$dir/$entryname") ) {
			deldir("${dir}/${entryname}");
		} elseif ($entryname != "." && $entryname != "..") {
			unlink("${dir}/${entryname}");
		}
	}
	closedir($current_dir);
	rmdir($dir);
}


function checkForImages()
{
	$image = @$_GET["image"];
	
	if ($image == "") return;
	
	if ($image == "background.png") {

		$image = <<<EOF
pngiVBORw0KGgoAAAANSUhEUgAAAAEAAAF8CAIAAACBvEZ1AAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADhSURBVHjavFRBEgMhCEv2/0/aJ/RPqduW
HYTo3npwZBQChOhxvnQAeF767Sz22DmWhk2le6Y4fn0uW2Gn+MBoOUsuao6548aZCkbYSj6tnpqb
833UmrFpMFn7SnhTntoPet23X+W31hB8BFbGrtym3snOM8scGjYWM2DC1Fxv+DZeDE+Z6ycOYfRT
89Bp0+TMM7l9Eo+k0YfLIa/lVpOrX30GKHlj/q43OO53s0XXAI32sdHkh2/XV65FC/1y8e7ymYtd
aA0P2gK85q1+jca1+5uw5uzClOZ3sPzn/rfeAgwATvFG9lEK3vkAAAAASUVORK5CYII=
EOF;
	} else if ($image == "setup.png") {

		$image = <<<EOF
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
EOF;
	} else if ($image == "1x1.gif") {
		
		$image = <<<EOF
gifR0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==
EOF;
	} else if ($image == "bullet.gif") {
		
		$image = <<<EOF
gifR0lGODlhEAAQAPfEAP///////qTB//z9/0+a/5G0///+/lmh/6nE/8HZ/2au/+Tt/0uU/z2J/3mk
/3ql/snd/5qn5fL2/+fu/oSl9DyI/4LC/rbN/SQ3omiN3ZCc3Yyl6WiJ4TiF/j1UsyQznjlWtyQu
l2hyw7HM/36o/2WK3OLu/12p/1yi/vT3/3Oh/3Of/yxQwlqg/xY8uYmq9Bc4sOXt/2mY/3Oe/FuP
/bPO/4my/12o/5jC+2iT9ZvH+////YCc5KG///j6/py7/7zW/1ef/5uo5YDB/Z+9/zx003a6/42b
3D6B/eHq//r8/9Pl///+/8bU+qCu6dDc+09YsYXH/4a/95uu67LL/2OY+lel/keV/j5JqGV1xWm1
/xhAv2Oj+qrG+2GX+k6L4FWB1YSo9zRcvTZQtDh1/Iuv/1SA1TVv8lVuxefv/5mm5A8nnJDI/Mrc
/yo6oilMvPb5/2aJ5Iam86rF/9fl/0SA3Txt1NTl/xg4sIKs+12O9l2R/zRw33qo/16V+n2m/mev
/4id4Wl2wlNnvf79/ni//xtLzoaT1pDJ/WKb5X6K0FCb/3GO2mJ6yzN9/yhXvYPG/zuH/1uf/63G
/zp8/zeF/4Oq/8DU/z+F/6jE/ylNv42n60eC2py9/zxuzPP3/3+X30CL/5Os7anG/0uH/26c/1KF
7C53/l+F5T9ZtoCo/2ih6DF29ou2+424/z5yzbbN/1+A057K/Cpv/Ha+//z8/py8/6bD/4qv/3a7
/1yo/8ba/4Sr/1uT+naf94jB+JWq6nCe/2m2/6Ct6f///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAMQALAAAAAAQABAA
AAj/AIkJFDhMBJYQUNQMXEgsgps3HORQiMMCgwaGimC8WHKnzS4IQHy5EDRQCJ4aJi5lgkXlh4AR
NrYcEvghDJ1JcwQMgIMAV4FONNYQc6IpwS0iZf48AfDJlgNefQwdyYJqFAlLK4LNaAJAQgEZKs4M
8pCjVI8kMdIsmMAEQApVe8iMSaWH1IUdAQIY8AEAgIEHlGaBaGQKExI/Xqr06gKgVp5TjlihmWJH
UoMKkSq5UkKoVYdQDPgEIiaGy4FFBFoMCIDjCoEgKB4JBFbECKAbJ2TpsKJLQa46oAYy4jQkSiFh
WmhBsvAlFsMNnhJJYYPo16pXPBgOFJXBDJgSmxgGAQQAOw==
EOF;
	} else if ($image == "orange.gif") {
		
		$image = <<<EOF
gifR0lGODlhEAAQANU/AP///3ctAP98AP+ULv+2cJlBAP+zav+6eOZ8HP/Diu6eVf/Qo/+uYYg3AP+r
XP/Hkv+ACKpNBf+/gYAyAP+iSv/Ztf/Jlf/KmP+NIf/gw/9/Bf99AqpLAP+cPf/nzuZyCf/OoP/j
yP/o0v+YN6pRDO5/Gf/Mm//w4pE8AP/Nnv/cu/+PJP/Tqf+DDP/Vrv/7+P/8+e6YSf+IF+5zAuZ4
Ff9+A+59Ff+lUOZxB+6LMf+FEf/79/+LHOaHMuaINAAAACH5BAEAAD8ALAAAAAAQABAAQAa2wJ9Q
GEAVCo3AUFggDEYjE2BKdawghR8plFmIpi+TZGzgEGkDyG0KEgggK5xSOPF5TiJVpaICISZCDQ8W
BwQuVFMmDg4DSTk8bhKIDG4tM3M/ARElAx0dAyURmEMBJAogLCwPMaJLAT0nHhkAMCwWFhcpNEoB
CiEPD7NTOywExjYBBRIUzAuIUwYUKwUoDBsbLQnPlBs1DT8RHRoCklMEbhoyZnQfAxgEDDU6GAMf
gEtEDUdJ+EEAOw==
EOF;
	} else if ($image == "notok.gif") {
	
		$image = <<<EOF
gifR0lGODlhEAAQAOZpAP/+/v3x8f3s7P/8/LETE8kVFegjI+ksLLkUFLMTE/i8vPnCwuEYGNkXF+gh
IexJSZMQEMIVFe5ZWbsUFJcZGdQXF6MSEuo1Neo4OJ8REf3v7/eysvi7u7UTE84WFuxKSqoSEqgS
EqElJfrPz/rNzfORkZUQEP7z854lJaEREb4UFPF9ffWenutBQekxMe1TU/J/f/nJye1VVYcXF/vZ
2acSEugkJOo2Ns0WFpwlJdAWFvaqqu1XV/BwcLATE+koKOo6OpMYGOktLaUSEoYODtsXF+kvL/rU
1KwSEvOMjPrS0vvd3fWcnJIkJPTs7OIYGOxOTuQYGO9nZ/Bycu5bW+s+Pus8PP3q6u9iYu1QUPnE
xOxHR/SVlcAVFf74+PSamuozM54REcUVFfjAwJYQEPnGxuXT0+YYGPKBgf///wAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5
BAEAAGkALAAAAAAQABAAAAe+gGmCg4SFhgkOGBg2CIaCKFsbGgMAXmNQIoUUEgtlCp8cCzFTFIQG
LDsBHCVcSTRLGy2DFlRoGgAnTD0jAAArQGSCXTJZMAEAAUe9EhMEJoIRFwcOWAK9Ay81SAkWggkN
FQUGJL0CRikEHRmCGc1FWgAD1gIuIQgQghAdBUq9PGBXkBkgQGSQjwpfBkhRgeBBgAVRQBAKouPH
AwYYG3wQgmNGoRweDli5cKPKgQJNzDgaIubMkwhh0jhxRJNQIAA7
EOF;
	} else if ($image == "ok.gif") {
	
		$image = <<<EOF
gifR0lGODlhEAAQANU4AADpAADKAADPAADTAADbAADVAADdAACuAAaeBgCsAIXOgQjtCKbHplqvWgr1
CnGecT6kPhB3EGi2aEuwS0WsRTemNyqEKgB4AAHAASmfKQC+AFm0WQDgAFSDVADaAGK1Yih4KADx
ABi5GMPUwxvOG1O7Ux+9HxK1EgDuALvjuQD+AACyAADzAEGdQTuuOyF1IQu+CxmnGWGoYQCxAGK2
YsPkwwiWCACDAP///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAADgALAAAAAAQABAAAAZUQJxw
SCwaj8gkLqXYKHG1CgVHyyRLMclQ5DJOSKbiaQEZfjAOmLHBMrRkM4DqcGQEQoMCCmBLIggeHAI3
ShYBAhoRTw8rCS9POCMXIJBCHZWYmUJBADs=
EOF;
	}
	
	$type = substr($image, 0, 3);
	$image = substr($image, 3);
	switch ($type) {
		case "png":
			header("Content-Type: image/png"); break;
		case "jpg":
			header("Content-Type: image/jpeg"); break;
		case "gif":
			header("Content-Type: image/gif"); break;
	}
	header("Content-Disposition: inline");
	echo base64_decode($image);
	exit;
}

function printHeader()
{

?>
<html>
<head>
<style>
body, p, td, tr, table, input { font-family: verdana, arial, helvetica; color: #0B5979; font-size: 12px;}
.title { font-size: 26px; font-weight: bold;}
a { text-decoration: none; color : #0B5979; }
a:hover { color : #0B5979; text-decoration: underline; }
</style>
</head>
<body>
	<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td align="center" valign="middle">
				<div style="margin: 0px; text-align: left; border: solid #0B5979 1px; width: 550px; height: 380px; background-image:url(<?=$_SERVER['PHP_SELF']?>?image=background.png);">
					<table width="550" height="80" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td width="100" height="80" valign="top">
							<img src="<?=$_SERVER['PHP_SELF']?>?image=setup.png">
						</td>
						<td width="450" valign="top">
							<img src="<?=$_SERVER['PHP_SELF']?>?image=1x1.gif" height="30"><br>
							<span class="title">Txt-Db-API Installation</span><br>
							&nbsp;<? if (isset($_SESSION['version']) && $_SESSION['version'] != '') print "Version ".$_SESSION['version']; ?>
						</td>
					</tr>
					</table>
					<table width="550" height="275" cellspacing="0" cellpadding="0" border="0">
					<tr>
<?

}

function printNextButton()
{
	global $text, $lang, $nextStep, $step, $SID;

?>
</tr></table>
<table width="550" height="15" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom" align="right">
		<a href="<?=$_SERVER['PHP_SELF']?>?step=<?=$nextStep?>&laststep=<?=$step?>&lang=<?=$lang?><?=$SID?>">
		<img src="<?=$_SERVER['PHP_SELF']?>?image=orange.gif" align="absmiddle" hspace="5" border="0"><b><?=$text['next']?></b></a>&nbsp;&nbsp;&nbsp;
	</td>
<?

}

function printBackNextButton($backActive = 1, $nextActive = 1)
{
	global $text, $lang, $nextStep, $prevStep, $step, $SID;

?>
</tr></table>
<table width="550" height="15" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom" align="right">
		<?
			if ($backActive) {
				print "<a href=\"".$_SERVER['PHP_SELF']."?step=".$prevStep."&laststep".$step."&lang=".$lang.$SID."\">";
				print "<img src=\"".$_SERVER['PHP_SELF']."?image=orange.gif\" align=\"absmiddle\" hspace=\"5\" border=\"0\"><b>".$text['back']."</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			} else {
				print "<img src=\"".$_SERVER['PHP_SELF']."?image=orange.gif\" align=\"absmiddle\" hspace=\"5\" border=\"0\"><b><font color=\"#CCCCCC\">".$text['back']."</font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			
			if ($nextActive) {
				print "<a href=\"".$_SERVER['PHP_SELF']."?step=".$nextStep."&laststep=".$step."&lang=".$lang.$SID."\">";
				print "<img src=\"".$_SERVER['PHP_SELF']."?image=orange.gif\" align=\"absmiddle\" hspace=\"5\" border=\"0\"><b>".$text['next']."</b></a>&nbsp;&nbsp;&nbsp;";
			} else {
				print "<img src=\"".$_SERVER['PHP_SELF']."?image=orange.gif\" align=\"absmiddle\" hspace=\"5\" border=\"0\"><b><font color=\"#CCCCCC\">".$text['next']."</font></b>&nbsp;&nbsp;&nbsp;";
			}
		?>
	</td>
<?

}

function printFooter()
{

?>
					</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</body>
</html>
<?
}


class unzipfile
{
	var $zipname;
	
	function unzipfile($zipname)
	{
		$this->zipname = $zipname;
	}

	function extract()
	{
		$fp = @fopen($this->zipname, "rb");
		
		@fseek($fp, filesize($this->zipname)-18);
	
		$centralDir = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', @fread($fp, 18));
		
		$pos = $centralDir['offset'];
		
		for ($i = 0, $extracted = 0; $i < $centralDir['entries']; $i++) {
			
			@rewind($fp);
			@fseek($fp, $pos);
			
			$data = unpack('Vid', @fread($fp, 4));
			if ($data['id'] != 0x02014b50)
				return -1;

			$fileHeader = unpack('vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', @fread($fp, 42));
			
			$fileHeader['filename'] = fread($fp, $fileHeader['filename_len']);
			$fileHeader['extra'] = ($fileHeader['extra_len'] > 0) ? fread($fp, $fileHeader['extra_len']) : '';
			$fileHeader['comment'] = ($fileHeader['comment_len'] > 0) ? fread($fp, $fileHeader['comment_len']) : '';
			
			$isDirectory = 0;
			if (substr($fileHeader['filename'], -1) == '/')
				$isDirectory = 1;

			$fileHeader['mtime'] = $this->toUnixTimeStamp($fileHeader['mdate'], $fileHeader['mtime']);
				
			$pos = ftell($fp);
			
			@rewind($fp);
			@fseek($fp, $fileHeader['offset']);
			
			// ----- File entry
			$data = unpack('Vid', @fread($fp, 4));
			if ($data['id'] != 0x04034b50)
				return -1;

			$fileHeader = unpack('vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', @fread($fp, 26));
			
			$fileHeader['filename'] = fread($fp, $fileHeader['filename_len']);
			$fileHeader['extra'] = ($fileHeader['extra_len'] > 0) ? fread($fp, $fileHeader['extra_len']) : '';
			
			$fileHeader['mtime'] = $this->toUnixTimeStamp($fileHeader['mdate'], $fileHeader['mtime']);
						
			// ----- Do the extraction (if not a directory)
			if (!$isDirectory) {
			
				$dirs = explode('/', $fileHeader['filename']);
				array_pop($dirs);
				$path ='';
				
				// ----- Create directories which are necessary to store file
				foreach ($dirs as $dir) {
					$path .= $dir;
					if (!file_exists($path) || !is_dir($path))
						mkdir($path);
						
					$path .= '/';
				}
			
				// ----- Look for not compressed file
				if ($fileHeader['compression'] == 0) {
					if (($destFile = @fopen($fileHeader['filename'], 'wb')) == 0)  
						return -1;

					$fsize = $fileHeader['compressed_size'];
					while ($fsize != 0) {
						$readSize = ($fsize < 2048 ? $fsize : 2048);
						$buffer = fread($fp, $readSize);
						@fwrite($destFile, pack('a'.$readSize, $buffer), $readSize);
						$fsize -= $readSize;
					}

					fclose($destFile);

					touch($fileHeader['filename'], $fileHeader['mtime']);

				} else {

					if (($destFile = @fopen($fileHeader['filename'], 'wb')) == 0)
						return -1;

					$buffer = @fread($fp, $fileHeader['compressed_size']);

					// ----- Decompress the file
					$fileContent = gzinflate($buffer);
					unset($buffer);

					// ----- Write the uncompressed data
					@fwrite($destFile, $fileContent, $fileHeader['size']);
					unset($fileContent);

					@fclose($destFile);

					// ----- Change the file mtime
					touch($fileHeader['filename'], $fileHeader['mtime']);
				}
			}
		}
	}
	
	function toUnixTimeStamp ($mdate, $mtime)
	{
		if ($mdate && $mtime) {
			$hour = ($mtime & 0xF800) >> 11;
			$minute = ($mtime & 0x07E0) >> 5;
			$second = ($mtime & 0x001F) * 2;

			$year = (($mdate & 0xFE00) >> 9) + 1980;
			$month = ($mdate & 0x01E0) >> 5;
			$day = $mdate & 0x001F;

			return mktime($hour, $minute, $second, $month, $day, $year);

		} else
			return time();
	}
}


function initLanguages($lang)
{
	global $text;

	if ($lang == 'de') {
		$text['error'] = "Fehler";
		$text['download'] = "Die Installation kann nicht fortgesetzt werden. Es wurde kein Installations Zipfile im aktuellen Verzeichnis gefunden.<br><br>Bitte laden Sie sich die aktuelle Version der Txt-Db-API von <a href=\"http://www.c-worker.ch\" target=\"_blank\">www.c-worker.ch</a> herunter und kopieren sie das Zipfile in das Verzeichnis, in dem auch dieses Installationsskript liegt. Danach k&ouml;nnen Sie das Installationsskript erneut aufrufen.";  
		$text['welcome'] = "Willkommen zur Installation der Txt-Db-API<br><br>Dieses kleine Setup wird Sie schnell mit ein paar Schritten zum Ziel f&uuml;hren, um danach sofort mit der Txt-Db-API arbeiten zu k&ouml;nnen. Hier nochmal die Hauptmerkmale der Txt-Db-API:<br>";
		$text['feat1'] = "Man hat die M&ouml;glichkeit auf normale Text Files wie eine SQL Datanbank zuzugreifen";
		$text['feat2'] = "Es wird auf dem Webserver kein spezielles Datenbanksystem ben&ouml;tigt, trotzdem kann man SQL nutzen";
		$text['feat3'] = "Mit der API kann man &uuml;ber SQL Tabellen erstellen, abfragen, &auml;ndern, l&ouml;schen, u.s.w., ohne dass man sich selbst mit den Text Files befassen muss";
		$text['next'] = "Weiter";
		$text['back'] = "Zur&uuml;ck";
		$text['selectpaths'] = "W&auml;hlen Sie bitte die Pfade.<br>Der absolute Pfad des Hauptverzeichnisses Ihres Webservers wurde ermittelt und bereits vor den Eingabefeldern ausgegeben. Wenn Sie nicht wissen, was Sie hier eintragen sollen, dann verwenden Sie bitte die vorgegebenen Werte.";
		$text['apidir'] = "Verzeichnis, in das die Dateien der Txt-Db-API abgelegt werden sollen:";
		$text['dbdir'] = "Verzeichnis, in das die Datenbanken angelegt werden sollen:";
		$text['paths_checked'] = "Die eingegebenen Pfade wurden gepr&uuml;ft.<br><br>Zusammenfassung:";
		$text['nopermission'] = "Keine Schreibberechtigung im folgenden Verzeichnis<br>";
		$text['selectoptions'] = "W&auml;hlen Sie bitte die Komponenten aus, die installiert werden sollen.";
		$text['webgui'] = "Webgui &nbsp;<i>(Oberf&auml;che f&uuml;r die Datenbankadministration)</i>";
		$text['examples'] = "Beispiele";
		$text['doc'] = "Dokumentation &nbsp;<i>(Txt-Db-API Schnittstellenbeschreibung)</i>";
		$text['webguilogin'] = "Sie haben die Komponente 'Webgui' selektiert. Hier k&ouml;nnen Sie nun die Daten des Administrators festlegen, der Zugriff auf die Webgui haben soll.<br><br>Bitte w&auml;hlen Sie einen Benutzernamen und ein Passwort."; 
		$text['password'] = "Passwort";
		$text['username'] = "Benutzername";
		$text['installtext'] = "Es wurden alle n&ouml;tigen Informationen eingegeben und die Installation kann nun abgeschlossen werden.<br><br>Dieser Vorgang kann einige Sekunden dauern."; 
		$text['install'] = "Installieren";
		$text['removeinstaller'] = "<b><u><font color=\"red\">WICHTIG</font></u></b>:<br>Sie sollten nun dieses Installationsskript (".basename(@$_SERVER['PHP_SELF']).") von ihrem Webserver entfernen, um einen Missbrauch zu verhindern.";
		$text['exampleslink'] = "Der Link zu den Txt-Db-API Beispielen lautet:";
		$text['webguilink'] = "Der Link zur Webgui lautet:";
		$text['tbeditlink'] = "Der Link zum Table Editor lautet:";
		$text['installcompleted'] = "Die Installation wurde erfolgreich abgeschlossen.";

	} else if ($lang == 'en') { 
		$text['error'] = "Error";
		$text['download'] = "The installation cannot be continued. No installation zip file was found in the current directory.<br><br>Please download the latest version of Txt-Db-API from <a href=\"http://www.c-worker.ch\" target=\"_blank\">www.c-worker.ch</a> and copy the zip file in the same directory which contains this installation script. Then you can call again the installations script.";  
		$text['welcome'] = "Welcome to the installation of Txt-Db-API<br><br>This little setup will guide you quickly with few steps through the installation process, so that you can work with the Txt-Db-API immediately. Here again the main features of Txt-Db-API:<br>";
		$text['feat1'] = "You can access normal text files like a SQL database";
		$text['feat2'] = "You don't need any special database software on the web server, yet you can still use SQL";
		$text['feat3'] = "You can use the API to create, query, change, or delete tables, and you don't have to bother with the Text-Files themse";
		$text['next'] = "Next";
		$text['back'] = "Back";
		$text['selectpaths'] = "Please specify the paths.<br>The absoulte path to the root directory of your webserver was detected and was already printed out in front of the input fields. If you don't know, what to enter here, then please use the pre-defined values.";
		$text['apidir'] = "Directory, where the files of Txt-Db-API will be copied to:";
		$text['dbdir'] = "Directory, where the databases will be stored:";
		$text['paths_checked'] = "The entered paths were verified.<br><br>Result:";
		$text['nopermission'] = "No write access in the following directory:<br>";
		$text['selectoptions'] = "Please select the components which shall be installed.";
		$text['webgui'] = "Webgui &nbsp;<i>(Interface for the database administration)</i>";
		$text['examples'] = "Examples";
		$text['doc'] = "Documentation &nbsp;<i>(Txt-Db-API interface description)</i>";
		$text['webguilogin'] = "You have selected the component 'Webgui'. Below you can now specify the data for the administrator, who then has access to the webgui.<br><br>Please choose an username and a passoword.";
		$text['password'] = "Password";
		$text['username'] = "Username";
		$text['installtext'] = "The required information was entered correctly and the installation can be completed now.<br><br>This process can take a few seconds."; 
		$text['install'] = "Install";
		$text['removeinstaller'] = "<b><u><font color=\"red\">IMPORTANT</font></u></b>:<br>You should now remove this installation script (".basename(@$_SERVER['PHP_SELF']).") from your webserver to avoid any abuse.";
		$text['exampleslink'] = "The link to the Txt-Db-API examples is:";
		$text['webguilink'] = "The link to the Webgui is:";
		$text['tbeditlink'] = "The link to the Table Editor is:";
		$text['installcompleted'] = "The installation has been completed successfully.";
	}
}

?>