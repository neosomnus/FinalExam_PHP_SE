<?php  
require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
	$is_admin = true;

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT), $is_admin);
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../index.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register-an-admin.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register-an-admin.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register-an-admin.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location: ../index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_POST['insertNewAdminBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
	$is_admin = true;

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT), $is_admin);
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../alladmins.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register-an-admin.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register-an-admin.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register-an-admin.php");
	}
}

if (isset($_POST['insertNewBranchBtn'])) {
	$address = trim($_POST['address']);
	$head_manager = trim($_POST['head_manager']);
	$contact_number = trim($_POST['contact_number']);

	if (!empty($address) && !empty($head_manager) && !empty($contact_number)) {
		$insertABranch = insertABranch($pdo, $address, $head_manager, 
			$contact_number, $_SESSION['username']);
		$_SESSION['status'] =  $insertABranch['status']; 
		$_SESSION['message'] =  $insertABranch['message']; 
		header("Location: ../index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../index.php");
	}

}

if (isset($_POST['updateBranchBtn'])) {

	$address = $_POST['address'];
	$head_manager = $_POST['head_manager'];
	$contact_number = $_POST['contact_number'];
	$date = date('Y-m-d H:i:s');

	if (!empty($address) && !empty($head_manager) && !empty($contact_number)) {

		$updateBranch = updateBranch($pdo, $address, $head_manager, $contact_number, 
			$date, $_SESSION['username'], $_GET['branch_id']);

		$_SESSION['message'] = $updateBranch['message'];
		$_SESSION['status'] = $updateBranch['status'];
		header("Location: ../index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_POST['deleteBranchBtn'])) {
	$branch_id = $_GET['branch_id'];

	if (!empty($branch_id)) {
		$deleteBranch = deleteABranch($pdo, $branch_id);
		$_SESSION['message'] = $deleteBranch['message'];
		$_SESSION['status'] = $deleteBranch['status'];
		header("Location: ../index.php");
	}
}

if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['username']);
	header("Location: ../login.php");
}

if (isset($_POST['insertReplyBtn'])) {
	$reply_description = $_POST['reply_description'];
	$inquiry_id = $_POST['inquiry_id'];
	$insertQuery = insertReply($pdo, $reply_description, $inquiry_id, $_SESSION['user_id']);
	if ($insertQuery) {
		header("Location: ../admin/reply-to-inquiry.php?inquiry_id=" . $inquiry_id);
	}
}

if (isset($_POST['updateReplyBtn'])) {
	$reply_description = $_POST['reply_description'];
	$reply_id = $_POST['reply_id'];
	$inquiry_id = $_POST['inquiry_id'];
	$editQuery = editReply($pdo, $reply_description, $reply_id);
	if ($editQuery) {
		header("Location: ../admin/reply-to-inquiry.php?inquiry_id=" . $inquiry_id);
	}
}

if (isset($_POST['deleteReplyBtn'])) {
	$reply_id = $_POST['reply_id'];
	$inquiry_id = $_POST['inquiry_id'];
	$deleteQuery = deleteReply($pdo, $reply_id);

	if ($deleteQuery) {
		header("Location: ../admin/reply-to-inquiry.php?inquiry_id=" . $inquiry_id);
	}

}

if (isset($_POST['suspendAccountBtn'])) {
	$user_id = $_POST['user_id'];
	$suspendAccount = suspendAccount($pdo, $user_id);

	if ($suspendAccount) {
		header("Location: ../index.php");
	}
}

if (isset($_POST['unsuspendAccountBtn'])) {
	$user_id = $_POST['user_id'];
	$unsuspendAccount = unsuspendAccount($pdo, $user_id);

	if ($unsuspendAccount) {
		header("Location: ../index.php");
	}
}

?>