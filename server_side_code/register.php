<?php

require("config.inc.php");

if (!empty($_POST)) {

	$response = array("error" => FALSE);
    
    $query = " SELECT * FROM users WHERE email = :email";
    //now lets update what :user should be
    $query_params = array(
        ':email' => $_POST['email']
    );

    try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {

        $response["error"] = TRUE;
        $response["error_msg"] = "Database Error1. Please Try Again!";
        die(json_encode($response));
    }
    
    $row = $stmt->fetch();
    if ($row) {
		$response["error"] = TRUE;
        $response["error_msg"] = "I'm sorry, this email is already in use";
        die(json_encode($response));
	} else {
		$query = "INSERT INTO users ( name, email, fcm_registered_id, created_at ) VALUES ( :name, :email, :fcm_id, NOW() ) ";
		
		$query_params = array(
			':name' => $_POST['name'],
			':email' => $_POST['email'],
			':fcm_id' => $_POST['fcm_id']
		);

		try {
			$stmt   = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch (PDOException $ex) {

			$response["error"] = TRUE;
			$response["error_msg"] = "Database Error2. Please Try Again!";
			die(json_encode($response));
		}
		
		$response["error"] = FALSE;
		$response["error_msg"] = "Register successful!";
		echo json_encode($response);
	}
    
} else {
?>
	<h1>Register</h1> 
	<form action="register.php" method="post"> 
	    name:<br /> 
	    <input type="text" name="name" value="" /> 
	    <br /><br /> 
		email:<br /> 
	    <input type="text" name="email" value="" /> 
	    <br /><br />
		fcm_id:<br /> 
	    <input type="text" name="fcm_id" value="" /> 
	    <br /><br />
	    <input type="submit" value="Register" /> 
	</form>
	<?php
}

?>