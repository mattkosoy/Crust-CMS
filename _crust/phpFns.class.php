<? 
include_once("mysql.php");

/*-------------------------------------------------------------------------*/
class phpFns{

/*-------------------------------------------------------------------------*/
function countRows ($p_sTableName, $p_sParentTable, $p_nParentId){
	global $db;
	
	
	if($p_sTableName == "comments"){ 
		$q = 'SELECT * FROM comments WHERE id_text = '. $p_nParentId;
	} else {
		$q = 'SELECT * FROM '.$p_sTableName." WHERE  other_tablename = '" .$p_sParentTable. "' and id_other = ".$p_nParentId;
	}
	
	$res = $db->get_results($q, ARRAY_A);
	
	$total = count($res);
	
	return $total;
	
}



/*-------------------------------------------------------------------------*/
function getColVal($p_sTableName = '', $p_sField = '', $p_nItemId = -1){
	global $db;
	
	if($p_nItemId < 0){
		return;
	} else {
		$res = $db->get_row("SELECT ".$p_sField." FROM ".$p_sTableName." WHERE id = ".$p_nItemId); 
		$rowVal = $res->$p_sField;
		return $rowVal;
	}

}

/*-------------------------------------------------------------------------*/
function checkFile($p_sTableName = '', $p_nItemId = -1, $type = false){ // checks a row in a table to see if a filename exists
	global $db;
	
	/*echo "item id is: ". $p_nItemId;
	echo "<br/>side piece flag: ".$sidepiece;
	echo "<br/>Table name: ".$p_sTableName;*/
	
	if ($type == false){ 
		$type = "image";
	} else {
		$type = "thumb";
	}
	
	
	$other = $this->getColVal($p_sTableName, "type", $p_nItemId);
		
	
	if($p_nItemId <= 0){
		echo '<input type="file" name="'.$type.'" />';
		return;
	} else {
		$img = $this->getColVal($p_sTableName, $type, $p_nItemId);
		
		//echo "img is: ". $img;
		
		if(!$img){
		
			if($sidepiece){
			//echo 'yo';
			} else {
			echo '<input type="file" name="'.$type.'" />';
			}
		} else {
			//echo $type;
				if($type == "pdf" ){ 
					echo '<b>'.$img.'</b> - <a href="delete_img.php?table='.$p_sTableName.'&itemId='.$p_nItemId.'&img='.$img.'&field='.$type.'&parentTable='.$other.'">Delete</a>';
				} else {
				echo '<img src="../uploads/'.$img.'"  style="float:left; width: 100px;" /><br/><a href="delete_img.php?table='.$p_sTableName.'&itemId='.$p_nItemId.'&img='.$img.'&field='.$type.'&parentTable='.$other.'">Delete</a>';
				}
		}
	}

}


/*-------------------------------------------------------------------------*/
	function gen_order_dropdown($p_tablename, $p_primarykey, $p_itemid, $p_current_order, $p_parentTable, $p_parentId)
	{
/*	echo "table name: ". $p_tablename."<br/>";
	echo "primary key: ". $p_primarykey."<br/>";
	echo "item id: ". $p_itemid."<br/>";
	echo "current order: ".$p_current_order."<br/>";
	echo "parent table: ".$p_parentTable."<br/>";
	echo "parent id: ".$p_parentId."<br/>";  */
		
		global $db;
		
	
		if($p_tablename == "photos" || $p_tablename == "videos"){ 
			$query =	"SELECT * FROM ". $p_tablename . " WHERE other_tablename = '".$p_parentTable."' AND id_other = ".$p_parentId;
		} else {
			$query =	"SELECT * FROM ".$p_tablename;	
		}
		
		$get_items = $db->get_results($query, ARRAY_A);
		
		$p_itemCount = count($get_items);
	
	
		$ret_html .= '<form name="form' .$p_itemid. '" method="post" >';
   		$ret_html .= '<input type="hidden" id="origorder" value="'.$p_current_order.'" />';
   		$ret_html .= '<input type="hidden" id="parentId" value="'.$p_parentId.'" />';
   		$ret_html .= '<input type="hidden" id="posturl" value="';		
		$ret_html .= "updateOrder.php?";															
		$ret_html .= "action=" . $p_tablename;
		$ret_html .=  "&". $p_primarykey ."=" . $p_itemid;
		$ret_html .= '&origorder=' . $p_current_order;
		$ret_html .= '&parentTable=' . $p_parentTable;
		$ret_html .= '&p_parentId=' . $p_parentId;
		$ret_html .= '" />';   

   		$ret_html .= '<select name="position" onchange="changeOrder(this.form, '. "'" .$p_tablename. "', ". "'" .$p_primarykey. "'".');">';
   		
   		for ($temp_order=1; $temp_order<=$p_itemCount; $temp_order++) {
			$ret_html .= '<option value="' . $temp_order .'"';
			if ($temp_order == $p_current_order) 
				$ret_html .= ' SELECTED';
			$ret_html .= '> '. $temp_order .'</option>';
		}
		$ret_html .= '</select>';
		
		
		
		
		if($p_tablename == "slide_show"){
//		$ret_html .= '<br/><br/><input type="submit" name="submit" value="update" class="submit" style="width:60px;"/>';
		}
		
		$ret_html .= '</form>';
		
		//echo "<p>html string to return: ".$ret_html."</p>";

		
		return $ret_html;
	}
/*------------------------------------------------------------------------------------------------*/
	function getRow($p_tableName, $p_itemId){
		global $db;
		$p_Row = $db->get_row("SELECT * FROM $p_tableName WHERE id = $p_itemId");
		return $p_Row;
	}
/*---------------------------------------------------------------------------*/
	function returnRowName($p_tableName, $p_itemId){
		global $db;
		$p_Field = "title";
		$p_Row = $db->get_row("SELECT $p_Field FROM $p_tableName WHERE id = $p_itemId");
		return $p_Row->$p_Field;
	}

/*---------------------------------------------------------------------------*/

