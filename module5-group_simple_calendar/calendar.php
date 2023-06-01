<?php
ini_set("session.cookie_httponly", 1);

session_start();

$previous_ua = @$_SESSION['useragent'];
$current_ua = $_SERVER['HTTP_USER_AGENT'];

if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
  die("Session hijack detected");
}else{
  $_SESSION['useragent'] = $current_ua;
}

?>
<!DOCTYPE html>
<html lang='en'>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://classes.engineering.wustl.edu/cse330/content/calendar.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<meta charset="utf-8"/>
		<title>Calendar</title>
		<style>
			body{
				width: 760px; /* how wide to make your web page */
				background-color: rgb(42, 75, 75); /* what color to make the background */
				margin: 0 auto;
				padding: 0;
				font:12px/16px Arial, sans-serif; 
			}
			div#main{
				background-color: rgb(129, 124, 124);
				margin: 0;
				padding: 10px;
			}
			table {
				border-collapse: collapse;
				width: 100%;
				height: 100px;
				table-layout: fixed;
				border: 1px solid black;
			}
			td {
				height:  100px;
				text-align: left;
				vertical-align: text-top;
				border: 1px solid black;
				
			}
			th {
				background-color: teal;
				border: 1px solid black;
			}
			#reg {
				display: inline-block;
			}
			.text-right{
				text-align:right;
			}
			p.inline{
				display: inline;
			}
		</style>
	</head>
	
	<body><div id="main">
<!--Show if user not login-->
		<!--User Login/Registration-->
		<fieldset class = "notLoggedIn">
			Username:  <input type="text" id="user">
			Password:  <input type="password" id="passGuess">
			<button id="loginBUTT">Login</button>
			<button class="inline" id=reg>Click here to register</button>
		</fieldset>

<!--End-->
<!--Shown if the user is logged in-->
		<div class = "loggedIn">
			Welcome! <p id="userWelcome" class="inline"></p>
		</div>
		<div class="text-right loggedIn">
			<button class="inline" id="toggleTagsOn">Turn on Tags</button> 
			<button class="inline" id="logoutClick">Logout</button>  
		</div>
