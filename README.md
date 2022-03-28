## 概要

学んだことをアウトプットするためのアプリケーション

旅行写真・動画を投稿するアプリケーション

## なぜ、このアプリケーションを作るのか

- 学んだ知識のアウトプット

    - Udemy や書籍でのインプットばかりで、アウトプットが十分でない
    - 知識としてしか学べてなく、自分のスキルになっていない

- 仕事で学んだことや吸収しきれていない点を、このアプリケーションでの実装を通して理解を深めたい

- 0 から体系的に作る

    - 仕事では、既にリリースされている案件の改修・機能追加のみなので、設計段階から 0 ベースでアプリを作る経験をしたい
    - フレームワークを使用しないことで、普段ブラックボックスになっている処理も理解を深める

- 今後も、何か実践してみたければこのアプリケーションへの追加として実装すればいいので、アウトプットのハードルが下がる
    - 今までは業務でのみアウトプットしていた。

**今回作成する動機から、このアプリケーションは完成されたものではなく、練習場のようなものであるべき**

現状、HTML/CSSは度外視

## 処理の流れ

MVCモデルで設計しています

1. URLを叩いたら、public/index.php を呼び出す
2. bootstrap/app.php を読み込み、設定・ヘルパーファイルの読み込みと spl_autoload_register の定義をする
3. app\libraries\Coreを読み込み、リクエストURLに対応するコントローラ・メソッドを呼び出す
    - 例：リクエストURLが `post/show/1` であれば、user namespace 配下 PostController の Show メソッドを呼び出す
4. 必要に応じて model を通して対応するテーブルのレコードを取得し、view ファイルに埋め込んで表示

## 使用技術

フロントエンド
- JavaScript (ES6)

バックエンド
- PHP8.0
- MySQL5.7

その他ライブラリ等
- Webpack
- eslint