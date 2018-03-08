<?php
/* CREATE TABLE users (
    userid INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    password VARCHAR(50),
    premium TINYINT,
    numposts INT
) */

require_once $_SERVER['DOCUMENT_ROOT']."/../config.php";

/**
 * The Database class pulls the users table from the database
 * Members can be inserted into the table and selected from it
 * @author Antonio Suarez <asuarez2@mail.greenriver.edu>
 * @copyright 2018
 */
class Database
{
    protected $dbh;

    /**
     * Database constructor.
     * Establishes connection with database
     * @return void
     */
    function __construct()
    {
        try {
            // instantiate a PDO object
            $this->dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Adds member to the database
     * @param userid id of user
     * @param username username
     * @param password VARCHAR(50),
     * @param premium 1 if true, 0 if false
     * @param numposts number of posts made
     * @return void
     */
    function addMember($username, $password, $premium, $numposts)
    {
        $dbh = $this->dbh;
        // define the query
        $sql = "INSERT INTO users(username, password, premium, numposts)
          VALUES (:username, :password, :premium, :numposts)";

        // prepare the statement
        $statement = $dbh->prepare($sql);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);
        $statement->bindParam(':premium', $premium, PDO::PARAM_INT);
        $statement->bindParam(':numposts', $numposts, PDO::PARAM_INT);

        // execute
        $statement->execute();
        $id = $dbh->lastInsertId();
    }

    function selectMember()
    {
        $dbh = $this->dbh;
        // Define the query
        $sql = "SELECT * FROM users";

        // Prepare the statement
        $statement = $dbh->prepare($sql);

        // Execute the statement
        $statement->execute();

        // Process the result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            echo "<tr><td>" . $row['userid'] . "</td>";
            echo "<td>" . $row['usename'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>" . $row['premium'] . "</td>";
            echo "<td>" . $row['numposts'] . "</td>";
        }
    }
}