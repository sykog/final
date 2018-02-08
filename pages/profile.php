<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{@username}}'s Profile</title>

    <!--Style sheets-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="../final/styles/profile-styles.css">
</head>
<body>

<?php
    include('../includes/navbar.html');
?>

<div class="container">
    <!--User image-->
    <img src="../final/images/default-image.png" alt="user image">
    <div id="userInfo">
        <p>Username: {{@username}}</p>
        <p>Location: Seattle</p>
        <p>Bio: Empty</p>
    </div><br>

    <div class="content">

        <hr><br>

        <div id="groups">
            <h4>Groups</h4><hr>
            <ul>
                <li>Ice Cream Enthusiasts</li>
                <li>Animal Memes</li>
            </ul>
            <h4>Interests</h4><hr>
            <ul>
                <li>Cats</li>
                <li>Ice Cream</li>
            </ul>
        </div>
    </div>


</div><!--End div 'container'-->

</body>
</html>