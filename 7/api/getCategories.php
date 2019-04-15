<?php

    include 'dbConnection.php';

    $conn = getDatabaseConnection("ottermart");
    
    $sql = "SELECT catId, catName FROM om_category ORDER BY catName";
    
    $stmt = $conn->prepare($sql); // This will send the sql you provided
	$stmt->execute(); // This will execute the sql
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($records);

?>