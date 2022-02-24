<?php
namespace App\Services;

use App\Libraries\Database;
use App\models\{Post, PostDetail};

class PostService {
    public Post $postModel;
    public PostDetail $postDetailModel;
    public Database $db;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->postDetailModel = new PostDetail();
        $this->db = new Database();
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

        return basename($filePath);
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
            WHERE posts.status_id = "public"
        ';

        $posts = $this->postModel->db
            ->prepare($sql)
            ->executeAndFetchAll(get_class($this->postModel));

        return $posts;
    }

    /**
     * 新規投稿を保存
     *
     * @param array $post 投稿内容
     * @return void
     */
    public function savePost(array $request, int $userId): void
    {
        try {
            $this->db->beginTransaction();

            // postsへinsert
            $request['user_id'] = $userId;
            $post = new Post($request);
            $post->save();

            // post_detailsへinsert
            $postDetailParams = [
                'post_id' => $post->db->lastInsertId(),
                'type' => 1,
                'path' => $request['file_path'],
                'sort_number' => 1
            ];
            $postDetail = new PostDetail($postDetailParams);
            $postDetail->save();

            $this->db->commit();

        } catch (\Exception $e) {
            $this->db->rollBack();
            
             exit($e->getMessage());
        }
    }

    /**
     * 主キー指定で投稿を取得
     *
     * @param integer $id
     * @return Post|false
     */
    public function fetchPostById(int $id): Post|false
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

         $postOrFalse = $this->postModel->db
            ->prepare($sql)
            ->bindValue(':id', $id)
            ->executeAndFetch(get_class($this->postModel));

            return $postOrFalse;
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
                ->bindValue(':id', $id)
                ->execute();

            // fileを削除
            // @todo 現状、同一post_idを複数のpost_detailが持つことはないため、loop等はしない
            $sqlGetPostDetail = 'SELECT * FROM post_details WHERE post_id = :post_id';
            $postDetail = $this->postModel->db->prepare($sqlGetPostDetail)
                ->bindValue(':post_id', $id)
                ->executeAndFetch(get_class($this->postDetailModel));

            // @todo 現状、post_detailに複数の値が入ることはないため、loop等はしない
            $filePath = $postDetail->path;
            if (!unlink(public_path('upload/' . $filePath))) throw new \Exception(('ファイルがありません'));

            // post_detailを削除
            $sqlDeletePostDetail = 'DELETE FROM post_details WHERE post_id = :post_id';
            $this->postModel->db->prepare($sqlDeletePostDetail)
                ->bindValue(':post_id', $id)
                ->execute();

            $this->postModel->db->commit();

        } catch (\Exception $e) {
            $this->postModel->db->rollBack();

             exit($e->getMessage());
        }
    }
}