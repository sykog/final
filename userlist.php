<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/../config.php";

    // uses ajax to print out list of members
    // ordered by name or number of posts
    if ($_POST['category'] == "orderName") {
        $category = "username";
    }
    if ($_POST['category'] == "orderPosts") {
        $category = "numPosts DESC";
    }

    try {
        // instantiate a PDO object
        $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Define the query
    $sql = "SELECT * FROM users  ORDER BY $category";

    // Prepare the statement
    $statement = $dbh->prepare($sql);

    // Execute the statement
    $statement->execute();

    // Process the result
    $result = $statement->fetchall(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        echo "<li><a href='/328/final/profile/".$row['username']."'>".
            $row['username']."</a> - ".$row['numPosts']." post(s)</li>";
    }
