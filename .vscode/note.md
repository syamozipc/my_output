# リファクタ
<!-- 1. fillableの方を回し、property_existsでチェック -->
<!-- 2. iterater method実装 -->
3. プロパティをprivate化し、テーブルにないカラムは一つのプロパティにまとめ、それは回さないようにする
4. primary keyは回さないようにする

propertyの値が未定義でも、カラムにはnullを挿入しようとする
例：status_idとかはnullは許容しないのでerrorになる
解決策：
・最初から全プロパティを定義せず、pdoや新規作成時に任せる
    →特に記述しなくていいので楽。ただ、dbから取ってくるときはデフォルトでpublicになってしまう
・デフォルト値を設ける（statusはtentative、みたいな）
    →dbテーブルと処理が被る