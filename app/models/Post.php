<?php

class Post {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function fetchPostsList(): array
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

    public function save(array $post, string $filePath): void
    {
        try {
            $this->db->beginTransaction();

            $sql = 'INSERT INTO posts (user_id, country_id, description) VALUES (:user_id, :country_id, :description)';

            $this->db->prepare($sql)
                ->bind(':user_id', 1)
                ->bind(':country_id', $post['country_id'])
                ->bind(':description', $post['description'])
                ->execute();

            $id = $this->db->lastInsertId();

            $sql = 'INSERT INTO post_details (post_id, type, path, sort_number) VALUES (:post_id, :type, :path, :sort_number)';

            $this->db->prepare($sql)
                ->bind(':post_id', $id)
                ->bind(':type', 1)
                ->bind(':path', basename($filePath))
                ->bind(':sort_number', 1)
                ->execute();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
             exit($e->getMessage());
        }
    }

    public function fetchPostById(int $id): object
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
            WHERE posts.id = :id
        ';

         $post = $this->db->prepare($sql)
            ->bind(':id', $id)
            ->executeAndFetch();

            return $post;
    }

    public function update(array $post, int $id): void
    {
            $sql = 'UPDATE posts SET country_id = :country_id, description = :description WHERE id = :id';

            $this->db->prepare($sql)
                ->bind(':country_id', $post['country_id'])
                ->bind(':description', $post['description'])
                ->bind(':id', $id)
                ->execute();
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM posts WHERE id = :id';

        $this->db->prepare($sql)
            ->bind(':id', $id)
            ->execute();
    }
}