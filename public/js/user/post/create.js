reflectInput();

displayInputImg();

/**
 * 1.inputに文字を入力すると発火
 * 2.入力した文字をvalueに持つoptionがdatalist内にあるかcheck
 * 3.あった場合、そのdata-place-idを取得
 * 4.select tag内からdata-place-idと同じ値をvalueに持つoptionにselectedを付与
 */
function reflectInput() {
	const suggestionInput = document.querySelector('.js-suggestionInput');
	const placeSelect = document.querySelector('.js-countriesSelect');

	suggestionInput.addEventListener('change', ev => {
		// inputに紐づくdatalist tagは、.listでアクセスできる
		const placeId = suggestionInput.list.querySelector(
			`[value=${ev.target.value}]`
		)?.dataset.countryId;

		// 入力と一致する値が無ければ、undefinedになる
		if (placeId === undefined) return;

		// select tagのoptionが順に入る
		for (const option of placeSelect) {
			if (option.value === placeId) {
				option.selected = true;
				break;
			}
		}
	});
}

/**
 * 1.画像を選択時に発火
 * 2.input[file]から該当のfile情報を取得してblobUrlを生成
 * それをimgタグのsrcに設定し、is-hiddenクラスを取り除いて画面に表示する
 */
function displayInputImg() {
	document
		.querySelector('.js-inputImg')
		.addEventListener('change', function (e) {
			const file = e.target.files[0];

			if (!file) return;

			blobUrl = window.URL.createObjectURL(file);

			const img = document.querySelector('.js-displayImg');
			img.src = blobUrl;

			img.addEventListener('load', () => img.classList.remove('is-hidden'));
		});
}
