<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 1) {
	header("Location: admin/index.php");
}

if ($getUserByID['is_suspended'] == 1) {
	header("Location: suspended-account-error.php");
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
	<h1 style="text-align: center;">Hello there!! <span style="color: blue"><?php echo $_SESSION['username']; ?></span>. Here are all the available jobs in Company A</h1>

	<?php $getAllBranches = getAllBranches($pdo); ?>
	<?php foreach ($getAllBranches as $row) { ?>
	<div class="branches" style="display: flex; justify-content: center; margin-top: 25px;">
		<div class="branchContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%; padding: 25px;">
			<h3>Job: <?php echo $row['address']; ?></h3>
			<h3>Head Manager: <?php echo $row['head_manager']; ?></h3>
			<h3>Contact Number: <?php echo $row['contact_number']; ?></h3>
		</div>
	</div>
	<?php } ?>

</body>
</html>