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
	<h1 style="text-align:center;">All Inquiries</h1>

	<div class="branches" style="display: flex; justify-content: center; margin-top: 25px;">
		<div class="branchContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%; padding: 25px;">
			<?php $getAllInquiries = getAllInquiries($pdo, $_GET['inquiry_id']); ?>
			<h2><?php echo $getAllInquiries['username']; ?></h2>
			<i><?php echo $getAllInquiries['date_added']; ?></i>
			<p><?php echo $getAllInquiries['description']; ?></p>
			<hr>
			<h1>All Replies</h1>
			<?php $getAllRepliesByInquiry = getAllRepliesByInquiry($pdo, $_GET['inquiry_id']); ?>
			<?php foreach ($getAllRepliesByInquiry as $row) { ?>
			<div class="reply" style="margin-left: 25px; margin-top: 10px;">
				<h3><?php echo $row['username'];?><span style="color:red;"> (Admin)</span></h3>
				<i><?php echo $row['date_added']; ?></i>
				<p><?php echo $row['description']; ?></p>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
</html>