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
        $this->db = Database::getSingleton();
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
     * 該当のpostIdを持つpostDetail classの配列を取得
     *
     * @param integer $postId
     * @return array
     */
    public function fetchPostDetailByPostId(int $postId):array
    {
        $sql = 'SELECT * FROM post_details WHERE post_id = :post_id';

        $postDetailsOrFalse = $this->postDetailModel->db->prepare(sql:$sql)
            ->bindValue(param:':post_id', value:$postId)
            ->executeAndFetchAll(className:get_class($this->postDetailModel));

        return $postDetailsOrFalse;
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
     * 投稿を削除→画像を削除→投稿詳細を削除
     *
     * @param Post $id
     * @return void
     */
    public function deletePost(Post $post): void
    {
        try {
            $this->db->beginTransaction();

            // postを論理削除
            $post->status_id = 'deleted';
            $post->save();

            $postDetails = $this->fetchPostDetailByPostId(postId:$post->id);

            foreach ($postDetails as $postDetail) {
                // postDetailのfileを削除
                if (!unlink(public_path('upload/' . $postDetail->path))) throw new \Exception(('ファイルがありません'));
                
                // post_detailを物理削除
                $postDetail->delete();
            }

            $this->db->commit();

        } catch (\Exception $e) {
            $this->db->rollBack();

             exit($e->getMessage());
        }
    }
}