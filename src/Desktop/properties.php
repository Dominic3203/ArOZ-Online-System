<?php
include '../auth.php';
if (isset($_GET['filename']) && $_GET['filename'] != ""){
	if (file_exists("files/" . $_GET['filename'])){
		$filename = "files/" .  $_GET['filename'];
	}else{
		die("ERROR. Filename not found");
	}
}else{
	die("ERROR. Undefined filename given");
}

function filesize_formatted($path)
{
    $size = filesize($path);
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function getHumanReadableSize($size){
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

function getIcon($path){
	if (is_dir($path)){
		return "folder open outline";
	}
	$fullmime = mime_content_type($path);
	$mime = explode("/",$fullmime)[0];
	$detail = explode("/",$fullmime)[1];
	if ($mime == "audio"){
		return "file audio outline";
	}else if ($mime == "video"){
		return "file video outline";
	}else if ($detail == "x-php"){
		return "file code outline";
	}else if ($mime == "text"){
		return "file text outline";
	}else if ($mime == "model"){
		return "cube";
	}else if ($mime == "image"){
		return "file image outline";
	}else if ($mime == "directory"){
		return "folder open";
	}else if ($detail == "zip"){
		return "file archive outline";
	}else if ($detail == "javascript"){
		return "file code outline";
	}else if ($detail == "json"){
		return "file code outline";
	}else if ($detail == "pdf"){
		return "file pdf outline";
	}else if ($mime == "application"){
		return "file outline";
	}
	
}

function getDecodeFileName($filename){
	if (strpos($filename,"inith") !== false){
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$filenameOnly = str_replace("." . $ext,"",$filename);
		$hexname = substr($filenameOnly,5);
		if (ctype_xdigit($hexname) && strlen($hexname) % 2 == 0) {
			$originalName = hex2bin($hexname);
			return $originalName . "." . $ext;
		} else {
			//This is not an encoded filename but just so luckly that is start with inith
			return $filename;
		}
		
	}else if (ctype_xdigit($filename) && strlen($filename) % 2 == 0) {
		//This is a folder encriped in hex filename format
		return hex2bin($filename);
	}else{
		return $filename;
	}
}

function GetDirectorySize($path){
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}

$ext = pathinfo($filename, PATHINFO_EXTENSION);
$fileType = "file";
if ($ext == "shortcut"){
	//This is a shortcut
	$fileType = "shortcut";
}elseif(is_dir($filename)){
	//This is a folder
	$fileType = "folder";
}
//Or otherwise it is a file
?>
<html>
<head>
	<link rel="stylesheet" href="../script/tocas/tocas.css">
	<script src="../script/tocas/tocas.js"></script>
	<script src="script/jquery-1.12.4.js"></script>
	<style>
	    .spcialPadding{
	        padding-top:20px;
	        padding-left:10px;
	    }
	</style>
</head>
<body style="background-color:#f0f0f0;font-size:80%;overflow-y:hidden;">
	<!-- <div class="ts top attached tabbed menu">
		<a class="active item">Summary</a>
	</div> -->
	<div class="ts active bottom attached tab segment" style="height:100%;">
			<div class="ts grid">
				<div class="four wide column"><i class="huge <?php echo getIcon($filename);?> icon spcialPadding"></i></div>
				<div class="twelve wide column">
					<div class="ts small header" style="word-wrap: break-word !important;">
					<div class="ts vertical fluid inputs">
					<div class="ts borderless input">
						<input type="text" value="<?php echo getDecodeFileName(basename($filename));?>" readonly>
					</div>
					<div class="ts borderless input">
						<input type="text" value="<?php echo basename($filename);?>" readonly>
					</div>
					</div>
						<div class="sub header"><?php 
						if ($fileType == "file"){
							echo "." . $ext . " file (".mime_content_type($filename).")";
						}elseif ($fileType == "shortcut"){
							echo "." . $ext . " file (shortcut/aroz)";
						}elseif ($fileType == "folder"){
							echo "directory (directory/aroz)";
						}?></div>
					</div>
				</div>
			</div>
			<div class="ts divider"></div>
			<table class="ts table">
			<tbody>
				<tr>
					<td><?php
					if ($fileType == "file"){
						echo 'Opens with';
					}elseif ($fileType == "shortcut"){
						echo 'Target';
					}else{
					    echo 'ArOZ Online File System Object';
					}
					?></td>
					<td id="openOrTarget"><?php
					if ($fileType == "file"){
						if (file_exists("../SystemAOB/functions/file_system/default/" . $ext . ".csv")){
							$line = fgetcsv(fopen("../SystemAOB/functions/file_system/default/" . $ext . ".csv","r"));
							echo $line[0];
						}else{
							echo "N/A";
						}
					}elseif($fileType == "shortcut"){
						$content = file_get_contents($filename);
						$modulename = explode(PHP_EOL,$content)[2];
						echo $modulename;
					}
					?><br></td>
				</tr>
				<?php
				if ($fileType == "file"){
				echo "<tr>
					<td></td>
					<td><button class='ts tiny basic button' onClick='changeOpenApps();'>Change</button></td>
				</tr>";
				}
				?>
				<tr>
					<td><?php echo ($fileType=="shortcut")? "Shortcut " : "";?>Location</td>
					<td><div class="ts borderless tiny fluid input"><input type="text" value="<?php echo realpath($filename);?>" readonly></div></td>
				</tr>
				<tr>
					<td><?php echo ($fileType=="shortcut")? "Shortcut " : "";?>Direct Access Path</td>
					<td><div class="ts borderless tiny fluid input"><input type="text" value="<?php echo str_replace(str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']),"http://$_SERVER[HTTP_HOST]",str_replace("\\","/",realpath($filename)));
					?>" readonly></div></td>
				</tr>
				<?php
					if ($fileType == "shortcut"){
						if (file_exists("../" . explode(PHP_EOL,$content)[2])){
							//If this is a module with Path specification over the root of AOB
							//e.g. <ArOZ Online Root>/Audio
							$realPath = realpath("../" . explode(PHP_EOL,$content)[2]);
						}elseif (file_exists(explode(PHP_EOL,$content)[2])){
							//Just in case there are someone trying to access external storage from here
							//e.g. /media/storage1/module
							$realPath = realpath(explode(PHP_EOL,$content)[2]);
						}else if (strpos(explode(PHP_EOL,$content)[2],"https://") !== false || strpos(explode(PHP_EOL,$content)[2],"http://") !== false){
							$realPath = explode(PHP_EOL,$content)[2];
						}else{
							$realPath = "This shortcut might point to a directory no longer exists.";
						}
						echo '<tr>
					<td>Starting Location</td>
					<td><div class="ts borderless tiny fluid input"><input type="text" value="'.$realPath.'" readonly></div></td>
				</tr>';
					}
				?>
				<tr>
					<td>Size</td>
					<td><?php 
					if ($fileType == "folder"){
						echo getHumanReadableSize(GetDirectorySize($filename));
					}else{
						echo filesize_formatted($filename);
					};
					
					?></td>
				</tr>
				<tr>
					<td>Date Modified</td>
					<td><?php echo date("F d Y H:i:s", filemtime($filename));?></td>
				</tr>
				<tr>
					<td>Owner</td>
					<td><?php echo str_replace("files/","",dirname($filename));?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<script>
	    var ext = "<?php echo $ext;?>";
    	var VDI = !(!parent.isFunctionBar);
    	if (VDI){
            windowID = $(window.frameElement).parent().attr("id");
            parent.setWindowFixedSize(windowID + "");
            parent.setGlassEffectMode(windowID + "");
    	}
	    
	    function changeOpenApps(){
	        if (VDI){
	            windowID = $(window.frameElement).parent().attr("id");
	            parent.newEmbededWindow("SystemAOB/functions/file_system/openWith.php?ext=" + ext,"Change default opener",undefined,new Date().getTime(),365,575,window.screen.availWidth/2 - 180, window.screen.availHeight/2 - 387 + 30,0,1,windowID,"updateOpenWithModuleDisplay");
	        }else{
	            //Open the select with windows on a new tab
	            window.open("../SystemAOB/functions/file_system/openWith.php?ext=" + ext);
	        }
	    }
	    
	    function updateOpenWithModuleDisplay(data){
	        //Update the openWith module name
	        object = (JSON.parse(data));
	        var newModuleName = object.newModule;
	        $("#openOrTarget").text(newModuleName);
	        
	    }
	</script>
</body>
</html>