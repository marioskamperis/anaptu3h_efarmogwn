<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
</head>

<script>
	$(document).ready(function ()
	{
		console.log("ready!");
		$.ajax({
			url: 'book_ticket.php',
			data: {
				place_id: 'RYTDFYHG&*I%$^*(YH',
				name: 'marios',
				address: 'solomou 31',
				lat: '27,3',
				lon: '27,3',
				telephone: 'ok',
				website: 'www.marinetraffi.com',
				user_id: '1245'
			},
			type: 'post',
			success: function (output)
			{
				console.log(output);
			}

		})
		;
		console.log("done");
	});


</script>