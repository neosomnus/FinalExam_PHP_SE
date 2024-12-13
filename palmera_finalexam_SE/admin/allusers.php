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

    <div class="container" style="display: flex; justify-content: center;">
		<div class="allUsers" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 25%; text-align: center;">
			<h1>All Users</h1>
			<ul style="display: flex; flex-direction: column; align-items: center; list-style-type: disc; padding: 0;">
				<?php $getAllUsers = getAllUsers($pdo); ?>
				<?php foreach ($getAllUsers as $row) { ?>
					<li style="margin-top: 10px;"><a href="profile.php?user_id=<?php echo $row['user_id']; ?>"><?php echo $row['username']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
    
	<h2>All Admins</h2>
	<ul>
		<?php $getAllUsers = getAllUsers($pdo);?>
		<?php foreach ($getAllUsers as $row) { ?>
			<li><?php echo $row['username']; ?></li>
		<?php } ?>
	</ul>
</body>
</html>