<div class="greeting">
	<h1>Hello there! Welcome Admin, <span style="color: blue;"><?php echo $_SESSION['username']; ?></span></h1>
</div>

<div class="navbar">
	<h3>
		<a href="index.php">Home | </a>
        <a href="profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Your Profile | </a>
		<a href="insertbranch.php">Add New Job | </a>
		<a href="register-an-admin.php">Add New Admin | </a>
		<a href="alladmins.php">All Admins | </a>
		<a href="allusers.php">All Users | </a>
		<a href="allinquiries.php">All Inquiries | </a>
		<a href="activitylogs.php">Activity Logs | </a>
		<a href="core/handleForms.php?logoutUserBtn=1">Logout |</a>	
	</h3>	
</div>