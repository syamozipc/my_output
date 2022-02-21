ログアウト時、api_token は削除されるが remember_token は再度作成されている

・remember me チェック項目を作る
・remember_token での login 処理を作る
→
・（明示的な）ログアウト時に cookie のユーザー ID、記憶トークン、DB の記憶ダイジェストを全て削除する
