<?
include_once("mysql.php");
include_once("phpFns.class.php");

/* ---------------------------------------------------------------------------------------------------*/
class formMngr {
	var $m_nItemId = -1;

/* ---------------------------------------------------------------------------------------------------*/
function buildMainForm($p_sTableName = 'shit', $p_nItemId = -1){
	
	$this->m_nItemId = $p_nItemId;	
	global $db;
	global $fns;
		
	if($p_nItemId < 0){  // if this is a negative item id -- we're inserting
		$p_nItemId =  $fns->retrnAutoIncrement($p_sTableName);
		//echo "yo: ". $p_nItemId;
		/**/
	} else {
		$q = "SELECT * FROM ".$p_sTableName." WHERE id = ".$p_nItemId;
		$info = $db->get_results($q, ARRAY_A);
	}
	
	
	
	$p_nItemId 		=	$info[0]['id'];
	$p_sTitle	 	=	stripslashes($info[0]['title']);
	$p_sCopy		=	stripslashes($info[0]['copy']);
	$p_sImage		=	$info[0]['image']; 
	$p_sThumb		=	$info[0]['thumb'];
	$p_sType		=	$info[0]['type'];
	//if($p_nItemId < 1){  // if this is a negative item id -- we're inserting
	//	$p_nItemId =  $fns->retrnAutoIncrement($p_sTableName);
	//}
	if($p_nItemId <= 0 ){ 
		$next_id = $fns->retrnAutoIncrement("shit"); 
	}  else { 
		$next_id = $p_nItemId;
	}
	
	
	?>	
	
		
		<div id="formContainer">
		<form id="itemJawn" name="itemJawn" action="handlePost.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="p_sTableName" value="<? echo $p_sTableName; ?>" />
		<input type="hidden" name="p_nItemId" value="<? echo $p_nItemId; ?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000">
	
	
	<? 
	//These items are unused, but could be good for later on.
	
	
	if($p_sTableName == "shit"){  ?>
		<div class="row">
			<span class="label">Title</span>
			<span class="former"><input type="text" name="title" value="<? echo $p_sTitle; ?>" /></span>
		</div>

		<div class="row">
			<span class="label">Copy</span>
			<span class="former"><textarea name="copy"><? echo $p_sCopy; ?></textarea><? echo  $this->txtFormat(); ?></span>
		</div>
		
	
		<div class="row">
			<span class="label">Type</span>
			<span class="former">
				<select name="type">
					<? if($p_sType == 'home'){ $s = 'selected'; } else { $s = ''; } ?><option value="home" <? echo $s; ?>>Homepage</option>
					<? if($p_sType == 'info'){ $s = 'selected'; } else { $s = ''; } ?><option value="info" <? echo $s; ?>>Info</option>
					<? if($p_sType == 'work'){ $s = 'selected'; } else { $s = ''; } ?><option value="work" <? echo $s; ?>>Work</option>
					<? if($p_sType == 'photos'){ $s = 'selected'; } else { $s = ''; } ?><option value="photos" <? echo $s; ?>>Photography</option>
					<? if($p_sType == 'video'){ $s = 'selected'; } else { $s = ''; } ?><option value="video" <? echo $s; ?>>Video</option>
					<? if($p_sType == 'friends'){ $s = 'selected'; } else { $s = ''; } ?><option value="friends" <? echo $s; ?>>Friends</option>
				</select>
			</span>
		</div>		

		<div class="row">
				<span class="label">Image<br/><i style="font-size:9px;">you choose your own size!</i></span>
				<span class="former"><?  $p_image = $fns->checkFile($p_sTableName, $next_id); ?></span>
		</div>
		
		<div class="row">
				<span class="label">thumb<br/><i style="font-size:9px;">square thumbs</i></span>
				<span class="former"><?  $p_image = $fns->checkFile($p_sTableName, $next_id, true); ?></span>
		</div>	
		
	<?	}  ?>		

		<div class="row">
			<span class="label">&nbsp;</span>
			<span class="former"><input type="submit" name="submit" value="SUBMIT" class="submit" /></span>
		</div>
		
		</form>		
		
		</div>
		
		
		
		<?

}
/* ---------------------------------------------------------------------------------------------------*/


function handleSubmit($p_sTableName, $p_nItemId = -1){
	global $db;
	global $fns;
	
	//echo $p_sTableName;
	
	if($p_nItemId > 0){
		//update 
		switch ($p_sTableName){
			case 'shit':	
				$q = "UPDATE shit SET ";
				$q.= "type = '".$_POST['type']."', ";
				$q.= "title = '".addslashes($_POST['title'])."', ";
				$q.= "copy = '".addslashes($_POST['copy'])."' ";
				if($_FILES['image']['name'] == ''){ 
				} else {
					$img = $fns->handle_upload('image', $next_id);
					$q.= ", image = '".$img."' ";			
				}
				if($_FILES['thumb']['name'] == ''){ 
				} else {
					$thumb = $fns->handle_upload('thumb', $next_id);
					$q.= ", thumb = '".$thumb."' ";			
				}
			//	$q.= ', NOW()';
			break;
		}
		
   		 $q.= "WHERE id = ". $_POST['p_nItemId'];
   		 
   		 
	} else {
		
		$next_id = $fns->retrnAutoIncrement($p_sTableName);
	
		//insert
		switch ($p_sTableName){
			case 'shit':	
				$q = "INSERT INTO shit (type, title, copy, image, thumb, date) VALUES ";
				$q.= "( '".$_POST['type']."', ";
				$q.= "'".addslashes($_POST['title'])."', ";
				$q.= " '".addslashes($_POST['copy'])."', ";
				
				if($_FILES['image']['name'] == ''){ 
					$img = ''; 
				} else {
					$img = $fns->handle_upload('image', $next_id);
				}
				$q.= "'".$img."', ";
				
				if($_FILES['thumb']['name'] == ''){ 
					$thumb = ''; 
				} else {
					$thumb = $fns->handle_upload('thumb', $next_id);
				}
				$q.= "'".$thumb."', NOW() )";
			break;
		}
	}

	

	//echo "query is: ".$q;
	$result = $db->query($q);

	//echo "result is: ". $result;
}

/* ---------------------------------------------------------------------------------------------------*/


/* ---------------------------------------------------------------------------------------------------*/

function txtFormat() { ?>

<p> <a href="javaScript:textEdit('addBold');"> bold</a> | <a href="javaScript:textEdit('addItalic');"> italicize </a> | <a href="javaScript:textEdit('hhh');"> header</a>  | <a href="javaScript:textEdit('lineBreak');">line break</a>  |  <a href="javaScript:textEdit('ppp');"> new paragraph</a> | <a href="javaScript:Lister('orderList');"> orderered list</a> | <a href="javaScript:textEdit('addLink');"> add link</a> | <a href="javaScript:textEdit('addMaillink');">link to email</a></p>

<? }
/* ---------------------------------------------------------------------------------------------------*/
} // end class
/* ---------------------------------------------------------------------------------------------------*/
$forms = new formMngr(); 

