<?PHP

/* Hello. Welcome to CRUST */

// this is your array of allowed content types. 
$allowed = array('work', 'photos', 'video', 'friends', 'info');

if(isset($_GET['select']) && in_array($_GET['select'], $allowed)){
	$type = $_GET['select'];
} else {
	$type = 'home';
}
?>
<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="c/bocksel.css" type="text/css" media="screen" />
<link rel="stylesheet" href="c/thickbox.css" type="text/css" media="screen" />

<script src="j/jquery.js" type="text/javascript"></script>
<script src="j/jquery.bgiframe.js" type="text/javascript"></script>
<script src="j/jquery.dimensions.js" type="text/javascript"></script>
<script src="j/jquery.tooltip.min.js" type="text/javascript"></script>
<script src="j/chili-1.7.pack.js" type="text/javascript"></script>
<script src="j/thickbox.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$('#content_column img.thumb').tooltip({
		track: true,
		delay: 0,
		showURL: false,
		showBody: " - ",
		fade: 250
	});
	
	<? /* $('#header h1 a').tooltip({
		track: true,
		delay: 0,
		showURL: false,
		showBody: " - ",
		fade: 250
	});
	*/ ?>

	$('a.obf').defuscate();

});

jQuery.fn.defuscate = function( settings ) {
    settings = jQuery.extend({
        link: true,
        find: /\b([A-Z0-9._%-]+)\([^)]+\)((?:[A-Z0-9-]+\.)+[A-Z]{2,6})\b/gi,
        replace: '$1@$2'
    }, settings);
    return this.each(function() {
        if ( $(this).is('a[@href]') ) {
            $(this).attr('href', $(this).attr('href').replace(settings.find, settings.replace));
            var is_link = true;
        }
        $(this).html($(this).html().replace(settings.find, (settings.link && !is_link ? '<a href="mailto:' + settings.replace + '">' + settings.replace + '</a>' : settings.replace)));
    });
};

</script>

<title>Jon Bocksel</title>
</head>
<body>
<div id="header"><h1><a href="<? echo $_SERVER['PHP_SELF']; ?>" title="read the latest updates">Jon Bocksel</a></h1></div>

<div id="site" class="clrfix">
	<div id="menu_column">
		<ul>
		<? if($type == 'work'){ $sel = 'class="selected"';} else { $sel = '';} ?>	<li><a href="<? echo $_SERVER['PHP_SELF']; ?>?select=work" <? echo $sel; ?> >Selected Work</a></li>
		<? if($type == 'photos'){ $sel = 'class="selected"';} else { $sel = '';} ?>	<li><a href="<? echo $_SERVER['PHP_SELF']; ?>?select=photos" <? echo $sel; ?> >Photography</a></li>
		<? if($type == 'video'){ $sel = 'class="selected"';} else { $sel = '';} ?>	<li><a href="<? echo $_SERVER['PHP_SELF']; ?>?select=video" <? echo $sel; ?> >Video</a></li>
		<? if($type == 'friends'){ $sel = 'class="selected"';} else { $sel = '';} ?>	<li><a href="<? echo $_SERVER['PHP_SELF']; ?>?select=friends" <? echo $sel; ?> >Friends</a></li>
		<? if($type == 'info'){ $sel = 'class="selected"';} else { $sel = '';} ?>	<li><a href="<? echo $_SERVER['PHP_SELF']; ?>?select=info" <? echo $sel; ?> >Information</a></li>
		</ul>
	</div>
	
<?
require_once($_SERVER['DOCUMENT_ROOT'].'/_crust/mysql.php');
global $db;

