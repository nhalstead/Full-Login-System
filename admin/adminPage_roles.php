<?php
require_once('admin-includes/class.user.php');
$user = User::getInstance();
	if (!$user->get_session()){
	   header("Location: login.php");
	}
	$uid = $_SESSION['uid'];
	$userData = $user->get_user_by_id($uid);

	// Check User Perms
	if(!$user->has_role($uid, array("ADMIN", "MODERATOR") )){
	  header("Location: home.php");
	}

	function doTell(&$in, $default = ""){ return isset($in)?$in:$default; }

?>
<head>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" integrity="sha256-NuCn4IvuZXdBaFKJOAcsU2Q3ZpwbdFisd5dux4jkQ5w=" crossorigin="anonymous">
    <link rel="stylesheet" href="../Full-Login-System/assets-system/css/bootstrap.css"/>
		<link rel="stylesheet" href="../Assets-System/css/user.css"/>
</head>
<body>
	<link rel="stylesheet" href="../Assets-System/css/admin.css"/>
	<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container">
			<a class="navbar-left" href="../users/home.php">Home</a>
			<?php
				// Offer the Admin Page if Admin
				if($user->has_role($uid, array("ADMIN", "MODERATOR") )){
					echo '<a class="navbar-left" href="adminPage.php">Mgr Page</a>';
				}
			?>
			<a class="navbar-right" href="../users/home.php?q=logout">LOGOUT</a>
	  </div>
	</nav>
	<link rel="stylesheet" href="../assets-system/css/sidebar.css"/>
	<ul id="social_side_links">
		<li><a style="background-color: #54aed0" href="./?reg"><img src="images/create-group-button.svg" /></a></li>
	</ul>
	<div class="container">
		<div class="row">
			<table class="table">
			  <thead>
				<tr>
				  <th scope="col">#</th>
				  <th scope="col">First Name</th>
				  <th scope="col">Last Name</th>
				  <th scope="col">Username</th>
					<?php
						if($editMode == true){
							echo '<th scope="col">&nbsp;</th>';
						}
					?>
				</tr>
			  </thead>
			  <tbody>
				<?php
					$usersList = $user->fetch_all_users(); // Grab User Listing

					foreach($usersList as $i => $userI ){
						echo "<tr>";
							echo "<th scope=\"row\">".$userI['uid']."</th>";
							echo "<td>".$userI['fname']."</td>";
							echo "<td>".$userI['lname']."</td>";
							echo "<td>".$userI['uname']."</td>";
							if($editMode == true){
								echo "<td><a href=\"?edit&uid=".$userI['uid']."\"><i class=\"fa fa-pencil-square\" aria-hidden=\"true\"></i>&nbsp;Edit User</a></td>";
							}
						echo "</tr>";
					}
				?>
			  </tbody>
			</table>
	   </div>
	</div>
    <script src="../Full-Login-System/assets-system/js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
