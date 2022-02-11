<?php
namespace App\Services;

use App\models\Post;

class PostService {
    public object $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    /**
     * tmpフォルダにアップロードされたファイルをpublic/uploadへ移動
     *
     * @param array $files file情報
     * @return void
     */
    public function uploadFileToPublic(array $files)
    {
        $tempPath = $files['upload']['tmp_name'];
        $randomFileName = md5(uniqid());
        $filePath = public_path('upload/' . $randomFileName . '.' . basename($files['upload']['type']));

        move_uploaded_file($tempPath, $filePath);

        return $filePath;
    }

    /**
     * 投稿一覧を取得
     *
     * @return array
     */
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

        $postsList = $this->postModel->db->prepare($sql)->executeAndFetchAll();

        return $postsList;
    }

    /**
     * 新規投稿を保存
     *
     * @param array $post 投稿内容
     * @return void
     */
    public function savePost(array $post): void
    {
        try {
            $this->postModel->db->beginTransaction();

            $sql = 'INSERT INTO posts (user_id, country_id, description) VALUES (:user_id, :country_id, :description)';

            $this->postModel->db->prepare($sql)
                ->bind(':user_id', 1)
                ->bind(':country_id', $post['country_id'])
                ->bind(':description', $post['description'])
                ->execute();

            $id = $this->postModel->db->lastInsertId();

            $sql = 'INSERT INTO post_details (post_id, type, path, sort_number) VALUES (:post_id, :type, :path, :sort_number)';

            $this->postModel->db->prepare($sql)
                ->bind(':post_id', $id)
                ->bind(':type', 1)
                ->bind(':path', basename($post['file_path']))
                ->bind(':sort_number', 1)
                ->execute();

            $this->postModel->db->commit();

        } catch (\Exception $e) {
            $this->postModel->db->rollBack();
            
             exit($e->getMessage());
        }
    }

    /**
     * 主キー指定で投稿を取得
     *
     * @param integer $id
     * @return object
     */
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

         $post = $this->postModel->db->prepare($sql)
            ->bind(':id', $id)
            ->executeAndFetch();

            return $post;
    }

    /**
     * 主キー指定で投稿を更新
     *
     * @param array $post 投稿内容
     * @param integer $id
     * @return void
     */
    public function updatePost(array $post, int $id): void
    {
            $sql = 'UPDATE posts SET country_id = :country_id, description = :description WHERE id = :id';

            $this->postModel->db->prepare($sql)
                ->bind(':country_id', $post['country_id'])
                ->bind(':description', $post['description'])
                ->bind(':id', $id)
                ->execute();
    }

    /**
     * 投稿を削除→画像を削除→投稿詳細を削除
     *
     * @param integer $id
     * @return void
     */
    public function deletePost(int $id): void
    {
        try {
            $this->postModel->db->beginTransaction();

            // postを削除
            $sqlDeletePost = 'UPDATE posts SET status_id = "deleted" WHERE id = :id';
            $this->postModel->db->prepare($sqlDeletePost)
                ->bind(':id', $id)
                ->execute();

            // fileを削除
            $sqlGetPostDetail = 'SELECT * FROM post_details WHERE post_id = :post_id';
            $postDetail = $this->postModel->db->prepare($sqlGetPostDetail)
                ->bind(':post_id', $id)
                ->executeAndFetch();

            $filePath = $postDetail->path;
            if (!unlink(public_path('upload/' . $filePath))) throw new \Exception(('ファイルがありません'));

            // post_detailを削除
            $sqlDeletePostDetail = 'DELETE FROM post_details WHERE post_id = :post_id';
            $this->postModel->db->prepare($sqlDeletePostDetail)
                ->bind(':post_id', $id)
                ->execute();

            $this->postModel->db->commit();
        } catch (\Exception $e) {
            $this->postModel->db->rollBack();
             exit($e->getMessage());
        }
    }
}