function confirmation( p_url) {
	
    var answer = confirm("Do you really want to do that? ")
   
    
    if (answer){
         var location = p_url
     	//alert("location is: " + location);
      	document.location.href = location;
     }
     else{
         alert("Word.")
     }
}


function confirmRequest( p_link, p_sWarnMessage )
{
	var confirmed = confirm ( p_sWarnMessage );
	if ( true == confirmed )
	{
		p_link.href  += "&confirmed=true";
	}
	return confirmed;
}

function changeOrder(p_form, p_tablename, p_key) {

	//alert (" WHY! ");

 	var l_origOrder = p_form.origorder.value;
	var l_newOrder 	= p_form.position.value;
	if ( l_origOrder == l_newOrder )
    	return;
	var location = p_form.posturl.value;
	location += "&neworder=" + l_newOrder;

	//alert(" Submit to: "+ location);
	document.location.href = location;
}

function textEdit(action){
		var textEdit = document.itemJawn.copy.value;
		// link
			if(action == 'addLink'){
	
				var nameAdded = prompt("what do you want the link to say:","");
				var linkAdded = prompt("Paste your link here (http://...):","http://");
				if(!nameAdded|| !linkAdded){}
				else{ document.itemJawn.copy.value = textEdit + '<a href="'+linkAdded+'" target="_new" >' +nameAdded+'</a> ';}
			}
				else if(action == 'addMaillink'){	
				var nameAdded = prompt("what do you want the link to say:","");
				var linkAdded = prompt("Paste email addresss here:","");
				if(!nameAdded|| !linkAdded){}
				else{ document.itemJawn.copy.value = textEdit + '<a  href="mailto:'+linkAdded+'">' +nameAdded+'</a> ';}
			}
				// bold text
			else if(action == 'addBold'){
				var boldText = prompt("type what you want to be bold:","");
				if(!boldText){}
				else{ document.itemJawn.copy.value = textEdit + '<b>' +boldText+'</b> ';}
			}
				// italic text
			else if(action == 'addItalic'){
				var italicText = prompt("The word(s) you want italicized:","");
				if(!italicText){}
				else{document.itemJawn.copy.value = textEdit + '<i>' +italicText+'</i> ';}
			}
			//line break
			if(action == 'lineBreak'){
				document.itemJawn.copy.value = textEdit + '<br/>';
			}
			//paragraph
			if(action == 'ppp'){
				var newPar = prompt("The word(s) Enter Text:","");
				if(!newPar){}
				else{document.itemJawn.copy.value = textEdit + '<p>' +newPar+'</p> ';}
			}
			
			//header text
			if(action == 'hhh'){
				var newHead = prompt("Header Text:","");
				if(!newHead){}
				else{document.itemJawn.copy.value = textEdit + '<h3 class="style2">' +newHead+'</h3> ';}
			}
	
	window.document.itemJawn.copy.focus();
}



function Lister(action){
var textEdit = document.itemJawn.copy.value;

//ordered list
			if(action == 'orderList'){

			//number of items in list
			var maxNum = prompt("Number of Items in this list:","");
			var container = new Array(maxNum);
			textEdit = textEdit + '<ul>\n';
				for (i = 0; i < maxNum; i++)
				{
				container[i] = prompt("list item","");
				textEdit = textEdit + '<li>' + container[i] + '</li>\n';

				}
				textEdit = textEdit + '</ul>\n';
				document.itemJawn.copy.value = textEdit;
			}
}
