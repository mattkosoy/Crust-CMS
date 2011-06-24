<? session_start();
include_once "formMngr.class.php";	// include form manager class - generates forms & handles form submission

global $db;
global $fns;

//echo "something";


if($_POST){

//print_r($_POST);

$admin = $_POST['p_sTableName'];

$itemId = $_POST['p_nItemId'];

$forms->handleSubmit($admin , $itemId);
	





header("Location: index.php?action=".$admin."&itemId=".$itemId."&success=true");

//print_r($_SESSION);

}
?>