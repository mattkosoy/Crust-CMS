<?

include_once("listMngr.class.php");


/* ---------------------------------------------------------------------------------------------------*/
class htmlFns{

/* ---------------------------------------------------------------------------------------------------*/
function htmlHeader(){
	global $list;
?>
<html>
<head>
	<title>CRUST AND BUTTER - FLAVOR MANAGEMENT</title>

	<link href="style.css" rel="stylesheet" type="text/css" />

	<script src="j/prototype.js" type="text/javascript"></script>
	<script src="j/scriptaculous.js?load=effects" type="text/javascript"></script>
	<script src="j/fns.js" type="text/javascript"></script>
<?  if($_GET['success'] == true ){ 
	
	$success_div = '<div id="success" ><h2>THATS IN THERE.</h2></div>';
	
	$element = "document.getElementById('success')";
	$bodyExtras = 'onLoad="new Effect.Fade('.$element.', {duration: 3.5})"';
	
 } 

//onclick="new Effect.Fade(document.getElementById('demo-all'))" ?>
</head>

<body <? echo $bodyExtras; ?>>

<? if ($_SESSION['valid_user']){ ?>
<div id="top_piece">

<? echo $success_div; ?>
</div>



<div id="cnt" style="width:850px;">


<div id="piece">

<? 

$p_sTablename = $_REQUEST['action'];

switch($p_sTablename){
	case "shit":
		$p_sLabel = "Shit";
	break;
}

if(!$p_sLabel || $p_sLabel == ''){ $p_sLabel = "Shit"; }

if(!$p_sTablename){ $p_sTablename = "shit"; }
echo '<h2>'. $p_sLabel.'</h2><a href="index.php?action='.$p_sTablename.'" title="Add Shit">add one</a>';
	
	$list->sortForm("shit", $p_nSortid, $_GET['itemId']);
	
	//$p_sTablename = "gallery";

	
	$list->sidePiece($p_sTablename);
	
	
echo '<p style="text-align: center;"><a href="logout.php">-LOGOUT-</a></p>'."\n";
	
?>
</div>


<? 




} else {  ?>

<div style="width:600px; margin: auto;">

<? } ?>

<div id="inner_container">

<?
}


/* ---------------------------------------------------------------------------------------------------*/
function htmlFooter(){ ?>


</div>

</div>
</body>
</html>
<?

}





}

$html = new htmlFns();

?>