import '../../../scss/user/post/edit.scss';

reflectInput();

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
