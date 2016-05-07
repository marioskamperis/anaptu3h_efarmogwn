<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
</head>

<script>
$( document ).ready(function() {
console.log( "ready!" );
	$.ajax({
		url: 'mobile_login.php',
		data: {
			email: 'marios@gmail.com',
			password: 'marios',
			is_mobile: 'true'
		},
		type: 'post',
		success: function (output)
		{
			alert(output);
		}

	});
	console.log("done");
});


</script>
<?php
echo "ok";
?>