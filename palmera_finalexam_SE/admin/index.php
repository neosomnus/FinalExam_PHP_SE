<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 0) {
	header("Location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<?php include 'navbar.php'; ?>

	<div class="searchForm">
		<form action="index.php" method="GET">
			<p>
				<input type="text" name="searchQuery" placeholder="Search here">
				<input type="submit" name="searchBtn" value="Search">
				<h3><a href="index.php">Search Again</a></h3>	
			</p>
		</form>
	</div>

	<?php  
	if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

		if ($_SESSION['status'] == "200") {
			echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
		}

		else {
			echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>";	
		}

	}
	unset($_SESSION['message']);
	unset($_SESSION['status']);
	?>

	<div class="tableClass">
		<table style="width: 100%;" cellpadding="20"> 
			<tr>
				<th>Job</th>
				<th>Head Manager</th>
				<th>Contact Number</th>
				<th>Date Added</th>
				<th>Added By</th>
				<th>Last Updated</th>
				<th>Last Updated By</th>
				<th>Action</th>
			</tr>
			<?php if (!isset($_GET['searchBtn'])) { ?>
				<?php $getAllBranches = getAllBranches($pdo); ?>
				<?php foreach ($getAllBranches as $row) { ?>
				<tr>
					<td><?php echo $row['address']; ?></td>
					<td><?php echo $row['head_manager']; ?></td>
					<td><?php echo $row['contact_number']; ?></td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td><?php echo $row['last_updated_by']; ?></td>
					<td>
						<a href="updatebranch.php?branch_id=<?php echo $row['branch_id']; ?>">Update</a>
						<a href="deletebranch.php?branch_id=<?php echo $row['branch_id']; ?>">Delete</a>
					</td>
				</tr>
				<?php } ?>
			<?php } else { ?>
				<?php $getAllBranchesBySearch = getAllBranchesBySearch($pdo, $_GET['searchQuery']); ?>
				<?php foreach ($getAllBranchesBySearch as $row) { ?>
				<tr>
					<td><?php echo $row['address']; ?></td>
					<td><?php echo $row['head_manager']; ?></td>
					<td><?php echo $row['contact_number']; ?></td>
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_updated']; ?></td>
					<td><?php echo $row['last_updated_by']; ?></td>
					<td>
						<a href="updatebranch.php?branch_id=<?php echo $row['branch_id']; ?>">Update</a>
						<a href="deletebranch.php?branch_id=<?php echo $row['branch_id']; ?>">Delete</a>
					</td>
				</tr>
				<?php } ?>
			<?php } ?>
		</table>
	</div>

    <h1 style="text-align: center;">Suspend Accounts</h1>
	<?php $getAllUsers = getAllUsers($pdo); ?>
	<?php foreach ($getAllUsers as $row) { ?>
	<div class="container" style="display: flex; justify-content: center;">
		<div class="userInfo" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%; margin-top: 25px; padding: 50px;">
			<h3>Username: <span style="color: blue"><?php echo $row['username']; ?></span></h3>
			<h3>First Name: <span style="color: blue"><?php echo $row['first_name']; ?></span></h3>
			<h3>Last Name: <span style="color: blue"><?php echo $row['last_name']; ?></span></h3>
			<h3>Date Joined: <span style="color: blue"><?php echo $row['date_added']; ?></span></h3>

			<?php if ($row['is_suspended'] == 0) { ?>
				<a href="suspend-an-account.php?user_id=<?php echo $row['user_id']; ?>" style="float: right;">Suspend Account</a>
			<?php } else { ?>
				<a href="suspend-an-account.php?user_id=<?php echo $row['user_id']; ?>" style="float: right;">Unsuspend Account</a>
			<?php } ?>

		</div>
	</div>
	<?php } ?>
	
</body>
</html>