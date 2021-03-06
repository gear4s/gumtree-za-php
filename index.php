<html>
	<head>
		<link rel="stylesheet" href="pure.css">
		<style>
			.button-success,
			.button-error,
			.button-warning,
			.button-secondary {
				color: white;
				border-radius: 4px;
				text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
			}

			.button-success {
				background: rgb(28, 184, 65); /* this is a green */
			}

			.button-error {
				background: rgb(202, 60, 60); /* this is a maroon */
			}

			.button-warning {
				background: rgb(223, 117, 20); /* this is an orange */
			}

			.button-secondary {
				background: rgb(66, 184, 221); /* this is a light blue */
			}

			td.pnum, td img {
				text-align: center;
			}

			html, body {
				height: 100%;
			}

			html {
				display: table;
				margin: auto;
			}

			body {
				display: table-cell;
				vertical-align: middle;
			}

			.loader {
				border: 16px solid #f3f3f3; /* Light grey */
				border-top: 16px solid #3498db; /* Blue */
				border-radius: 50%;
				width: 120px;
				height: 120px;
				animation: spin 2s linear infinite;
			}

			@keyframes spin {
				0% { transform: rotate(0deg); }
				100% { transform: rotate(360deg); }
			}

			table {display: none;}

			/* Add Animation */
			@-webkit-keyframes animatetop {
				from {top: -300px; opacity: 0}
				to {top: 0; opacity: 1}
			}

			@keyframes animatetop {
				from {top: -300px; opacity: 0}
				to {top: 0; opacity: 1}
			}

			/* Add animation (fade in the popup) */
			@-webkit-keyframes fadeIn {
			    from {opacity: 0;} 
			    to {opacity: 1;}
			}

			@keyframes fadeIn {
			    from {opacity: 0;}
			    to {opacity:1 ;}
			}
		</style>
		<script src="jq.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.pure-table').css("display", "none");

				function startAjax() {
					$.ajax({
						url: 'http://localhost/~digipc/getgumtree.php',
						type: 'get',
						success: function(data) {
							$('.rmtr').remove();
							$('.pure-table').css("display", "table");
							$('.loader').css("display", "none");
							$('html').css('display', "block");
							$('body').css('display', "block");
							$('body').css('vertical-align', "baseline");

							$("caption#popup").text("Advertisements (" + (data.messages?data.messages:"0") + " message" + (data.messages==1?"":"s") + ")");
							data.items.forEach(function(item) {
								var html = '				<tr class="rmtr">';
								html += "\n					<td><img style='width: 50%; height: 50%' src='" + item.imgsrc + "'></img>";
								html += "\n					<td>" + item.title + "</td>";
								html += "\n					<td class='pnum'>" + item.page + "</td>";
								html += "\n					<td><a href='gumtreeadd.php?" + item.hrefargs + "' class='button-warning pure-button'>Repost</a></td>";
								html += "\n					<td><a href='" + item.edithref + "' class='button-secondary pure-button'>Edit</a></td>";
								html += "\n					<td><a href='" + item.adhref + "' class='button-success pure-button'>View</a></td>";
								html += "\n					<td><a href='gumtreeremove.php?" + item.hrefargs + "' class='button-warning pure-button'>Delete</a></td>";
								html += '\n				</tr>';
								$('.pure-table-body').append(html);
							});
						},
						error : function(err, req) {
							$("#errPop").show();
						}
					});
				}
				startAjax();
				setInterval(startAjax, 60000);
			});
		</script>
	</head>

	<body>
		<div class="loader"></div>
		<table width="100%" id="tbId" class="pure-table pure-table-striped pure-table-horizontal">
			<caption id="popup">
				Advertisements
			</caption>
			<tbody class='pure-table-body'>
				<tr>
					<th>Image</th>
					<th>Title</th>
					<th>Page Number</th>
					<th colspan="4">Actions</th>
				</tr>
			</tbody>
		</table>
	</body>
</html>