<? session_start();

include_once('phpFns.class.php');
global $fns;
global $db;

if($_GET['action'] == "publico_media"){
	$reaction = $fns->getColVal($p_sTableName, "parent", $p_nItemId);
} else if($_GET['action'] == 'publico_news' || $_GET['action'] == 'publico_about' || $_GET['action'] == 'publico_events'){
	$reaction = str_replace("publico_", "", $_GET['action']);
} else {
	$reaction = $_GET['action'];	
} 

if(isset($_REQUEST['type'])) { 
	$reaction.='&type='.$_REQUEST['type'];
}

$query = "DELETE FROM ".$_GET['action']." WHERE id = ".$_GET['itemId']; 
$deleted = $db->query($query);

header ('Location: index.php?action='.$reaction);


?>