?>
<? /*

		<div class="row">
			<span class="label">Copy</span>
			<span class="former"><textarea name="copy"><? echo $p_sCopy; ?></textarea><? echo  $this->txtFormat(); ?></span>
		</div>

	<div class="row">
	<span class="label">Category</span>
	<span class="former">
		<select name="cat">
		<? if($p_cat == 'galleries'){ $s = 'selected'; } else { $s = ''; } ?><option value="galleries" <? echo $s; ?>>galleries</option>
		<? if($p_cat == 'friends'){ $s = 'selected'; } else { $s = ''; } ?><option value="friends" <? echo $s; ?>>friends</option>

		</select>
	</span>
</div>
<div class="row">
	<span class="label">Year</span>
	<span class="former">
		<select name="year">
		<? if($p_year == '2006'){ $s = 'selected'; } else { $s = ''; } ?><option value="2006" <? echo $s; ?>>2006</option>
		<? if($p_year == '2005'){ $s = 'selected'; } else { $s = ''; } ?><option value="2005" <? echo $s; ?>>2005</option>
		<? if($p_year == '2004'){ $s = 'selected'; } else { $s = ''; } ?><option value="2004" <? echo $s; ?>>2004</option>
		<? if($p_year == '2003'){ $s = 'selected'; } else { $s = ''; } ?><option value="2003" <? echo $s; ?>>2003</option>	
		</select>
	</span>
</div>
*/ ?>
