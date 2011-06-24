<? 
include_once("mysql.php");
include_once("phpFns.class.php");

/* ---------------------------------------------------------------------------------------------------*/
class listMngr{
	
	var $m_nPieceId = -1;

/* ---------------------------------------------------------------------------------------------------*/
function sidePiece($p_sTablename){
	global $db;
	global $fns;
	
	//echo $p_sTablename;
	
	if(!$p_sTablename){ $p_sTablename = 'shit'; }
	
	if($_REQUEST['type']){ 
		switch($_REQUEST['type']){
			case 'photos':
			case 'home':
			case 'info':
			case 'friends':
			case 'video':
			case 'work':
				$extras = " WHERE type = '".$_REQUEST['type']."' "; 
			break;
		}
	} else {
		$extras = " WHERE type = 'home' ";
	}
	
	
	$query = "SELECT * FROM ".$p_sTablename. $extras." ORDER BY id ASC";

	//echo $query;

	$res = $db->get_results($query, ARRAY_A);
	
	if($res < 1){ 
		echo '<div class="entry">';
		echo '<h3>No Entries</h3>';
		echo '</div>';
	}
	
	for ($i=0; $i<count($res); $i++){
	
		$this->m_nPieceId = $res[$i]['id'];
		$p_sOther = $res[$i]['type'];
		echo '<div class="entry">'."\n";
		
		if($res[$i]['title']){ 
			$title = $res[$i]['title'];
		} else {
			$title = "NO TITLE ENTERED";
		}
		echo "\t". '<h3><a href="index.php?action='.$p_sTablename.'&itemId='.$this->m_nPieceId.'&type='.$_REQUEST['type'].'">'.$title.'</a></h3>'."\n";
		/*if($p_sTablename != "text"){ 
			echo $fns->gen_order_dropdown($p_sTablename, 'id', $this->m_nPieceId, $res[$i]['display_order'], $p_sTablename, $this->m_nPieceId );
			echo "\n";
		} else { }
		*/
		$confirmation_string = "'delete_item.php?action=".$p_sTablename."&type=".$p_sOther."&parentId=".$this->m_nPieceId."&itemId=".$this->m_nPieceId."'";
		echo '<ul>'."\n";

		echo '<li style="background-color:#F00; border: 1px solid #F00;"><a href="#" onclick="confirmation('.$confirmation_string.')">Delete</a></li>';
		echo '</ul>'."\n";
		echo '<br/>';
		

		
		
		
		
		 /*	$p_image = $fns->checkFile($p_sTablename, $this->m_nPieceId, true); 	*/

		echo '</div>'."\n";
	
	
	}


}

/* ---------------------------------------------------------------------------------------------------*/

function sortForm($p_sTablename, $p_sortString, $p_nItemId){
global $fns;

//echo $p_nItemId;
echo '<div class="entry">';
echo '<form id="itemSort" action="index.php" method="get" enctype="multipart/form-data">';
echo '<input type="hidden" name="action" value="shit" />';
echo '<input type="hidden" name="itemId" value="'.$p_nItemId.'" />';


if($p_sTablename == "shit"){ ?> 
	<p>sort by category: </p>

	<select name="type" onChange="submit();" >
		<option value="info"> Choose One A Section: </option>
		<? if($_REQUEST['type'] == 'home'){ $s = 'selected'; } else { $s = ''; } ?><option value="home" <? echo $s; ?>>home</option>
		<? if($_REQUEST['type'] == 'info'){ $s = 'selected'; } else { $s = ''; } ?><option value="info" <? echo $s; ?>>info</option>
		<? if($_REQUEST['type'] == 'work'){ $s = 'selected'; } else { $s = ''; } ?><option value="work" <? echo $s; ?>>selected work</option>
		<? if($_REQUEST['type'] == 'photos'){ $s = 'selected'; } else { $s = ''; } ?><option value="photos" <? echo $s; ?>>photography</option>
		<? if($_REQUEST['type'] == 'video'){ $s = 'selected'; } else { $s = ''; } ?><option value="video" <? echo $s; ?>>video</option>
		<? if($_REQUEST['type'] == 'friends'){ $s = 'selected'; } else { $s = ''; } ?><option value="friends" <? echo $s; ?>>friends</option>

	</select>
<? }
	//
	//echo '<input type="submit" name="submit" value="submit" />';
echo '</form>';
echo '</div>';

}


/* ---------------------------------------------------------------------------------------------------*/
} // end class


$list = new listMngr(); ?>