<!--End-->

		<h1>Calendar</h1><!--Title of our Calender-->
		<h2 id="dispMon">Current Month: </h2><!--Display the current month displayed by the calendar-->
			
			<!--Buttons-->
			<button id="prevMonth">Previous</button><!--Button to move to the previous month-->
			<button id="shareCal" class="loggedIn">Share</button><!--Button to share calendar-->
			<button id="nextMonth">Next</button><!--Button to go to the next month-->
		<!--Header for the calendar with all the days of the week.  7 Columns, 1 row-->
		<table id = "calendar">
			<tr>
				<th>Sun</th>
				<th>Mon</th>
				<th>Tue</th>
				<th>Wed</th>
				<th>Thu</th>
				<th>Fri</th>
				<th>Sat</th>
			</tr>
		</table>
		
	<!--Popup Forms-->
		<!--Form to register a new user-->
		<div id="createUserForm" title="Register">
			<h3>Enter your information</h3>
			<form>
				<fieldset>
					<br>
					<!--for the username-->
					<label for="regUsername">Username (alphanumeric only)</label>
						<input type="text" name="regUsername" id="regUsername"><br><br>
					<!--for the passwords-->
					<label for="newPass1">Password: </label><br>
						<input type="password" name="newPass1" id="newPass1"><br><br>
					<label for="newPass2">Enter your password again: </label>
						<input type="password" name="newPass2" id="newPass2"><br><br>
					<p id="invalidUsernameWarning"></p><!--if the entered username is not alphanumeric-->
					<p id="unmatchedPasswordsWarning"></p><!--if the passwords do not match-->
				</fieldset>
			</form>
		</div>
		
		<!--create a new event-->
		<div id="createEventForm" title="Add new Event">
			<p id="eventDate"></p>
			<h3>Enter your information (Event)</h3>
			<form>
				<fieldset>
					<br>
					<label for="eName">Name</label>
						<input type="text" name="eName" id="eName" maxlength="20"><br><br>
					<label for="eTime">Time: </label>
						<input type="time" name="eTime" id="eTime"><br><br>
					<label for="eTag">Tag</label>
						<input type="text" name="eTag" id="eTag" maxlength="10"><br><br>
				</fieldset>
			</form>
		</div>

		<!--edit an existing event-->
		<div id="editEventForm" title="Edit Event">
			<p id="editDate"></p>
			<p id="editID"></p>
			<h3>Enter your information (Event)</h3>
			<form>
				<fieldset>
					<br>
					<label for="editName">Name</label>
						<input type="text" name="editName" id="editName" maxlength="20"><br><br>
					<label for="editTime">Time: </label>
						<input type="time" name="editTime" id="editTime"><br><br>
					<label for="editTag">Tag</label>
						<input type="text" name="editTag" id="editTag" maxlength="10"><br><br>
				</fieldset>
			</form>
		</div>
	
		<!--Form to share the calendar-->
		<div id="calShareForm" title="Calendar Sharing">
			<h3>Sharing this calender with whom?</h3>
			Enter only 1 user<br>
			<form>
						<input type="text" name="userShare" id="userShare"><br><br>			
			</form>
		</div>
		
		<script>
			//default session token
				var token = 0;
			//Default the month to the current month
				var currentTime = new Date();
				var currentY = currentTime.getFullYear();
				var currentM = currentTime.getMonth();
				var currentMonth = new Month(currentY,currentM);
				var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30 ,31];
				var tagged = false;
			document.addEventListener("DOMContentLoaded", buildCalendar(currentMonth), false);
			document.addEventListener("DOMContentLoaded", checkLogin(), false); 
			
			document.getElementById("prevMonth").addEventListener("click", decMon);//button moves the calendar to the previous month
			document.getElementById("nextMonth").addEventListener("click", incMon);//button moves the calendar to the next month
			document.getElementById("reg").addEventListener("click", openFormReg);//text that, when clicked, brings up user registration
			document.getElementById("logoutClick").addEventListener("click", logoutJS);
			document.getElementById("toggleTagsOn").addEventListener("click", tagOn);//button that turn on/off tags display


			function checkLogin() {
				var xmlHttp = new XMLHttpRequest();
				xmlHttp.open("POST", "check.php", true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.addEventListener("load", function(event){
					var data = JSON.parse(event.target.responseText);
					if(data.login){
						token = data.token;
						$("fieldset.notLoggedIn").toggle();
						$(".loggedIn").toggle();
						document.getElementById("userWelcome").innerHTML=data.username;
					}
				}, false);
				xmlHttp.send(null);

			}
		
			function tagOn(){
				if (tagged) {
					tagged = false;
					document.getElementById("toggleTagsOn").innerHTML="Turn on Tags";
					reloadMon();
				} else {
					tagged = true;
					document.getElementById("toggleTagsOn").innerHTML="Turn off Tags";
					reloadMon();
				}
			}

		//function to build a calander on our page using a month object
			function buildCalendar(month){
				//get the number of weeks in the month so we can make an approriately sized calendar
				var numWeeks = month.getWeeks().length;
				
				//get the number of days in the month from the array we made earlier
				var numDays = monthLength[month.month];
				//account for leap years if necessary
				if(month.month==1 && month.year%4===0 && month.year%100!==0){
					numDays=29;
				}
				
				//get the day of the week that the first of the month is on
				var startDayWeek = month.getDateObject(1).getDay();
				
				//update the display on the page to display the month and year of the calendar
				document.getElementById("dispMon").innerHTML=monthNames[month.month]+" "+month.year;
				var table = document.getElementById("calendar");
				
				//reset
				var dayNum = 1;
				var started = false;
				
				//actually make the calendar now
				for (i = 0; i <numWeeks; i++){
					var r=i+1;
					var row = table.insertRow(r);
					
					//make cells for each day of the week in the row
					for (j = 0; j<7; j++){
						var cell = row.insertCell(j);
						//if the month has "started" but not "ended", but day numbers into the appropriate cells
						if(dayNum<=numDays && (j===Number(startDayWeek) || started===true)){
							started = true;
							//put day number into the cell
							cell.innerHTML=dayNum;
							var cellID=dayNum;
							cell.id=cellID;
							cell.className="day";
							
							document.getElementById(cellID).addEventListener("click", openFormEvent);
							printEvents(month.year,(month.month+1),dayNum);
							dayNum++;
						}
					}
				}
			}
			function printEvents(dateYear, dateMonth, dateDay){
				//first grab events for the day
				var xmlHttp = new XMLHttpRequest();
				xmlHttp.open("POST", "eventsperday.php", true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				//console.log("year="+dateYear+"&month="+dateMonth+"&day="+dateDay);
				xmlHttp.addEventListener("load", function(event){
					var data = JSON.parse(event.target.responseText);
					if(data.success){
						var m = data.eventnum;
						for (r=0; r<m; ++r){
							var curevent = data.events[r];
							if (tagged) {
								curtag = curevent.tag;
								if (curtag !== "") {
									var obligation = document.createElement("p");
									obligation.innerHTML="<b>"+curevent.tag+": </b>"+curevent.title+" ("+curevent.time+")";
									var eventID = curevent.eventid;
									obligation.id = eventID;
									obligation.name = "event"+dateDay;
									document.getElementById(dateDay).appendChild(obligation);
								}
							} else {
								var obligation = document.createElement("p");
								obligation.innerHTML=curevent.title+" ("+curevent.time+")";
								var eventID = curevent.eventid;
								obligation.id = eventID;
								obligation.name = "event"+dateDay;
								document.getElementById(dateDay).appendChild(obligation);
							}
							// console.log(document.getElementById(eventID).id);
						}
					}
				}, false);
				xmlHttp.send("year=" + encodeURIComponent(dateYear) + "&month=" + encodeURIComponent(dateMonth) + "&day=" + encodeURIComponent(dateDay));
			
			}
			
			function decMon()
			{
				weeksRmv=currentMonth.getWeeks().length;
				var table = document.getElementById("calendar");
				for (i=0; i<weeksRmv;i++){
					table.deleteRow(1);
				}
				
				//decrement the month
				currentMonth = currentMonth.prevMonth();
				if (currentM == 0) {
					currentY--;
					currentM = 11;
				} else {
					currentM--;
				}
				//build the new calendar
				buildCalendar(currentMonth);
			}
			
			function incMon()
			{
				weeksRmv=currentMonth.getWeeks().length;
				var table = document.getElementById("calendar");
				for (i=0; i<weeksRmv;i++){
					table.deleteRow(1);
				}
				
				currentMonth = currentMonth.nextMonth();
				if (currentM == 11) {
					currentY++;
					currentM = 0;
				} else {
					currentM++;
				}
				//build the calendar based on this new month
				buildCalendar(currentMonth);
			}

			function reloadMon()
			{
				weeksRmv=currentMonth.getWeeks().length;
				var table = document.getElementById("calendar");
				for (i=0; i<weeksRmv;i++){
					table.deleteRow(1);
				}
				
				buildCalendar(currentMonth);
			}
		
			function openFormEvent(){
				eventFrm();
			}
			function openFormReg(){
				regFrm();
			}
			function logoutJS(){
				logoutIntermediate();
				alert("Logout Successful");
			}
		
			function test(){
				alert("buttiful");
			}
		</script>
<!--END-->

<!--JQuery-->
		<script>
	//Functions for when the document load
		$(document).ready(function(){
			$(".loggedIn").toggle();
		});
		
//Functions for popup forms
	//Regular Expressions;
		var regexUsername = /^\w+$/;
		
		$( function() {
			
	//login
		function login(){
			//send inputted data
			cur_username = $("#user").val();
			cur_passw = $("#passGuess").val();
			if (cur_username == "" || cur_passw == ""){
    				alert("Please enter your username or password!");
    				return;
  			}
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open("POST", "login.php", true);
			xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xmlHttp.send("user="+$("#user").val()+"&pass_guess="+$("#passGuess").val());
			
			//make an event listener for the response
            xmlHttp.addEventListener("load", ajaxCallback, false);
			
			//get response on whether or not login creation was successful
			function ajaxCallback(event) {
				// callback function body
				var data = JSON.parse(event.target.responseText);
				if(data.success){
					token = data.token;
					loggedIn = true;
					var userLoggedIn = $("#user").val();
					//if successful, toggle to fields to hide elements exclusive to not logged in users, and show elements exclusive to logged-in users
					$("fieldset.notLoggedIn").toggle();
					$(".loggedIn").toggle();
					alert("Login Successful");
					document.getElementById("userWelcome").innerHTML=userLoggedIn;
				}
				//if the login was a failure, prompt the user to double-check their entries
				else{
					alert("check username and password");
				}
			}
			reloadMon();
		}
		$( "#loginBUTT" ).button().on( "click", login);
	
	//Logout
		function logout(){
			//call the logout.php
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open("POST", "logout.php", true);
			xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xmlHttp.send();
			
			$("fieldset.notLoggedIn").toggle();
			$(".loggedIn").toggle();	
			reloadMon();
		}logoutIntermediate=logout;

			var userCreate = $( "#createUserForm" ).dialog({
				    autoOpen: false,
					modal: true,
					buttons: {
							"Register": addUser,
							Cancel: function() {
							userCreate.dialog( "close" );
							}
						}
			});
			//function to open the register user form
			function openRegForm() {
				userCreate.dialog( "open" );
			} regFrm=openRegForm;
			
			//function to take user inputted username and passwords and try to enter them into the database
			function addUser(){
                //Set default true
				var valid=true;
				//I just wanted jquery to stop yelling at me
				var newPass="notapassword";
				
				//Check to see if username is valid
				var newUsername= $("#regUsername").val();
				if(regexUsername.test(newUsername)){
					valid=true;
					document.getElementById("invalidUsernameWarning").innerHTML="";
				}
				else{
					valid = false;
					document.getElementById("invalidUsernameWarning").innerHTML="Warning:  Invalid Username";
				}
				
				//check to see if passwords match
				if($("#newPass1").val()===$("#newPass2").val()){
					valid=true;
					document.getElementById("unmatchedPasswordsWarning").innerHTML="";
					newPass=$("#newPass2").val();
				}
				else{
					valid = false;
					document.getElementById("unmatchedPasswordsWarning").innerHTML="Please make sure your passwords match.";
				}
				if (newUsername == "" || newPass == ""){
    				alert("Please enter your username or password!");
    				return;
  				}
				//if all of tests above passed
				if(valid){
					var xmlHttp = new XMLHttpRequest();
					xmlHttp.open("POST", "newuser.php", true);
					xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xmlHttp.send("user="+newUsername+"&password="+newPass);
					xmlHttp.addEventListener("load", ajaxCallbackNewUser, false);
					//see if the php says that the username is available
				}
			}
			function ajaxCallbackNewUser() {
				// callback function body
				var data = JSON.parse(event.target.responseText);
				if(!data.success){
					document.getElementById("invalidUsernameWarning").innerHTML="Account creation failed";
					document.getElementById("unmatchedPasswordsWarning").innerHTML=data.message;
				}
				//If login was a success, thank the user and prompt them to log in
				else{
					alert("Registration successful.  Please log in.");
					userCreate.dialog( "close" );
				}
			}
			
	//Create Event Form
			var eventCreate = $( "#createEventForm" ).dialog({
				    autoOpen: false,
					height: 350,
					width: 350,
					modal: true,
					buttons: {
							"Create Event": addEvent,
							Cancel: function() {
							eventCreate.dialog( "close" );
							}
						}
			});
			function openEventForm() {
				var type = $(event.target)[0].name;
				if (typeof type !== 'undefined') {
					openEventEdit();
					return;
				}
				var eday =  $(event.target)[0].id;
				var eDate = "New event on " + eday + " " + $("#dispMon").text();
				document.getElementById("eventDate").value=eday;
				document.getElementById("eventDate").innerHTML=eDate;
				document.getElementById("eName").innerHTML="";
				document.getElementById("eTime").innerHTML="";
				document.getElementById("eTag").innerHTML="";
				eventCreate.dialog( "open" );			
			} eventFrm=openEventForm;
			
			//$( ".day" ).on( "click", openEventForm);
			//function to add event
			function addEvent(){
				var eday = document.getElementById("eventDate").value;
				var eventName= $("#eName").val();
				var eventTime=$("#eTime").val();
				var eventTag=$("#eTag").val();
				if (eventName == "" || eventTime == ""){
    				alert("Event title and time cannot be left null!");
    				return;
  				}
				var thisDay=eday;
				var thisMonth=currentM + 1;
				var sending = "token="+token+"&title="+eventName+"&time="+eventTime+"&tag="+eventTag+"&year="+currentY+"&month="+thisMonth+"&day="+thisDay;
				var xmlHttp = new XMLHttpRequest();
				xmlHttp.open("POST", "newevent.php", true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.send(sending);
				xmlHttp.addEventListener("load", ajaxCallbackNewEvent, false);
			}
			function ajaxCallbackNewEvent() {
				// callback function body
				var data = JSON.parse(event.target.responseText);
				if(data.success){
					reloadMon();
					eventCreate.dialog("close");
				}
				else{
					alert("Error");
					eventCreate.dialog("close");
				}
			}
			var eventEdit = $( "#editEventForm" ).dialog({
				    autoOpen: false,
					height: 350,
					width: 350,
					modal: true,
					buttons: {
							"Alter Event": editEvent,
							"Delete Event": deleteEvent,
							Cancel: function() {
							eventEdit.dialog( "close" );
							}
						}
			});


			function openEventEdit() {
				var curevent =  $(event.target)[0].id;
				var eday = $(event.target)[0].name.substring(5);
				document.getElementById("editDate").value=eday;
				document.getElementById("editID").value=curevent;
				var msg = "Editing #" + curevent + " event:";
				document.getElementById("editName").innerHTML="";
				document.getElementById("editTime").innerHTML="";
				document.getElementById("editTag").innerHTML="";
				eventEdit.dialog("open");
			} eventEditFrm=openEventEdit;

			function editEvent(){
				var eday = document.getElementById("editDate").value;
				var eventID = document.getElementById("editID").value;
				var eventName= $("#editName").val();
				var eventTime=$("#editTime").val();
				var eventTag=$("#editTag").val();
				if (eventName == "" || eventTime == ""){
    				alert("Event title and time cannot be left null!");
    				return;
  				}
				var thisDay=eday;
				var thisMonth=currentM + 1;
				var sending = "token="+token+"&eventid="+eventID+"&title="+eventName+"&time="+eventTime+"&tag="+eventTag+"&year="+currentY+"&month="+thisMonth+"&day="+thisDay;
				//console.log(sending);
				var xmlHttp = new XMLHttpRequest();
				xmlHttp.open("POST", "editevent.php", true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.send(sending);
				xmlHttp.addEventListener("load", ajaxCallbackEditEvent, false);
			}
			function ajaxCallbackEditEvent() {
				// callback function body
				// eventEdit.dialog("close");
				var data = JSON.parse(event.target.responseText);
				if(data.success){
					reloadMon();
					// console.log(data.msg);
					eventEdit.dialog("close");
				}
				else{
					alert("Error");
					eventEdit.dialog("close");
				}
			}

			function deleteEvent(){
				var eventID = document.getElementById("editID").value;
				var sending = "token="+token+"&eventid="+eventID;
				console.log(sending);
				var xmlHttp = new XMLHttpRequest();
				xmlHttp.open("POST", "deleteevent.php", true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.send(sending);
				xmlHttp.addEventListener("load", ajaxCallbackEditEvent, false);
			}

			var calShare = $( "#calShareForm" ).dialog({
				    autoOpen: false,
					modal: true,
					buttons: {
							"This is still a TODO functionality": function() {
							calShare.dialog( "close" );
							}
						}
			});
			function openShareForm() {
				calShare.dialog( "open" );
			}

			$( "#shareCal" ).button().on( "click", openShareForm);
			
			function fetchEvents(date) {
				var day = date;
				var events;
				var xmlHttp = new XMLHttpRequest();
					xmlHttp.open("POST", "displayevents.php", true);
					xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xmlHttp.addEventListener("load", ajaxCallbackFetchEvents, false);
					xmlHttp.send();
				return events;
			}
			function ajaxCallbackFetchEvents() {
				var data = JSON.parse(event.target.responseText);
				events=data.events[day];
			}
		});
		</script>
	 
	</div></body>
</html>
