deleteConfirm();

function deleteConfirm() {
	document
		.querySelector('.js-deleteConfirm')
		.addEventListener('click', function (e) {
			if (!confirm('本当に削除しますか？')) e.preventDefault();
		});
}
