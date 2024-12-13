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
	<h1>All Inquiries</h1>
	<?php $getAllInquiries = getAllInquiries($pdo);?>
	<?php foreach ($getAllInquiries as $row) { ?>
	<div class="inquiryContainer" style="border-style: solid; padding: 25px; margin-top: 10px;">
		<h2><?php echo $row['username']; ?></h2>
		<i><?php echo $row['date_added']; ?></i>
		<p><?php echo $row['description']; ?></p>
		<a href="reply-to-inquiry.php?inquiry_id=<?php echo $row['inquiry_id']; ?>" style="float: right;">See All Replies</a>
	</div>
	<?php } ?>
</body>
</html>