	function updateDisplayOrders($p_tableName, $p_compareField, $p_compareValue){
	global $db;
	
	
	
	if($p_tableName == "video" || $p_tableName == "photos"){ 
		$updateD_query = "UPDATE ".$p_tableName." SET display_order = display_order +1 WHERE other_tablename = '". $p_compareField ."' AND id_other = ". $p_compareValue;
	} else {
		$updateD_query = "UPDATE ".$p_tableName." SET display_order = display_order +1";
	}
	
	//echo $updateD_query;

	$p_updateDispOrders = $db->query($updateD_query);
	return $p_updateDispOrders;
	}



/*------------------------------------------------------------------------------------------------*/
/* FUNCTION TO HANDLE DELETE WITH DISPLAY ORDER */
//
//
	function deleter($p_tableName, $p_controlName, $p_controlValue, $p_itemId, $p_currentDisplayOrder){
	// Adjust the orderr of all the other rows after this one
	global $db;
		
	$qDispOrder = "SELECT display_order FROM " . $p_tableName. " WHERE  id = " . $p_itemId; 
	
	if($p_tableName == "photos" || $p_tableName == "video"){ 
	
		$var = $db->get_var("SELECT count(display_order) FROM " . $p_tableName. " WHERE  id_other = " . $p_controlValue ." AND other_tablename = '".$p_controlName."'");
	
	} else {
		$var = $db->get_var("SELECT count(display_order) FROM " . $p_tableName);

	}
	//echo "number of rows: ".$var;
	
	/* $q1 = $db->get_results($qDispOrder, ARRAY_A);
	//print $qDispOrder; */
	if ($var != 1)
	{ 
		$result = $db->get_results($qDispOrder, ARRAY_A);
		 $deletedDispOrder = $result[0]['display_order'];
		
	/*	$deletedDispOrder = $result[0]; */
		$qUpdateDispOrders = "UPDATE " . $p_tableName . " SET display_order=display_order-1 WHERE";
		if($p_tableName == "photos" || $p_tableName == "video"){
		$qUpdateDispOrders.= " other_tablename = '". $p_controlName ."' AND id_other = " . $p_controlValue . " AND ";
		}
		$qUpdateDispOrders .= " display_order > " . $deletedDispOrder;
		//print '<BR><BR> ' . $qUpdateDispOrders; 
		$q2 = $db->query($qUpdateDispOrders);
		//echo $q2; 
	} 
	$q3 = $db->query("DELETE FROM " . $p_tableName . " WHERE  id ='" . $p_itemId . "'");
	//echo $q3; 
	
	header ('Location: index.php?admin='.$p_controlName.'&itemId='.$p_controlValue);
	
	}
/*------------------------------------------------------------------------------------------------*/

function DeleteNoDisplay($p_tableName, $p_itemId){
	global $db; 
	$q3 = $db->query("DELETE FROM " . $p_tableName . " WHERE  id ='" . $p_itemId . "'");
	
}
/*------------------------------------------------------------------------------------------------*/

function handle_upload($p_fieldName, $publico = false){
// secure fileuploading by haveboard@space1026.com //


//print_r($_FILES);

/////////////////////////////////////////////////////////////////////////////////
/////////////////////////FILE UPLOAD/////////////////////////////////////////////	
if($p_fieldName == "" || $_FILES[$p_fieldName]['name'] == ''){ return; } else { 	
	///////MIME CHECK
	
	//echo $p_fieldName;
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////SET VARIABLES TO PROCESS IMAGE, CURRENT MIME, CURRENT MEXTENSIONIME, ALLOWED MIME , ALLOWED EXTENSIONS
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
		$mime_type = $_FILES[$p_fieldName]['type'];  //echo $mime_type;
		$allowed_extensions = array( 'jpeg', 'jpg', 'gif');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
		$posted_file_info = pathinfo($_FILES[$p_fieldName]['name']);
		$posted_file_ext  = $posted_file_info['extension'];

//////////////////////////////
////// IS IT ACTUALLY AN IMAGE
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
			
			$size = getimagesize($_FILES[$p_fieldName]['tmp_name']);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
			$fp=fopen($_FILES[$p_fieldName]['tmp_name'], "rb");
			if ($size && $fp) {
				//YES, IT'S A TRUE IMAGE/////////////////////////////////////////////////////////////////////////////
				
				} else {
				//NOT AN IMAGE
				 ?><h2>Problem checking if this is a real file.  Through size & fopen check.</h2>
				<p>Image files must be a '.gif' or a '.jpg' and have a file extension in the name of the file ('image.jpg' instead of 'image').</p>
				<p><a href="javascript: history.go(-1)">Please Try Again</a></p><?
				 //stop processing
   				exit;
			}
			 fclose($fp);
////// IS IT ACTUALLY AN IMAGE END
//////////////////////////////////

///////////////////////////////////////////////////////////
//SET NAME TO A CLEAN NAME ALONG WITH EXTENSIONS AND COUNTS
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
		$firstfilename = $this->makeUrlFriendly($_FILES[$p_fieldName]['name']);
		$firstfilecount = strlen($firstfilename);
		$fileextcount = strlen($posted_file_ext);

	if($choplength > $firstfilecount){
			$choplength = '0';
			$chopstart = '0';}
		else {
			$choplength = $fileextcount;
			$chopstart = $firstfilecount - $choplength;
			}
		$truebaseset = substr($firstfilename, 0, $chopstart);
		$truebase = $truebaseset;
			if(!$truebase){
				//NOT AN IMAGE
				 ?><h2>I'm Sorry, there was a problem that occured while trying to upload the image.</h2>
				<p>Please rename your file using only letters(a through z), numbers(1 through 9) and underscores(_).</p>
				<p>Image files also must be a '.gif' or a '.jpg' and have a file extension in the name of the file ('image.jpg' instead of 'image').</p>
				<p><a href="javascript: history.go(-1)">Please Try Again</a></p><?
				 //stop processing
   				exit;
   				}
			
//SET NAME TO A CLEAN NAME ALONG WITH EXTENSIONS AND COUNTS
///////////////////////////////////////////////////////////


/////////////////////////////////////////////////////
////SPIT OUT RESULTS FOR TESTING PURPOSES
//echo '<hr />';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
//echo $imagefile;
//echo '<br />';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
//echo $HTTP_POST_FILES[$p_fieldName]['name'];
//echo '<br />';
//echo 'FILE NAME:'.$firstfilename.'<br />';
//echo 'FILE NAME LENGTH:'.$firstfilecount.'<br />';
//echo 'FILE EXT LENGTH:'.$fileextcount.'<br />';
//echo 'FILE CHOP LENGTH:'.$choplength.'<br />';
//echo 'FILE CHOP START:'.$chopstart.'<br />';
//echo 'FILE TRUE BASE:'.$truebase.'<br />';
//echo 'MIME:'.$mime_type.'<br />';
//echo 'EXT:'.$posted_file_ext.'<br />';


////SPIT OUT RESULTS FOR TESTING PURPOSES
/////////////////////////////////////////


////////////////////////////////////////////
////make sure the image is ONLY a gif or jpg

	if ( !$posted_file_ext OR !in_array($posted_file_ext, $allowed_extensions) ) {
   		 //this extension is invalid, display a message here and don't continue upload
		echo '<h2>The file you attempted to upload does not have the appropriate file extensions \'.jpg\' or \'.gif\'.</h2><p>Please rename your '.$posted_file_ext.' file with it\'s appropriate file extension(.jpg or .gif)</p><p>Image files must be a \'.gif\' or a \'.jpg\' and have a file extension in the name of the file (\'image.jpg\' instead of \'image\').</p><p><a href="javascript: history.go(-1)">Try Again</a></p>';
		exit;
			}
////END make sure the image is ONLY a gif or jpg
////////////////////////////////////////////////

			$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/uploads/';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
			$uploadfile = $uploaddir.basename($_FILES[$p_fieldName]['name']);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
				if (move_uploaded_file($_FILES[$p_fieldName]['tmp_name'], $uploadfile)) {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT FIELD
						$img = basename($_FILES[$p_fieldName]['name']);
						
						
						
						//file name cannot be more than 31 characters, so chop at 27 + 4 for extension
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////EDIT EXTENSION
					$newbase = $truebase;
					$chkstrnglngthforbase = strlen($newbase);
					$maxflenth = "27";
						if($chkstrnglngthforbase > $maxflenth)
							{
							$finalbase = substr($newbase, 0, $maxflenth);
							}
							else
							{
							$finalbase = $newbase;
							}				
						$nimg =  $finalbase.'.'.$posted_file_ext;
						
						rename ("../uploads/".$img, "../uploads/".$nimg);
							if ( substr($uploaddir, 0, -1) != '/' ) { $uploaddir .= '/'; }
							$fullpath = $uploaddir.$nimg;
							chmod ( $fullpath, 0755 );
			
					
						return $nimg;
			
				 	} else {
				 		?><h2>I'm Sorry, there was an unknown problem that occured while trying to upload the image</h2>
				 		<p>Image files must be a '.gif' or a '.jpg' and have a file extension in the name of the file ('image.jpg' instead of 'image').</p>
				 		<p><a href="javascript:history.go(-1)">Please Try Again</a></p><?
				 	exit;
				}	
		
		}
/////////////////////END FILE UPLOAD/////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
}

/* -------------------------------------------------------------------------------- */



function handle_pdf($p_fieldName, $publico = false){

	
	
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
	

	//copy ($_FILES['imagefile']['tmp_name'], "../site_images/".$_FILES['imagefile']['name']) or die ("could not copy");
	if(!$_FILES[$p_fieldName]){ $img = "";  } else { 	
		$uploadfile = $uploaddir.basename($_FILES[$p_fieldName]['name']);
		if (move_uploaded_file($_FILES[$p_fieldName]['tmp_name'], $uploadfile)) {
			$img = basename($_FILES[$p_fieldName]['name']);
		
		} else { }	
	} 	
	
	//copy ($_FILES[$p_fieldName]['tmp_name'], "../site_images/".$_FILES[$p_fieldName]['name']) or die ("could not copy $p_fieldName");

	$img = $_FILES[$p_fieldName]['name'];

	if($publico){	
		$publico_img = 'publicosplash.jpg';
		rename($uploaddir.$img, $uploaddir.$publico_img);
		$img = $publico_img;

	}

	chmod($uploaddir.$img, 0755);
			
	return $img;	

} 

/* -------------------------------------------------------------------------------- */
function retrnAutoIncrement($p_sTablename){
global $db;

//echo "table name ". $p_sTablename."<br/>";

$q = "SHOW TABLE STATUS LIKE '". $p_sTablename ."'";

//echo "query: ".$q." <br/>";

$res = $db->get_results($q, ARRAY_A);


//print_r($res);

$an = $res[0]['Auto_increment'];

//echo $an;
return $an;
}

/* -------------------------------------------------------------------------------- */
function genTypeSelect($p_sParentTable, $p_prodTypeId = -1, $onChange = false ){
	global $db;
		
	$q = "SELECT * FROM ".$p_sParentTable." ORDER BY title ASC";
	
	$res = $db->get_results($q, ARRAY_A);
	

	
	if($onChange){ 
		$on_change = 'onChange="submit();"';
		$all_option = '<option value="-1">Show All</option>';
	}

	echo '<select name="id_'.$p_sParentTable.'_type" '.$on_change.'>';
	echo $all_option;
	
	for($i = 0; $i < count($res); $i++){
		
		if($onChange){ 
			$requested = 'id_'.$p_sParentTable.'_type';
			if($_REQUEST[$requested] != $res[$i]['id']){ $s = ''; } else { $s = 'SELECTED'; }
			echo '<option value="'.$res[$i]['id'].'" '.$s.' >'.$res[$i]['title'].'</option>';
		} else { 			
			if($res[$i]['id'] == $p_prodTypeId){ $s = 'SELECTED'; } else {$s = ''; }
			echo '<option value="'.$res[$i]['id'].'" '.$s.' >'.$res[$i]['title'].'</option>';
		}
	}
	echo '</select>';

}

/*-------------------------------------------------------------------------*/ 

function br2nl($text)
{
   return  preg_replace('/<br\\s*?\/??>/i', '', $text);
}


/*-------------------------------------------------------------------------*/ 

function txtFormat() { ?>

<p> <a href="javaScript:textEdit('addBold');"> bold</a> | <a href="javaScript:textEdit('addItalic');"> italicize </a> | <a href="javaScript:textEdit('hhh');"> header</a>  | <a href="javaScript:textEdit('lineBreak');">line break</a>  |  <a href="javaScript:textEdit('ppp');"> new paragraph</a> | <a href="javaScript:Lister('orderList');"> orderered list</a> | <a href="javaScript:textEdit('addLink');"> add link</a> | <a href="javaScript:textEdit('addMaillink');">link to email</a></p>

<? }

/*-------------------------------------------------------------------------*/ 

function makeUrlFriendly($input) {
   // Replace spaces with underscores
   $output = preg_replace("/\s/e" , "_" , $input);
  
   // Remove non-word characters
   $output = preg_replace("/\W/e" , "" , $output);
  
   return $output;
}


/*-------------------------------------------------------------------------*/ 


}// end class

$fns = new phpFns();
?>