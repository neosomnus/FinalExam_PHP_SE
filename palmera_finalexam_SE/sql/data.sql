CREATE TABLE inquiries ( -- A typical user can only create inquiries
	inquiry_id INT AUTO_INCREMENT PRIMARY KEY,
	description TEXT,
	user_id INT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE replies ( -- An admin can only reply to inquiries
	reply_id INT AUTO_INCREMENT PRIMARY KEY,
	description TEXT,
	inquiry_id INT,
	user_id INT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