/* - - - - - - - - - - - - - - - - - - - - - - - - - - - -  - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* quick function for prev/next */
function prev_next($id, $type){
	global $db;
	$total_count_query = "SELECT count(*) FROM shit WHERE type = '".$type."' ORDER BY id ASC";
	$r_total = $db->get_results($total_count_query, ARRAY_A);
	
	$q_prev = "SELECT * FROM shit WHERE id > ".$id." AND type = '".$type."' ORDER BY id ASC LIMIT 0,1";
	$r_prev = $db->get_results($q_prev, ARRAY_A);

	$q_next = "SELECT * FROM shit WHERE id < ".$id." AND type = '".$type."' ORDER BY id DESC LIMIT 0,1";  
	$r_next = $db->get_results($q_next, ARRAY_A);

	#print_r($r_total);
	
	$return = '';
	if($r_total[0]['count(*)'] > 0){
		$return.= '<li>';
		if(count($r_prev) > 0) {
			$return.=  ' <a href="index.php?select='.$r_prev[0]['type'].'&id='.$r_prev[0]['id'].'" title="'.ucwords(stripslashes($r_prev[0]['title'])).'">Previous</a> / ';
		} else { 
			$return.=  ' <span style="color:#ccc;">Previous</span> / ';
		}
		
		$return.= '</li> <li>';
		if(count($r_next) > 0) {
			$return.= ' <a  href="index.php?select='.$r_next[0]['type'].'&id='.$r_next[0]['id'].'" title="'.ucwords(stripslashes($r_next[0]['title'])).'">Next</a> / ';
		} else {
			$return.= '  <span style="color:#ccc;">Next</span> / ';
		} 

		
		$return.= '</li>';
	} else { $return = null;}
	
	return($return);
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - -  - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
function formatDate($val){
	$arr = explode("-", $val);
	return date("m/d/y", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}

function paginate($start, $limit=5){

$older = $start + $limit;
$newer = $start - $limit;

$to_return = '
<div id="paginate">
<span class="left"><a href="index.php?start='.$older.'">Older</a></span>';
if($start > 0){
	$to_return.='<span class="right"><a href="index.php?start='.$newer.'">Newer</a></span>';
}
$to_return.='</div>'."\n";
return $to_return;

}

/*------------------------------------------------------------------*/



/* select the requested entry */
$q = "SELECT * FROM shit WHERE type = '".$type."' ";

if(isset($_GET['id']) && is_numeric($_GET['id'])){
	$q.= " AND id = '".$_GET['id']."'";
	$single = ' single'; // add the single item class if we're targeting a specific row
} else {
	$single = '';
}


if($type == 'home'){ 
	$q.= "ORDER BY date DESC ";
} else {
	$q.= " ORDER BY id DESC ";
}
if($type =='home'){
	if(isset($_GET['start']) && is_numeric($_GET['start'])){
		$start = $_GET['start'];
		$end =  5;
	} else {
		$start = 0;
		$end = 5;
	}
	
	if($start < 0){ $start = 0; $end = 5;}
	
	$q.= " LIMIT ".$start.", ".$end;
}
$r = $db->get_results($q, ARRAY_A);
/*	echo '<pre>';
	print_r($r);
	echo '</pre>';
*/
if($type =='home' || $type=='info' || $type == 'friends'){
	echo '<style type="text/css"> ul.single li:hover{  background-color:transparent !important; cursor:default !important;} </style>';
}

if(count($r)>0){

	if($single != ''){
		$i = $r[0];
		echo '<div class="mk-w"><h2>'.ucwords(stripslashes($i['title'])).'</h2></div>';
		echo'	<div id="content_column"> ';

		echo ' <ul class="'.$type.$single.'">'."\n".'<li>';
		switch($type){
			default:
				if(!empty($i['image']) || $i['image'] != ''){
					if(ereg('single', $single)){
						echo '<a href="/uploads/'.$i['image'].'" title="'.stripslashes($i['title']).'" class="thickbox"><img src="/uploads/'.$i['image'].'" alt="'.stripslashes($i['title']).'" /></a>';

					} else {
						echo '<img src="/uploads/'.$i['image'].'" alt="'.stripslashes($i['title']).'" />';
					}
				}
				echo '<p>'.nl2br(stripslashes($i['copy'])).'</p>';
			break;
		}
		echo '</li>'."\n".'</ul>'."\n";
		echo '<ul class="go_back"> '.prev_next($i['id'], $type).' <li><a href="index.php?select='.$type.'">Back</a></li></ul>';

	} else {
		if($type == "friends" || $type == "info"){
				echo '<div class="mk-w"><h2>'.ucwords(stripslashes($r[0]['title'])).'</h2></div>';
		}

		if($type == "work" || $type == "photos" || $type == "video"){
			switch($type){
				case 'work':
					echo  '<div class="mk-w"><h2>Selected Work</h2></div>';
				break;
				case 'photos':
					echo '<div class="mk-w"><h2>Photography</h2></div>';
				break;
				case 'video':
					echo '<div class="mk-w"><h2>Video</h2></div>';
				break;
			}
		}

		echo'	<div id="content_column"> ';
	
		echo ' <ul class="'.$type.$single.'">'."\n";
		foreach($r as $i){
			echo '<li>';
				#echo ' <pre>'; print_r($i); echo '</pre>';
			switch($type){
				case 'home':
				case 'info':
				case 'friends':
					if($type == 'home'){
						echo '<a href="index.php?select='.$type.'&id='.$i['id'].'">';
						echo '<h3>'.stripslashes($i['title']).'</h3>';
						echo '</a>';
					}
	
					if(!empty($i['image']) || $i['image'] != ''){
						echo '<img src="/uploads/'.$i['image'].'" alt="'.stripslashes($i['title']).'" />';
					}
					echo '<p>'.nl2br(stripslashes($i['copy'])).'</p>';
					if($type == 'home'){
						echo '<span class="date">'.formatDate($i['date']).'</span>';
					}
				break;
			
				case 'work':
				case 'photos':
				case 'video':
						echo '<a href="index.php?select='.$type.'&id='.$i['id'].'">';
						echo '<img src="/uploads/'.$i['thumb'].'" alt="'.stripslashes($i['title']).'" title="'.stripslashes($i['title']).'" class="thumb" />';
						echo '</a>';
				break;

			}
			
			echo '</li>';
		}
		echo '</ul> '."\n";
	}
} else {	

	echo ' <div id="content_column"><ul class="'.$type.$single.'">'."\n";
		echo '<li class="no_results">Sorry. No posts are available to display.</li>'."\n";
	echo '</ul> </div>'."\n";
}
?>
	</div>

<? if($type == "home"){ echo paginate($start); }?>

<!--[if IE]>
	<div id="no_ie">
		<h2>You're using a Microsoft Web Browser. You might see some "errors". </h2>  
		<p><a href="http://getfirefox.com" target="_blank">Firefox</a> works on PCs too.</p>
	</div>
-->

<div id="footer">&copy; The Swell Co <? echo date('Y'); ?></div>


</div>
</body>
</html>