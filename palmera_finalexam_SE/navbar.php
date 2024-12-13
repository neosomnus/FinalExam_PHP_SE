<div class="navbar" style="text-align: center; margin-bottom: 50px;">
		<img src="companyAlogo.png" alt=""width="150" height="150">
	<h1>Hello there! Welcome to Company A, <span style="color: blue;"><?php echo $_SESSION['username']; ?></span></h1>
	<h3>
		<a href="index.php">Home</a>
		<a href="profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Profile</a>
		<a href="inquiries.php">Inquiries</a>
		<a href="send-an-inquiry.php">Send An Inquiry</a>
		<a href="core/handleForms.php?logoutUserBtn=1">Logout</a>	
	</h3>	
</div>