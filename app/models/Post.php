<?php

class Post {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function fetchPostsList()
    {
        $sql = '
            SELECT 
                posts.id,
                posts.user_id,
                posts.country_id,
                posts.description,
                countries.name AS country_name,
                post_details.path,
                users.name AS user_name
            FROM posts
            JOIN post_details ON posts.id = post_details.post_id
            JOIN countries ON posts.country_id = countries.id
            JOIN users ON posts.user_id = users.id
            WHERE posts.status_id = "publish"
        ';
        
        $postsList = $this->db->prepare($sql)->executeAndFetchAll();

        return $postsList;
    }

    public function save($post, $filePath)
    {
        try {
            $this->db->beginTransaction();

            $sql = 'INSERT INTO posts (user_id, country_id, description) VALUES (:user_id, :country_id, :description)';
            $this->db->prepare($sql);

            $this->db->bind(':user_id', 1);
            $this->db->bind(':country_id', $post['country_id']);
            $this->db->bind(':description', $post['description']);

            $this->db->execute();

            $id = $this->db->lastInsertId();

            $sql = 'INSERT INTO post_details (post_id, type, path, sort_number) VALUES (:post_id, :type, :path, :sort_number)';
            $this->db->prepare($sql);

            $this->db->bind(':post_id', $id);
            $this->db->bind(':type', 1);
            $this->db->bind(':path', basename($filePath));
            $this->db->bind(':sort_number', 1);

            $this->db->execute(':description', $post['description']);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
             exit($e->getMessage());
        }
    }
}