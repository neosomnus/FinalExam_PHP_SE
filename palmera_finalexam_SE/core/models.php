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

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
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

function getAllBranches($pdo) {
	$sql = "SELECT * FROM branches ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getUserByID($pdo, $user_id) {
	$sql = "SELECT * FROM user_accounts WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


function insertInquiry($pdo, $description, $user_id) {
	$sql = "INSERT INTO inquiries (description, user_id) VALUES(?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$description, $user_id]);
	if ($executeQuery) {
		return true;
	}
}

function getInquiryByID($pdo, $inquiry_id) {
	$sql = "SELECT * FROM inquiries WHERE inquiry_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$inquiry_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function editInquiry($pdo, $description, $inquiry_id) {
	$sql = "UPDATE inquiries SET description = ? WHERE inquiry_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$description, $inquiry_id]);
	if ($executeQuery) {
		return true;
	}
}

function deleteInquiry($pdo, $inquiry_id) {
	$sql = "DELETE FROM inquiries WHERE inquiry_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$inquiry_id]);
	if ($executeQuery) {
		return true;
	}
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