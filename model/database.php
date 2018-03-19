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

    /**
     * Adds blog post to the databas
     * @param userid id of user
     * @param content post being made
     * @return void
     */
    function addPost($username, $title, $content)
    {
        $dbh = $this->dbh;
        // define the query
        $sql = "INSERT INTO posts(username, title, content)
          VALUES (:username, :title, :content)";

        // prepare the statement
        $statement = $dbh->prepare($sql);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':title', $title, PDO::PARAM_STR);
        $statement->bindParam(':content', $content, PDO::PARAM_STR);

        // execute
        $statement->execute();
        $id = $dbh->lastInsertId();
    }

    /**
     * Adds comment to the database
     * @param userid id of user
     * @param content post being made
     * @return void
     */
    function addComment($postid, $commentid, $username, $content)
    {
        $dbh = $this->dbh;
        // define the query
        $sql = "INSERT INTO comments(postid, commentid, username, content)
          VALUES (:postid, :commentid, :username, :content)";

        // prepare the statement
        $statement = $dbh->prepare($sql);
        $statement->bindParam(':postid', $postid, PDO::PARAM_INT);
        $statement->bindParam(':commentid', $commentid, PDO::PARAM_INT);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':content', $content, PDO::PARAM_STR);

        // execute
        $statement->execute();
    }

    /**
     * increases post count by 1 for specified user
     * @param username username
     * @param numPosts number of posts made
     * @return void
     */
    function updatePostCount($username, $numPosts)
    {
        $dbh = $this->dbh;
        // define the query
        $sql = "UPDATE users SET numPosts = :numPosts + 1 WHERE username = :username";

        // prepare the statement
        $statement = $dbh->prepare($sql);
        $statement->bindParam(':numPosts', $numPosts, PDO::PARAM_INT);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);

        // execute
        $statement->execute();
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
        /*foreach ($result as $row) {
            echo "<tr><td>" . $row['userid'] . "</td>";
            echo "<td>" . $row['usename'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>" . $row['premium'] . "</td>";
            echo "<td>" . $row['numposts'] . "</td>";
        } */
        return $result;
    }

    function memberExists($username)
    {
        $dbh = $this->dbh;
        // Define the query
        $sql = "SELECT * FROM users WHERE username= :username";

        // Prepare the statement
        $statement = $dbh->prepare($sql);

        $statement->bindParam(":username", $username, PDO::PARAM_STR);

        // Execute the statement
        $statement->execute();

        // Process the result
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row['username'] == $username;
    }//end memberExists()

    function getMember($username) {
        $dbh = $this->dbh;
        // Define the query
        $sql = "SELECT * FROM users WHERE username= :username";

        // Prepare the statement
        $statement = $dbh->prepare($sql);

        $statement->bindParam(":username", $username, PDO::PARAM_STR);

        // Execute the statement
        $statement->execute();

        // Process the result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getPosts() {
        $dbh = $this->dbh;
        // Define the query
        $sql = "SELECT * FROM posts";

        // Prepare the statement
        $statement = $dbh->prepare($sql);

        // Execute the statement
        $statement->execute();

        // Process the result
        $result = $statement->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    function getMemberPosts($username) {
        $dbh = $this->dbh;
        // Define the query
        $sql = "SELECT * FROM posts WHERE username= :username";

        // Prepare the statement
        $statement = $dbh->prepare($sql);

        // Bind the parameters
        $statement->bindParam(":username", $username, PDO::PARAM_STR);

        // Execute the statement
        $statement->execute();

        // Process the result
        $result = $statement->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    function getPost($postid) {
        $dbh = $this->dbh;
        // Define the query
        $sql = "SELECT * FROM posts WHERE postid= :postid";

        // Prepare the statement
        $statement = $dbh->prepare($sql);

        //Bind the parameter
        $statement->bindParam(":postid", $postid, PDO::PARAM_INT);

        // Execute the statement
        $statement->execute();

        // Process the result
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    function getComments($postid) {
        $dbh = $this->dbh;
        // Define the query
        $sql = "SELECT * FROM comments  WHERE postid= :postid ORDER BY commentid";

        // Prepare the statement
        $statement = $dbh->prepare($sql);

        //Bind the parameter
        $statement->bindParam(":postid", $postid, PDO::PARAM_INT);

        // Execute the statement
        $statement->execute();

        // Process the result
        $result = $statement->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }
}