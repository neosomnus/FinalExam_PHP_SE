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

	<?php $getReplyByID = getReplyByID($pdo, $_GET['reply_id']); ?>

	<div class="deleteDiv" style="border-style: solid; background-color: #FFB6C1; color: red;">
		<h1>Are you sure you want to delete this?</h1>
	</div>

	<div class="replyInfo" style="border-style: solid;">
		<h1>Description: <?php echo $getReplyByID['description']; ?></h1>
	</div>
	<form action="index.php" method="POST">
		<p>
			<input type="hidden" name="reply_id" value="<?php echo $getReplyByID['reply_id']; ?>">
			<input type="hidden" name="inquiry_id" value="<?php echo $getReplyByID['inquiry_id']; ?>">
			<input type="submit" name="deleteReplyBtn" value="Delete" style="float: right; height: auto;">
		</p>
	</form>

</body>
</html>