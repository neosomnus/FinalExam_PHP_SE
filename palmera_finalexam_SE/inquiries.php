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
	
	<?php $getAllInquiries = getAllInquiries($pdo);?>
	<?php foreach ($getAllInquiries as $row) { ?>
	<div class="inquiry" style="display: flex; justify-content: center; margin-top: 25px;">
		<div class="inquiryContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%; padding: 25px;">
			<h2><?php echo $row['username']; ?></h2>
			<i><?php echo $row['date_added']; ?></i>
			<p><?php echo $row['description']; ?></p>
			
			<div class="buttons" style="float:right;">
				<a href="see-all-replies.php?inquiry_id=<?php echo $row['inquiry_id']; ?>">See All Replies</a>

				<?php if ($_SESSION['username'] == $row['username']) { ?>
					<a href="editinquiry.php?inquiry_id=<?php echo $row['inquiry_id']; ?>">Edit</a>
					<a href="deleteinquiry.php?inquiry_id=<?php echo $row['inquiry_id']; ?>">Delete</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>

</body>
</html>