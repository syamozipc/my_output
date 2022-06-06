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
    const imgContent = <HTMLImageElement>(
        document.querySelector('.js-imgContent')!
    );
    const description = <HTMLTableCellElement>(
        document.querySelector('.js-description')!
    );

    // モーダル内に投稿内容反映用DOM
    const modalImgContent = <HTMLImageElement>(
        document.querySelector('.js-modalImgContent')!
    );
    const modalDescription = <HTMLParagraphElement>(
        document.querySelector('.js-modalDescription')!
    );

    // モーダル操作用DOM
    const btnOpenModal = <HTMLInputElement>(
        document.querySelector('.js-btn-open-modal')!
    );
    const btnCloseModal = <HTMLButtonElement>(
        document.querySelector('.js-btn-close-modal')!
    );
    const btnCancel = <HTMLButtonElement>(
        document.querySelector('.js-btn-cancel')!
    );
    const btnDelete = <HTMLButtonElement>(
        document.querySelector('.js-btn-delete')!
    );
    const modal = <HTMLDivElement>document.querySelector('.js-modal')!;
    const overlay = <HTMLDivElement>document.querySelector('.js-overlay')!;

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
        const deleteForm = <HTMLFormElement>(
            document.querySelector('.js-delete-form')!
        );

        deleteForm.submit();
    });
};

deleteConfirm();
