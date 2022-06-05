import '@scss/user/post/show.scss';

// 削除確認モーダルの開閉処理
const toggleModal = (modal: HTMLDivElement, overlay: HTMLDivElement): void => {
    modal.classList.toggle('is-hidden');
    overlay.classList.toggle('is-hidden');
};

/**
 * 削除ボタン押下時、確認モーダルを表示し、OKなら削除実行
 */
const deleteConfirm = () => {
    // 投稿内容のDOM
    const imgContent = document.querySelector(
        '.js-imgContent'
    )! as HTMLImageElement;
    const description = document.querySelector(
        '.js-description'
    )! as HTMLTableCellElement;

    // モーダル内に投稿内容反映用DOM
    const modalImgContent = document.querySelector(
        '.js-modalImgContent'
    )! as HTMLImageElement;
    const modalDescription = document.querySelector(
        '.js-modalDescription'
    )! as HTMLParagraphElement;

    // モーダル操作用DOM
    const btnOpenModal = document.querySelector(
        '.js-btn-open-modal'
    )! as HTMLInputElement;
    const btnCloseModal = document.querySelector(
        '.js-btn-close-modal'
    )! as HTMLButtonElement;
    const btnCancel = document.querySelector(
        '.js-btn-cancel'
    )! as HTMLButtonElement;
    const btnDelete = document.querySelector(
        '.js-btn-delete'
    )! as HTMLButtonElement;
    const modal = document.querySelector('.js-modal')! as HTMLDivElement;
    const overlay = document.querySelector('.js-overlay')! as HTMLDivElement;

    // 削除ボタン押下時、投稿内容を含むモーダルを表示
    btnOpenModal.addEventListener('click', (e: MouseEvent): void => {
        e.preventDefault();

        modalImgContent.src = imgContent.src;

        modalDescription.textContent = description.textContent;

        modalImgContent.addEventListener(
            'load',
            toggleModal.bind(null, modal, overlay)
        );
    });

    // xボタン押下時、モーダルを閉じる
    btnCloseModal.addEventListener(
        'click',
        toggleModal.bind(null, modal, overlay)
    );

    // キャンセルボタン押下時、モーダルを閉じる
    btnCancel.addEventListener('click', toggleModal.bind(null, modal, overlay));

    // モーダル背景押下時、モーダルを閉じる
    overlay.addEventListener('click', toggleModal.bind(null, modal, overlay));

    // escapeキー押下時、モーダルをとじる
    document.addEventListener('keydown', (e: KeyboardEvent): void => {
        if (e.key === 'Escape' && !modal.classList.contains('is-hidden')) {
            toggleModal(modal, overlay);
        }
    });

    // モーダル内削除ボタン押下時、削除formをsubmit
    btnDelete.addEventListener('click', (): void => {
        const deleteForm = document.querySelector(
            '.js-delete-form'
        )! as HTMLFormElement;

        deleteForm.submit();
    });
};

deleteConfirm();
