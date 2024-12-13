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
	<div class="inquiryContainer" style="border-style: solid; padding: 25px;">
		<?php $getAllInquiries = getAllInquiries($pdo, $_GET['inquiry_id']); ?>
		<h2><?php echo $getAllInquiries['username']; ?></h2>
		<i><?php echo $getAllInquiries['date_added']; ?></i>
		<p><?php echo $getAllInquiries['description']; ?></p>
		<hr>
		<div class="replyContainer" style="margin-left: 25px;">
			<h1>All Replies</h1>
			<?php $getAllRepliesByInquiry = getAllRepliesByInquiry($pdo, $_GET['inquiry_id']);  ?>
			<?php foreach ($getAllRepliesByInquiry as $row) { ?>
			<div class="reply" style="padding: 10px;">
				<h3><?php echo $row['username']; ?></h3>
				<i><?php echo $row['date_added']; ?></i>
				<p><?php echo $row['description']; ?></p>

				<?php if ($_SESSION['username'] == $row['username']) { ?>
					<div class="editAndDelete" style="float:right;">
						<a href="editreply.php?reply_id=<?php echo $row['reply_id'] ?>">Edit</a>
						<a href="deletereply.php?reply_id=<?php echo $row['reply_id'] ?>">Delete</a>
					</div>
				<?php } ?>

			</div>	
			<?php } ?>
			<form action="index.php" method="POST">
				<p>
					<input type="text" name="reply_description" placeholder="Reply here" style="width: 100%">
					<input type="hidden" name="inquiry_id" value="<?php echo $_GET['inquiry_id']; ?>">
					<input type="submit" name="insertReplyBtn" value="Reply" style="float: right; height: auto;">
				</p>
			</form>
		</div>
	</div>
</body>
</html>