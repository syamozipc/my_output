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
    const inputEl = <HTMLInputElement>(
        document.querySelector('.js-suggestionInput')!
    );

    const ul = <HTMLUListElement>document.querySelector('.js-suggestionList')!;

    const baseApiUrl: string = inputEl.dataset.suggestUrl!;

    // 値が入力されたらそれを取得し、部分一致する国の取得をapiリクエスト
    // inputはInputEvent/Eventどちらかになるため、最初からInputEventを指定できないっぽい
    inputEl.addEventListener('input', (e: Event): void => {
        // callbackに直接書くとcatchが使用できずerror handling出来ないため、IIFEで定義
        (async (): Promise<void> => {
            ul.innerHTML = '';

            // e.targetのDOMは確定しないので、type guardでHTMLInputElementであることを保証した上でvalue propertyを呼び出す
            if (!(e.target instanceof HTMLInputElement)) return;

            if (!e.target.value) return;

            const queryString = new URLSearchParams({
                search: e.target.value,
            });

            const apiUrl = `${baseApiUrl}?${String(queryString)}`;

            const response: Response = await fetch(apiUrl);
            // TODO:ここのeslintエラー解消できない
            // eslint-disable-next-line @typescript-eslint/no-unsafe-assignment
            const data: CountryObject[] = await response.json();

            // 返ってきたcountryクラスの配列それぞれに対し、国名をliタグの中に表示する処理
            data.forEach((el: CountryObject): void => {
                const li = document.createElement('li');
                li.classList.add('js-suggestLi');
                li.textContent = el.name;

                ul.appendChild(li);
            });
        })().catch((error: string): void => {
            window.alert(error);
        });
    });

    // ulタグ以内がクリックされた時、対象がliタグであればその国名を取得し、inputタグに反映
    ul.addEventListener('click', (e: MouseEvent): void => {
        const clicked = (<HTMLElement>e.target).closest('.js-suggestLi');

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
    const suggestionInput = <HTMLInputElement>(
        document.querySelector('.js-suggestionInput')!
    );
    const placeSelect = <HTMLSelectElement>(
        document.querySelector('.js-countriesSelect')!
    );

    suggestionInput.addEventListener('change', (ev: Event) => {
        const targetValue = (<HTMLInputElement>ev.target).value;

        const targetOptionElement = <HTMLOptionElement>(
            suggestionInput.list!.querySelector(`[value=${targetValue}]`)
        );

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
    const inputImgElement = <HTMLInputElement>(
        document.querySelector('.js-inputImg')!
    );

    inputImgElement.addEventListener('change', (e: Event) => {
        const files = (<HTMLInputElement>e.target!).files;

        if (!files) return;

        const blobUrl = window.URL.createObjectURL(files[0]);

        const img = <HTMLImageElement>document.querySelector('.js-displayImg');
        img.src = blobUrl;

        img.addEventListener('load', () => img.classList.remove('is-hidden'));
    });
};
