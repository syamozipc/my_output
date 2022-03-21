# アウトプット用のポートフォリオ

# 概要

旅行写真・動画を投稿するアプリ

## なぜ、このポートフォリオを作るのか

- 学んだ知識のアウトプット

    - Udemy や書籍でのインプットばかりで、アウトプットが十分でない
    - 知識としてしか学べてなく、自分のスキルになっていない

- 仕事で学んだことや吸収しきれていない点を、ポートフォリオでの実装を通して理解を深めたい

- 0 から体系的に作る

    - 仕事では、既にリリースされている案件の改修・機能追加のみなので、設計段階から 0 ベースでアプリを作る経験をしたい
    - フレームワークを使用しないことで、普段ブラックボックスになっている処理も理解を深める
    - 自分でシンプルなMVCフレームワークを作ることで、フレームワークの考え方・メリット等を理解する

- 今後も、何か実践してみたければこのポートフォリオで実践すればいいので、アウトプットのハードルが下がる
    - 今までは業務でのみアウトプットしていた。

**今回作成する動機から、このポートフォリオは完成されたものではなく、練習場のようなものであるべき**

## 使用技術

フロントエンド
- JavaScript (ES6)
- Webpack
- eslint

バックエンド
- PHP8.0
- MySQL5.7

## 現時点で未実装の機能

- [ ] Docker 構築
- [ ] SNS ログイン
- [ ] TypeScript 実装
- [ ] 自己結合の多対多（フォロー機能）
- [ ] AWS 実装
    - [ ] 画像・動画を S3 に cludfront 経由でアップロード
    - [ ] EC2 動かす
    - [ ] RDS 使う
- [ ] HTML/CSS 勉強後、マークアップ部分を改善
- [ ] リファクタ
    - [ ] @todo 解消
    - [ ] file は動画/画像混在の複数投稿アップロードに対応させたい
    - [ ] Laravel のやり方に寄せる（定数はヘルパでドット繋ぎ、routing/名前付き route）
    - [ ] PHP8 の機能を追加（null 安全演算子、match 式、名前付き引数、constructor 省略構文、str_contains）