<? 
session_start();

include_once('phpFns.class.php');
global $fns;


unlink('../uploads/'.$_GET['img']);

//if($_GET['table'] == 'post_assets'){ $field = 'file_thumb'; }

//echo "field is: ". $field;

if(isset($_GET['row'])){
$query = "DELETE FROM ".$_GET['table']." WHERE id = ".$_GET['itemId']; 
}  else {
$query = "UPDATE ".$_GET['table'] ." SET ".$_GET['field']. " = '' WHERE id = ".$_GET['itemId'];
}
//echo "query is: ". $query;
$deleted = $db->query($query);

if($_GET['parentTable']){
	$admin = $_GET['parentTable'];
	$type = $admin;
	
	if(isset($_GET['itemId'])){
		$itemId = $_GET['itemId'];
	}

	if(isset($_GET['parentId'])){ 
		$itemId = $_GET['parentId'];
	}

} else {
	$admin = $_GET['table'];
	$itemId = $_GET['itemId'];
}

switch($admin){
	case"publico_events": 
		$admin = 'events';
	break;
	case"publico_news": 
		$admin = 'news';
	break;	

}


header ('Location: index.php?action=shit&itemId='.$itemId.'&type='.$admin.'&success=true');
?>