<?php  

require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function getUserByID($pdo, $user_id) {
	$sql = "SELECT * FROM user_accounts WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function insertNewUser($pdo, $username, $first_name, $last_name, $password, $is_admin) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password, is_admin) 
		VALUES (?,?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password, $is_admin])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}

function getAllAdmins($pdo) {
	$sql = "SELECT * FROM user_accounts 
			WHERE is_admin = 1";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts 
			WHERE is_admin = 0";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllBranches($pdo) {
	$sql = "SELECT * FROM branches";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllBranchesBySearch($pdo, $search_query) {
	$sql = "SELECT * FROM branches WHERE 
			CONCAT(address,head_manager,
				contact_number,
				date_added,added_by,
				last_updated,
				last_updated_by) 
			LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%".$search_query."%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getBranchByID($pdo, $branch_id) {
	$sql = "SELECT * FROM branches WHERE branch_id = ?";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute([$branch_id])) {
		return $stmt->fetch();
	}
}

function insertAnActivityLog($pdo, $operation, $branch_id, $address, 
		$head_manager, $contact_number, $username) {

	$sql = "INSERT INTO activity_logs (operation, branch_id, address, 
		head_manager, contact_number, username) VALUES(?,?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$operation, $branch_id, $address, 
		$head_manager, $contact_number, $username]);

	if ($executeQuery) {
		return true;
	}

}

function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs 
			ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}

function insertABranch($pdo, $address, $head_manager, $contact_number, $added_by) {
	$response = array();
	$sql = "INSERT INTO branches (address, head_manager, contact_number, added_by) VALUES(?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$insertBranch = $stmt->execute([$address, $head_manager, $contact_number, $added_by]);

	if ($insertBranch) {
		$findInsertedItemSQL = "SELECT * FROM branches ORDER BY date_added DESC LIMIT 1";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute();
		$getBranchID = $stmtfindInsertedItemSQL->fetch();

		$insertAnActivityLog = insertAnActivityLog($pdo, "INSERT", $getBranchID['branch_id'], 
			$getBranchID['address'], $getBranchID['head_manager'], 
			$getBranchID['contact_number'], $_SESSION['username']);

		if ($insertAnActivityLog) {
			$response = array(
				"status" =>"200",
				"message"=>"Branch addedd successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
		
	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"Insertion of data failed!"
		);

	}

	return $response;
}

function updateBranch($pdo, $address, $head_manager, $contact_number, 
	$last_updated, $last_updated_by, $branch_id) {

	$response = array();
	$sql = "UPDATE branches
			SET address = ?,
				head_manager = ?,
				contact_number = ?, 
				last_updated = ?, 
				last_updated_by = ? 
			WHERE branch_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$updateBranch = $stmt->execute([$address, $head_manager, $contact_number, 
	$last_updated, $last_updated_by, $branch_id]);

	if ($updateBranch) {

		$findInsertedItemSQL = "SELECT * FROM branches WHERE branch_id = ?";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute([$branch_id]);
		$getBranchID = $stmtfindInsertedItemSQL->fetch(); 

		$insertAnActivityLog = insertAnActivityLog($pdo, "UPDATE", $getBranchID['branch_id'], 
			$getBranchID['address'], $getBranchID['head_manager'], 
			$getBranchID['contact_number'], $_SESSION['username']);

		if ($insertAnActivityLog) {

			$response = array(
				"status" =>"200",
				"message"=>"Updated the branch successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}

	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;

}


function deleteABranch($pdo, $branch_id) {
	$response = array();
	$sql = "SELECT * FROM branches WHERE branch_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$branch_id]);
	$getBranchByID = $stmt->fetch();

	$insertAnActivityLog = insertAnActivityLog($pdo, "DELETE", $getBranchByID['branch_id'], 
		$getBranchByID['address'], $getBranchByID['head_manager'], 
		$getBranchByID['contact_number'], $_SESSION['username']);

	if ($insertAnActivityLog) {
		$deleteSql = "DELETE FROM branches WHERE branch_id = ?";
		$deleteStmt = $pdo->prepare($deleteSql);
		$deleteQuery = $deleteStmt->execute([$branch_id]);

		if ($deleteQuery) {
			$response = array(
				"status" =>"200",
				"message"=>"Deleted the branch successfully!"
			);
		}
		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
	}
	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;
}

function getAllInquiries($pdo, $inquiry_id=NULL) {

	if (!empty($inquiry_id)) {
		$sql = "SELECT 
					user_accounts.username AS username,
					inquiries.inquiry_id AS inquiry_id,
					inquiries.description AS description,
					inquiries.date_added AS date_added
				FROM inquiries
				JOIN user_accounts 
				ON inquiries.user_id = user_accounts.user_id
				WHERE inquiries.inquiry_id = ?
				";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$inquiry_id]);

		if ($executeQuery) {
			return $stmt->fetch();
		}

	}
	else {
		$sql = "SELECT 
					user_accounts.username AS username,
					inquiries.inquiry_id AS inquiry_id,
					inquiries.description AS description,
					inquiries.date_added AS date_added
				FROM inquiries
				JOIN user_accounts 
				ON inquiries.user_id = user_accounts.user_id
				ORDER BY inquiries.date_added DESC
				";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute();

		if ($executeQuery) {
			return $stmt->fetchAll();
		}

	}
}


function getAllRepliesByInquiry($pdo, $inquiry_id) {
	$sql = "SELECT 
				user_accounts.username AS username,
				replies.reply_id AS reply_id,
				replies.description AS description,
				replies.date_added AS date_added
			FROM replies
			JOIN user_accounts 
			ON replies.user_id = user_accounts.user_id
			JOIN inquiries 
			ON replies.inquiry_id = inquiries.inquiry_id
			WHERE inquiries.inquiry_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$inquiry_id]);

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function insertReply($pdo, $description, $inquiry_id, $user_id) {
	$sql = "INSERT INTO replies (description, inquiry_id, user_id) VALUES(?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$description, $inquiry_id, $user_id]);
	if ($executeQuery) {
		return true;
	}
}


function getReplyByID($pdo, $reply_id) {
	$sql = "SELECT * FROM replies WHERE reply_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$reply_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function editReply($pdo, $description, $reply_id) {
	$sql = "UPDATE replies SET description = ? WHERE reply_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$description, $reply_id]);
	if ($executeQuery) {
		return true;
	}
}

function deleteReply($pdo, $reply_id) {
	$sql = "DELETE FROM replies WHERE reply_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$reply_id]);
	if ($executeQuery) {
		return true;
	}
}


function suspendAccount($pdo, $user_id) {
	$sql = "UPDATE user_accounts SET is_suspended = 1
			WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return true;
	}
}

function unsuspendAccount($pdo, $user_id) {
	$sql = "UPDATE user_accounts SET is_suspended = 0
			WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return true;
	}
}

?>