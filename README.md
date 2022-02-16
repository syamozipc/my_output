# アウトプット用のポートフォリオ

https://github.com/syamozipc/my_output

# 概要

旅行写真・動画を投稿する、インスタのパクリ

## なぜ、このポートフォリオを作るのか

-   学んだ知識のアウトプット

    -   Udemy や書籍でのインプットばかりで、アウトプットが十分でない
    -   知識としてしか学べてなく、自分のスキルになっていない

-   仕事で学んだことや吸収しきれていない点を、ポートフォリオでの実装を通して理解を深めたい

-   1 から体系的に作る

    -   仕事では、既にリリースされている案件の改修・保守・機能追加のみなので、設計段階から 0 ベースでアプリを作る経験をしたい
    -   フレームワークに頼らないことで、普段ブラックボックスになっている処理も理解を深める

-   今後も、何か実践してみたければこのポートフォリオで実践すればいいので、アウトプットのハードルが下がる
    -   今までは業務でのみアウトプットしていた。

**今回作成する動機から、このポートフォリオは完成されたものではなく、練習場のようなものであるべき**

## 実装したい機能

-   [ ] 認証機能
    -   [x] メール認証での新規登録
    -   [ ] ログイン/ログアウト
    -   [ ] remember token
    -   [ ] パスワードリセット
    -   [ ] SNS ログイン
-   [ ] 自己結合の多対多（フォロー機能）
-   [ ] AWS 実装
    -   [ ] 画像・動画を S3 に cludfront 経由でアップロード
    -   [ ] EC2 動かす
    -   [ ] RDS 使う
-   [ ] TypeScript 実装
-   [ ] HTML/CSS 勉強後、マークアップ部分を改善
-   [ ] リファクタ
    -   [ ] @todo 解消
    -   [ ] post モデルが post_detail モデルを持つよう、リレーションを作りたい
    -   [ ] モデルオブジェクトに引数を渡し、save するだけでテーブルに入るようにしたい
    -   [ ] session の unset が多分不十分（独習 PHP 参照）
    -   [ ] file は動画/画像混在の複数投稿アップロードに対応させたい
    -   [ ] ルーティングを別のやり方にしたい
    -   [ ] 定数はヘルパでドット繋ぎにしたい
    -   [ ] user 登録時は、password の null を見ずに status_id に仮登録ステータスを追加し、そちらを見るようにしたい
    -   [ ] CSRF/XSS 対策
    -   [ ] PHP8 の機能（null 安全演算子、match 式）

<!-- 実行済機能
・CRUD
・npm/webpackとESLint
・validation
-->
