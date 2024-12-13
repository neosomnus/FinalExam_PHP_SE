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
	<div class="infoContainer" style=" text-align: center;">
		<h1 style="margin-top: 10px;">Delete an Inquiry</h1>
		<h1 style="color: red;">Are you sure you want to delete this?</h1>
		<?php $getInquiryByID = getInquiryByID($pdo, $_GET['inquiry_id']); ?>
			<h1>Description: <?php echo $getInquiryByID['description']; ?></h1>
		</div>
		<form action="core/handleForms.php" method="POST">
			<p>
				<input type="hidden" name="inquiry_id" value="<?php echo $getInquiryByID['inquiry_id']; ?>">
				<input type="submit" name="deleteInquiryBtn" style="margin-top: 25px; width: 100%;" value="Delete">
			</p>
		</form>
	</div>
	
</body>
</html>