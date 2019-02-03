<?php 
	session_start();
	
	//if nothing has been set yet, initialize an array of 15 zeros, and set this array to be a session variable
	if (!isset($_SESSION['hotelRooms'])) {
		$rooms = array_fill(0,15,0);
		$_SESSION['hotelRooms'] = $rooms;
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset = "utf-8">
		<title>Hotel Booking</title>
		
		<?php 
			//only run through this if the user has already pressed the submit button
			if (isset($_POST["submit"])) {  
				$roomCheck = 0;
				
				//set the assigned room with 1 in the array
				if ($_POST["customerRoomType"] == "A") {
					for ($i = 0; $i < 5; $i++) {
						if ($_SESSION['hotelRooms'][$i] == 0) {
							$_SESSION['hotelRooms'][$i] = 1;
							break;
						}
						$roomCheck++;
					}
				}
				
				if ($_POST["customerRoomType"] == "B") {
					for ($i = 5; $i < 10; $i++) {
						if ($_SESSION['hotelRooms'][$i] == 0) {
							$_SESSION['hotelRooms'][$i] = 1;
							break;
						}
						$roomCheck++;
					}
				}
				
				if ($_POST["customerRoomType"] == "C") {
					for ($i = 10; $i < 15; $i++) {
						if ($_SESSION['hotelRooms'][$i] == 0) {
							$_SESSION['hotelRooms'][$i] = 1;
							break;
						}
						$roomCheck++;
					}
				}
			}
		?>
		<script type = "text/javascript">
			//website was built using JavaScript (as asked in the original instructions), but the core functions are still in PHP (storing room statuses in session array)

			//the prices of each room type
			var priceTypeA = getRandomPrice(100,400);
			var priceTypeB = getRandomPrice(100,300);
			var priceTypeC = getRandomPrice(100,200);
			
			//this function returns a random price between two values, inclusive
			function getRandomPrice(min, max) {
				return Math.floor(Math.random() * (max - min + 1)) + min;
			}
			
			var roomA = "Type A: $" + priceTypeA;
			var roomB = "Type B: $" + priceTypeB;
			var roomC = "Type C: $" + priceTypeC;
			
			var rooms = <?php echo json_encode($_SESSION['hotelRooms']); ?>;      //retrieving the php session array as a javascript array

			//append a string on to the room info strings that indicates the availability
			if (rooms[4] == 1)
				roomA += " ... NO VACANCIES";
			else
				roomA += " ... AVAILABLE";

			if (rooms[9] == 1)
				roomB += " ... NO VACANCIES";
			else
				roomB += " ... AVAILABLE";

			if (rooms[14] == 1)
				roomC += " ... NO VACANCIES";
			else
				roomC += " ... AVAILABLE";
			
			var cheapRoom;
			var mediumRoom;
			var expensiveRoom;
			
			//determine which room is most expensive, cheapest, and middle price
			if (priceTypeA > priceTypeB && priceTypeA > priceTypeC) {
				
				expensiveRoom = roomA;
			
				if (priceTypeB > priceTypeC) {
					cheapRoom = roomC;
					mediumRoom = roomB;
				}
				else {
					cheapRoom = roomB;
					mediumRoom = roomC;
				}
			}
			else if (priceTypeB > priceTypeA && priceTypeB > priceTypeC) {
				
				expensiveRoom = roomB;
				
				if (priceTypeA > priceTypeC) {
					cheapRoom = roomC;
					mediumRoom = roomA;
				}
				else {
					cheapRoom = roomA;
					mediumRoom = roomC;
				}
			}
			else if (priceTypeC > priceTypeA && priceTypeC > priceTypeB) {
				
				expensiveRoom = roomC;
				
				if (priceTypeA > priceTypeB) {
					cheapRoom = roomB;
					mediumRoom = roomA;
				}
				else {
					cheapRoom = roomA;
					mediumRoom = roomB;
				}
			}

			//write out the HTML for the page
			document.writeln("<h1><center>Hotel Booking Website</center></h1><h5><center>tested in Google Chrome</center></h5>");
			document.writeln("<center><h3>Rooms:</h3>");
			document.writeln(cheapRoom + "<br>");
			document.writeln(mediumRoom + "<br>");         //rooms are sorted in ascending order by price
			document.writeln(expensiveRoom + "<br>");
			document.writeln("<h3>Book a Room:</h3>");
			document.writeln("<form method = \"post\">");
			document.writeln("<input type = \"hidden\" name = \"priceA\" value = \"" + priceTypeA + "\">");
			document.writeln("<input type = \"hidden\" name = \"priceB\" value = \"" + priceTypeB + "\">");        //hidden form elements hold the price of each room
			document.writeln("<input type = \"hidden\" name = \"priceC\" value = \"" + priceTypeC + "\">");
			document.writeln("<div><label>Name:</label><br><input type = \"text\" name = \"customerName\"</div>");
			document.writeln("<div><label>Room Type:</label><br><input type = \"text\" name = \"customerRoomType\"</div>");
			document.writeln("<p><input type=\"submit\" value = \"Submit Request\" name=\"submit\"></form></center>");
			
		</script>
		
		<?php  
			if (isset($_POST["submit"])) {
				if ($_POST["customerRoomType"] == "A" || $_POST["customerRoomType"] == "B" || $_POST["customerRoomType"] == "C") {
					
					//retrieve the room type and price from the post method
					if ($_POST["customerRoomType"] == "A") {
						$roomNum = $roomCheck + 1;
						$roomPrice = $_POST["priceA"];
					}
					else if ($_POST["customerRoomType"] == "B") {
						$roomNum = $roomCheck + 6;
						$roomPrice = $_POST["priceB"];
					}
					else {
						$roomNum = $roomCheck + 11;
						$roomPrice = $_POST["priceC"];
					}
					
					//display no vacancies message if rooms of the desired type are not available
					if ($roomCheck == 5) 
						print("<center>NO VACANCIES.  PLEASE COME BACK LATER</center>");
					else {    //display booking memo if everything works out
						print("<center>Hello, " . $_POST["customerName"] . ".<br>");
						print("You have been booked to Room Number " . $roomNum . ", which is a room of Type " . $_POST['customerRoomType'] . ", for the price of $" . $roomPrice . " .</center>");
					}
				}
				else
					print("<center>Please Enter a Valid Room Type</center>");      //if the user entered any room other than A, B, or C, reject the input
			}
				
		?>
	</head>
	<body></body>
</html>
