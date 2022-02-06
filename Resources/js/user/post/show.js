import '../../../scss/user/post/show.scss';

const deleteConfirm = () => {
    document
        .querySelector('.js-deleteConfirm')
        .addEventListener('click', (e) => {
            if (!window.confirm('本当に削除しますか？')) e.preventDefault();
        });
};

deleteConfirm();
