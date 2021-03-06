<?php
require_once('admin-includes/class.user.php');
$user = new User();
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

// Check for a Registration Request
	if(isset($_GET['reg'])) {
		// Setup the Registration Page and Detection of the Current Session
		$_SESSION['man_redirect'] = "adminPage_users.inc.php?view";
		header("Location: registration.php");
		exit();
	}

// Delete the User on Request
	if(isset($_GET['delete'])){
		$user->delete_user($_GET['delete']);
		header("Location: adminPage_users.inc.php?edit");
		exit();
	}

// Setup for an Edit Mode on Request
	$editMode = false;
	if(isset($_GET['edit'])) {
		$editMode = true;
	}
	if(isset($_GET['edit']) && isset($_GET['uid']) ){
		header("Location: adminPage_editUser.inc.php?url=adminPage_users.inc.php%3Fedit&uid=".$_GET['uid']);
		exit();
	}

?>
<body>
	
	<ul id="social_side_links">
		<?php if(!isset($_GET['edit'])){
				echo '<li><a style="background-color: #54d063;" href="?edit" title="Edit Mode"><!-- add target="_blank" to open in new tab. --><img src="assets/images/pencil.svg" /></a></li>';
		} ?>
		<li><a style="background-color: #54aed0" href="./?reg" title="New User"><img src="images/create-group-button.svg" /></a></li>
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
							echo "<td>";
								$uR = $user->fetch_role($userI['uid']);
								switch($uR){
										case "ADMIN":
											echo '&nbsp;<i class="fa fa-id-badge" aria-hidden="true" title="Administrator"></i>&nbsp;&nbsp;';
										break;

										case "MODERATOR":
											echo '<i class="fa fa-user-circle" aria-hidden="true" title="Moderator"></i>&nbsp;&nbsp;';
										break;

										case "MEMBER":
											echo '<i class="fa fa-user-circle-o" aria-hidden="true" title="Member"></i>&nbsp;&nbsp;';
										break;

										case "GUEST":
											echo '<i class="fa fa-user-o" aria-hidden="true" title="Guest"></i>&nbsp;&nbsp;';
										break;

										case "NONE":
											echo '<i class="fa fa-user-times" aria-hidden="true" title="User has no Perms"></i>&nbsp;&nbsp;';
										break;

										default:
										break;
								}

								if($editMode == true){
									echo "<a href=\"?edit&uid=".$userI['uid']."\"><i class=\"fa fa-pencil-square\" aria-hidden=\"true\"></i>&nbsp;Edit User</a>";
								}
							echo "</td>";
						echo "</tr>";
					}
				?>
			  </tbody>
			</table>
	   </div>
	</div>
</body>
</html>
