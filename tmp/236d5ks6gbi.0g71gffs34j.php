<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $BASE.'/'.$UI; ?>" />        
        <title><?php echo $site; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="../../ui/css/bootstrap.css" rel="stylesheet" media="screen">
        <link href="../../ui/css/crud.css" rel="stylesheet" media="screen">

 
	<script type="text/javascript">
function ajaxDisplay(){
 
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			document.getElementById("display").innerHTML = ajaxRequest.responseText;
		}
	}
	
	var first = document.getElementById('itemQt').value;
	var queryString = "?amount=" + first;

	ajaxRequest.open("GET", "<?php echo $BASE.'/ajax/100'; ?>", true);
	ajaxRequest.send(null); 
}
</script>

    </head>

    <body>

        <div class="container">
		
            <?php if ($message): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?php echo $message; ?></strong>
            </div>
            <?php endif; ?>
