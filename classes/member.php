<?php

    /**
     * The Member class represents a member on the blog
     * Members have usernames and passwords. Members can comment on blog posts and
     * create new posts if they are premium members
     * @author Antonio Suarez <asuarez2@mail.greenriver.edu>
     * @author Sam Ourn <sourn@mail.greenriver.edu>
     * @copyright 2018
     */
    class Member
    {
        protected $username;
        protected $password;
        protected $comments;
        protected $premium;

        /**
         * Member constructor.
         * @param $username username
         * @param $password pasword
         * @param $posts starts number of posts made
         * @return void
         */
        function __construct($username, $password, $premium, $comments)
        {
            $this->username = $username;
            $this->password = $password;
            $this->comments = $comments;
            $this->premium = $premium;
        }

        /**
         * get the member's username
         * @return string username
         */
        function getUsername()
        {
            return $this->username;
        }

        /**
         * set the member's username
         * @param $username
         * @return void
         */
        function setUsername($username)
        {
            $this->username = $username;
        }

        /**
         * get the member's password
         * @return string password
         */
        function getPassword()
        {
            return $this->password;
        }

        /**
         * encrypt and set password
         * @param $fname password
         * @return void
         */
        function setPassword($password)
        {
            $this->password = $password;
        }

        function getPremium() {
            return $this->premium;
        }

        /**
         * get number of comments made
         * @return string comment count
         */
        function commentCount()
        {
           return $this->comments;
        }

        /**
         * create a blog post, send to database
         * only premium members can make posts
         * @param $comment the post being made
         * @return void
         */
        function blogPost($title, $comment)
        {
            // exit if not a premium member
            if ($this->premium != 1) {
                return;
            }

            // access the database
            $database = new Database();
            $username = $database->getMember($this->username)[0]['username'];
            // add post to the database
            $database->addPost($username, $title, $comment);
            // update post count
            $database->updatePostCount($this->username, $this->comments);
        }

        /**
         * comment to to a post, send to database
         * @param $postid id of the post being comment on
         * @param id for the comment
         * @param $comment the comment being made
         * @return void
         */
        function comment($postid, $commentid, $comment)
        {
            // access the database
            $database = new Database();
            $username = $database->getMember($this->username)[0]['username'];
            // add post to the database
            $database->addComment($postid, $commentid, $username, $comment);
            // update post count
            $database->updatePostCount($this->username, $this->comments);
        }
    }