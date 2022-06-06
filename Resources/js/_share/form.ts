// async/await用

// 非同期でDBから取得するcountry class objectの型を定義
type CountryObject = {
    id: string;
    name: string;
    name_alpha: string;
    region_id: string;
    updated_at: string;
};

/**
 * 1. 入力値をパラメータとし、apiでgetリクエスト
 * 2. その入力値に部分一致する国が、countryクラスのオブジェクトの配列として返ってくる
 * 3. inputタグ直下にその国一覧を表示し、クリックされた国名をinputタグ内に反映
 */
export const displayMatchedCountries = (): void => {
    const inputEl = document.querySelector(
        '.js-suggestionInput'
    )! as HTMLInputElement;
    const ul = document.querySelector(
        '.js-suggestionList'
    )! as HTMLUListElement;
    const apiUrl: string = inputEl.dataset.suggestUrl!;

    // 値が入力されたらそれを取得し、部分一致する国の取得をapiリクエスト
    // inputはInputEvent/Eventどちらかになるため、最初からInputEventを指定できないっぽい
    inputEl.addEventListener('input', async (e: Event) => {
        ul.innerHTML = '';

        // e.targetのDOMは確定しないので、type guardでHTMLInputElementであることを保証した上でvalue propertyを呼び出す
        if (!(e.target instanceof HTMLInputElement)) return;

        if (e.target.value === '') return;

        const params = new URLSearchParams({
            search: e.target.value,
        });

        const response = await fetch(`${apiUrl}?${params}`);
        const data = await response.json();

        // 返ってきたcountryクラスの配列それぞれに対し、国名をliタグの中に表示する処理
        data.forEach((el: CountryObject) => {
            const li = document.createElement('li');
            li.classList.add('js-suggestLi');
            li.textContent = el.name;

            ul.appendChild(li);
        });
    });

    // ulタグ以内がクリックされた時、対象がliタグであればその国名を取得し、inputタグに反映
    ul.addEventListener('click', (e: MouseEvent): void => {
        const clicked = (e.target as HTMLElement).closest('.js-suggestLi');

        if (!clicked) return;

        inputEl.value = clicked.textContent!;

        ul.innerHTML = '';
    });
};

/**
 * 1.inputに文字を入力すると発火
 * 2.入力した文字をvalueに持つoptionがdatalist内にあるかcheck
 * 3.あった場合、そのdata-place-idを取得
 * 4.select tag内からdata-place-idと同じ値をvalueに持つoptionにselectedを付与
 */
export const reflectInput = (): void => {
    const suggestionInput = document.querySelector(
        '.js-suggestionInput'
    )! as HTMLInputElement;
    const placeSelect = document.querySelector(
        '.js-countriesSelect'
    )! as HTMLSelectElement;

    suggestionInput.addEventListener('change', (ev: Event) => {
        const targetValue = (ev.target as HTMLInputElement).value;

        const targetOptionElement = suggestionInput.list!.querySelector(
            `[value=${targetValue}]`
        ) as HTMLOptionElement;

        // inputに紐づくdatalist tagは、.listでアクセスできる
        const placeId = targetOptionElement?.dataset.countryId;

        // 入力と一致する値が無ければ、undefinedになる
        if (placeId === undefined) return;

        /**
         * for of は babelで変換したコードが処理性能が悪いらしく、eslintで引っ掛かるため、findで対応
         * ref：
         *  https://qiita.com/putan/items/0c0037ce00d21854a8d0
         *  https://qiita.com/putan/items/0c0037ce00d21854a8d0#comment-f54f6b228c89da0ebf63
         */
        const matchedOption = Array.from(placeSelect.options).find(
            (option) => option.value === placeId
        );
        matchedOption!.selected = true;
    });
};

/**
 * 1.画像を選択時に発火
 * 2.input[file]から該当のfile情報を取得してblobUrlを生成
 * それをimgタグのsrcに設定し、is-hiddenクラスを取り除いて画面に表示する
 */
export const displayInputImg = (): void => {
    const inputImgElement = document.querySelector(
        '.js-inputImg'
    )! as HTMLInputElement;

    inputImgElement.addEventListener('change', (e: Event) => {
        const files = (e.target! as HTMLInputElement).files;

        if (!files) return;

        const blobUrl = window.URL.createObjectURL(files[0]);

        const img = document.querySelector(
            '.js-displayImg'
        ) as HTMLImageElement;
        img.src = blobUrl;

        img.addEventListener('load', () => img.classList.remove('is-hidden'));
    });
};
