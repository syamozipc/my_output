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

-   [ ] ログイン機能
    -   [ ] メール認証での新規登録
    -   [ ] パスワードリセット
    -   [ ] user ページ
    -   [ ] SNS ログイン
-   [ ] リファクタ
    -   [ ] post モデルが post_detail モデルを持つよう、リレーションを作りたい
    -   [ ] モデルオブジェクトに引数を渡し、save するだけでテーブルに入るようにしたい
    -   [ ] session の unset が多分不十分（独習 PHP 参照）
    -   [ ] file は動画/画像混在の複数投稿アップロードに対応させたい
    -   [ ] ルーティングを別のやり方にしたい
    -   [ ] 定数はヘルパでドット繋ぎにしたい
-   [ ] PHP 8 を使う
    -   [x] 名前付き引数
    -   [ ] constructor の省略形
    -   [x] match 式
    -   [ ] null 安全オペレーター
    -   [ ] str_contains
-   [ ] ページネーション実装
-   [ ] cron を利用したタスク作る
-   [ ] 自己結合の多対多（フォロー機能）
-   [ ] 統計機能（PV 数などを Chart.JS で表示）
-   [ ] AWS 実装
    -   [ ] 画像・動画を S3 に cludfront 経由でアップロード
    -   [ ] EC2 動かす
    -   [ ] RDS 使う
-   [ ] 通知機能
-   [ ] CKEditor 使う（ブログ的な機能）
-   [ ] TypeScript 実装
-   [ ] CSV ファイル読み込み・書き出し
-   [ ] HTML/CSS 勉強後、マークアップ部分を改善

<!-- 実行済機能
・CRUD
・npm/webpackとESLint
・validation
-->
