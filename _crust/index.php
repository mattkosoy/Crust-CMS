<?  
session_start();

include_once "mysql.php"; 		// Include ezSQL database specific component
include_once "htmlFns.class.php";   	// include classes to output html & css 
include_once "formMngr.class.php";	// include form manager class - generates forms & handles form submission
//include_once "itemListMgr.class.php";	// include list manager class - generates lists of data
include_once "phpFns.class.php";	



if($_POST['userid'] && $_POST['password']){
	
  	$userid = $_POST['userid'];
	$password = $_POST['password'];
	
	global $db;
	global $fns;
	
	$result = $db->get_results("select * from auth where username = '$userid' and password = password('$password')", ARRAY_A);
	
	//echo $db;
	
	//echo $result;
	
/*	
	if(!$result){
		echo "<h2>Cannot run query</h2>";
		echo mysql_error();
		exit();
	}
*/	
		if( count($result) > 0){
		//if your in the database register the user name
		//$valid_user = $userid;
		session_register('valid_user');

		$_SESSION['valid_user'] = $userid;
		}
		//echo $userid;
		
		//echo $_SESSION['valid_user'];
		
	//print_r($_SESSION);
	
	
}



if ($_SESSION['valid_user']){
	
	// echo "item id". $_SESSION['sidebar_id']."<br/>";

	$html->htmlHeader();
	//echo "username is: ".$_SESSION['valid_user'];

/*
	// display list of available projects -- ordered by display order
	// if admin -- display list of clients
	// 				click client name to see project list -- ability to re-order projects here
	//				click project name to view project details -- difft types of media associated.
	//				if slideshow  -- show available images -- ability to re-order each image
	//"#666666"
	// if client -- display most recent project 1st 
	// 

*/
	
	// ADMIN USER //
		global $forms;
		global $items;
		global $db;
		
	//	$html->adminNav();

		if($_GET['action']){
			
						
			if($_GET['action']){ $l_tableName = $_GET['action']; $l_headerLabel = "Add";  $l_itemId = $_GET['itemId']; } 	//assign current table from the GET value
			
			if($_GET['itemId'] && !$_GET['delete']){ $l_headerLabel = "Update"; }
			
		} else {
			
			//echo "holler"; 
			
			$l_tableName = 'shit';		//default current table value
			$l_headerLabel = "Add";
		}
		
		if($l_headerLabel == "Update"){ 
			$l_headerExtras = ' - ';
			$l_rowName = $fns->returnRowName($l_tableName, $l_itemId);	
			if(!$l_rowName){ $l_rowName = '- No Name Entered -'; }
		    $l_headerExtras.=  $l_rowName;
		} else { 
			$l_headerExtras = "";
		}
		
		if ($l_tableName == "photos"){ $l_HeadLabel = "photo sets"; } else { $l_HeadLabel = $l_tableName; }
		
		
		echo '<h2 style="margin:0 0 0 20px; padding:0;">'.$l_headerLabel." ".ucwords($l_HeadLabel).' '. ucwords($l_headerExtras).'</h2>'."\n";
			
		//if delete action has been passed then perform delete //
	
	
	 	if($_GET['delete'] == 'true'){
			$deleted = $fns->DeleteNoDisplay($l_tableName, $_GET['itemId']);
		} else if($_GET['delete'] == "display"){
			
		//	$l_tableName = $_GET['parentTable'];
		//	$p_itemId = $_GET['parentId'];
		}
	
		// DETERMINE WHICH FORMS TO DISPLAY //
		
		
		if($_GET['itemId'] && !$_GET['delete']){
			switch ($_GET['action']){
				
				default:
					$forms->buildMainForm($l_tableName, $_GET['itemId']);
				break;
			
			}


		}
		else if($_GET['action'] || !$_GET){
			switch ($_GET['action']){

			default:
				if(!$l_tableName){ $l_tableName = "shit"; }
				$forms->buildMainForm($l_tableName);
			break;
			
			}
		} 
		
	
		echo '</div>'."\n\n";


	
	$html->htmlFooter();	
	

} else {
		if($_POST['userid']){ 
		$html->htmlHeader();
			//if they tried and failed to login
			echo "<h2>Error logging you in. Please try again</h2>";
			
			// potentially set a counter variable -- 3 tries maximum //
			
		} else {
		$html->htmlHeader();
			if($_GET['logout']){ 
				echo "<h2 style=\"text-align:center; text-transform: uppercase;\">Please Log In Again</h2>";
			} else { 
				echo "<h2 style=\"text-align:center; text-transform: uppercase;\">Please Log In</h2>";
			}
		}
	
	//login form:
?>

	<div style="height:100px; width:500px; text-align: center; padding: 0 0 20px 0;">

	<form method="post" action="index.php" name="loggerinner">
		
		<div class="row">
			<span class="label" style="margin:7px 0 0 0;"><strong>USERNAME:</strong></span>
			<span class="former"><input type="text" name="userid"></span>
		</div>
		
		<div class="row">
			<span class="label" style="margin:7px 0 0 0;"><strong>PASSWORD:</strong></span>
			<span class="former"><input type="password" name="password"></span>
		</div>
		
		<div class="row">
			<input type="submit" value="LOG IN" class="submit">
		</div>
	</form>
	</div>
<? 
$html->htmlFooter();
exit();
} 