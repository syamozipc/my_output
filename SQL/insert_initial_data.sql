-- 参考：https://ja.wikipedia.org/wiki/国連による世界地理区分
INSERT INTO `regions` (`name`, `name_alpha`)
VALUES
('アジア', 'asia'),
('アフリカ', 'africa'),
('北米', 'north America'),
('中南米', 'latin america and the caribbean'),
('ヨーロッパ', 'europe'),
('オセアニア', 'oceania'),
('北極', 'arctic'),
('南極', 'antarctic');

-- 国連加盟国一覧：https://www.mofa.go.jp/mofaj/files/000023536.pdf
-- 国の所属地域の分類：https://www.mofa.go.jp/mofaj/area/index.html
INSERT INTO `countries` (`name`, `name_alpha`, `region_id`)
VALUES
("アフガニスタン", "Afghanistan", 1),
("アルバニア", "Albania", 5),
("アルジェリア", "Algeria", 2),
("アンドラ", "Andorra", 5),
("アンゴラ", "Angola", 2),
("アンティグア・バーブーダ", "Antigua and Barbuda", 4),
("アルゼンチン", "Argentina", 4),
("アルメニア", "Armenia", 5),
("オーストラリア", "Australia", 6),
("オーストリア", "Austria", 5),
("アゼルバイジャン", "Azerbaijan", 5),
("バハマ", "Bahamas", 4),
("バーレーン", "Bahrain", 1),
("バングラデシュ", "Bangladesh", 1),
("バルバドス", "Barbados", 4),
("ベラルーシ", "Belarus", 5),
("ベルギー", "Belgium", 5),
("ベリーズ", "Belize", 4),
("ベナン", "Benin", 2),
("ブータン", "Bhutan", 1),
("ボリビア", "Bolivia", 4),
("ボスニア・ヘルツェゴビナ", "Bosnia and Herzegovina", 5),
("ボツワナ", "Botswana", 2),
("ブラジル", "Brazil", 4),
("ブルネイ", "Brunei Darussalam", 1),
("ブルガリア", "Bulgaria", 5),
("ブルキナファソ", "Burkina Faso", 2),
("ブルンジ", "Burundi", 2),
("カーボベルデ", "Cabo Verde", 2),
("カンボジア", "Cambodia", 1),
("カメルーン", "Cameroon", 2),
("カナダ", "Canada", 3),
("中央アフリカ共和国", "Central African Republic", 2),
("チャド", "Chad", 2),
("チリ", "Chile", 4),
("中国", "China", 1),
("コロンビア", "Colombia", 4),
("コモロ", "Comoros", 2),
("コンゴ", "Congo (Republic of the)", 2),
("コスタリカ", "Costa Rica", 4),
("コートジボワール", "Cote d'Ivoire", 2),
("クロアチア", "Croatia", 5),
("キューバ", "Cuba", 4),
("キプロス", "Cyprus", 5),
("チェコ", "Czech Republic", 5),
("朝鮮民主主義人民共和国", "Democratic People's Republic of Korea", 1),
("コンゴ民主共和国", "Democratic Republic of the Congo", 2),
("デンマーク", "Denmark", 5),
("ジブチ", "Djibouti", 2),
("ドミニカ", "Dominica", 4),
("ドミニカ共和国", "Dominican Republic", 4),
("エクアドル", "Ecuador", 4),
("エジプト", "Egypt", 2),
("エルサルバドル", "El Salvador", 4),
("赤道ギニア", "Equatorial Guinea", 2),
("エリトリア", "Eritrea", 2),
("エストニア", "Estonia", 5),
("エスワティニ", "Eswatini", 2),
("エチオピア", "Ethiopia", 2),
("フィジ－", "Fiji", 6),
("フィンランド", "Finland", 5),
("フランス", "France", 5),
("ガボン", "Gabon", 2),
("ガンビア", "Gambia", 2),
("ジョージア", "Georgia", 5),
("ドイツ", "Germany", 5),
("ガーナ", "Ghana", 2),
("ギリシャ", "Greece", 5),
("グレナダ", "Grenada", 4),
("グアテマラ", "Guatemala", 4),
("ギニア", "Guinea", 2),
("ギニアビサウ", "Guinea-Bissau", 2),
("ガイアナ", "Guyana", 4),
("ハイチ", "Haiti", 4),
("ホンジュラス", "Honduras", 4),
("ハンガリ－", " Hungary", 5),
("アイスランド", "Iceland", 5),
("インド", "India", 1),
("インドネシア", "Indonesia", 1),
("イラン", "Iran", 1),
("イラク", "Iraq", 1),
("アイルランド", "Ireland", 5),
("イスラエル", "Israel", 1),
("イタリア", "Italy", 5),
("ジャマイカ", "Jamaica", 4),
("日本", "Japan", 1),
("ヨルダン", "Jordan", 1),
("カザフスタン", "Kazakhstan", 5),
("ケニア", "Kenya", 2),
("キリバス", "Kiribati", 6),
("クウェート", "Kuwait", 1),
("キルギスタン", "Kyrgyzstan", 5),
("ラオス人民民主共和国", "Lao People's Democratic Republic", 1),
("ラトビア", "Latvia", 5),
("レバノン", "Lebanon", 1),
("レソト", "Lesotho", 2),
("リベリア", "Liberia", 2),
("リビア", "Libya", 2),
("リヒテンシュタイン", "Liechtenstein", 5),
("リトアニア", "Lithuania", 5),
("ルクセンブルク", "Luxembourg", 5),
("マダガスカル", " Madagascar", 2),
("マラウィ", "Malawi", 2),
("マレーシア", "Malaysia", 1),
("モルディブ", "Maldives", 1),
("マリ", " Mali", 2),
("マルタ", "Malta", 5),
("マーシャル諸島", "Marshall Islands", 6),
("モーリタニア", "Mauritania", 2),
("モーリシャス", "Mauritius", 2),
("メキシコ", "Mexico", 4),
("ミクロネシア連邦", "Micronesia (Federated States of)", 6),
("モナコ", "Monaco", 5),
("モンゴル", "Mongolia", 1),
("モンテネグロ", "Montenegro", 5),
("モロッコ", "Morocco", 2),
("モザンビーク", "Mozambique", 2),
("ミャンマー", "Myanmar", 1),
("ナミビア", "Namibia", 2),
("ナウル", "Nauru", 6),
("ネパール", "Nepal", 1),
("オランダ", "Netherlands", 5),
("ニュージーランド", "New Zealand", 6),
("ニカラグア", "Nicaragua", 4),
("ニジェール", "Niger", 2),
("ナイジェリア", "Nigeria", 2),
("北マケドニア", "North Macedonia", 5),
("ノルウェー", "Norway", 5),
("オマーン", "Oman", 1),
("パキスタン", "Pakistan", 1),
("パラオ", "Palau", 6),
("パナマ", "Panama", 4),
("パプア・ニューギニア", "Papua New Guinea", 6),
("パラグアイ", "Paraguay", 4),
("ペルー", "Peru", 4),
("フィリピン", "Philippines", 1),
("ポーランド", "Poland", 5),
("ポルトガル", "Portugal", 5),
("カタール", "Qatar", 1),
("韓国", "Republic of Korea", 1),
("モルドバ", "Republic of Moldova", 5),
("ルーマニア", "Romania", 5),
("ロシア連邦", "Russian Federation", 5),
("ルワンダ", "Rwanda", 2),
("セントクリストファー・ネイビス", "Saint Kitts and Nevis", 4),
("セントルシア", "Saint Lucia", 4),
("セントビンセントおよびグレナディーン諸島", "Saint Vincent and the Grenadines", 4),
("サモア", "Samoa", 6),
("サンマリノ", "San Marino", 5),
("サントメ・プリンシペ", "Sao Tome and Principe", 2),
("サウジアラビア", "Saudi Arabia", 1),
("セネガル", "Senegal", 2),
("セルビア", "Serbia", 5),
("セーシェル", "Seychelles", 2),
("シエラレオネ", "Sierra Leone", 2),
("シンガポール", "Singapore", 1),
("スロバキア", "Slovakia", 5),
("スロベニア", "Slovenia", 5),
("ソロモン諸島", "Solomon Islands", 6),
("ソマリア", "Somalia", 2),
("南アフリカ", "South Africa", 2),
("南スーダン", "South Sudan", 2),
("スペイン", "Spain", 5),
("スリランカ", "Sri Lanka", 1),
("スーダン", "Sudan", 2),
("スリナム", "Suriname", 4),
("スウェーデン", "Sweden", 5),
("スイス", "Switzerland", 5),
("シリア", "Syria", 1),
("タジキスタン", "Tajikistan", 5),
("タイ", "Thailand", 1),
("東ティモール", "Timor-Leste", 1),
("トーゴ", "Togo", 2),
("トンガ", "Tonga", 6),
("トリニダード・トバゴ", " Trinidad and Tobago", 4),
("チュニジア", "Tunisia", 2),
("トルコ", "Turkey", 1),
("トルクメニスタン", "Turkmenistan", 5),
("ツバル", "Tuvalu", 6),
("ウガンダ", "Uganda", 2),
("ウクライナ", "Ukraine", 5),
("アラブ首長国連邦", "United Arab Emirates", 1),
("イギリス", "United Kingdom", 5),
("タンザニア", "United Republic of Tanzania", 2),
("アメリカ", "United States of America", 3),
("ウルグアイ", "Uruguay", 4),
("ウズベキスタン", "Uzbekistan", 1),
("バヌアツ", "Vanuatu", 6),
("ベネズエラ", "Venezuela", 4),
("ベトナム", "Viet Nam", 1),
("イエメン", "Yemen", 1),
("ザンビア", "Zambia", 2),
("ジンバブエ", "Zimbabwe", 2),
("北極", "Arctic", 7),
("南極", "Antarctic", 8);