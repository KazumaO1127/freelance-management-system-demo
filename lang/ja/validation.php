<?php

return [

    /*
    |--------------------------------------------------------------------------
    | バリデーション日本語メッセージ
    |--------------------------------------------------------------------------
    */

    'accepted' => ':attributeを承認してください。',
    'accepted_if' => ':otherが:valueの場合、:attributeを承認してください。',
    'active_url' => ':attributeは有効なURLではありません。',
    'after' => ':attributeは:dateより後の日付にしてください。',
    'after_or_equal' => ':attributeは:date以降の日付にしてください。',
    'alpha' => ':attributeは英字のみ許可されています。',
    'alpha_dash' => ':attributeは英数字・ダッシュ・アンダースコアのみ許可されています。',
    'alpha_num' => ':attributeは英数字のみ許可されています。',
    'array' => ':attributeは配列でなければなりません。',
    'before' => ':attributeは:dateより前の日付にしてください。',
    'before_or_equal' => ':attributeは:date以前の日付にしてください。',

    'between' => [
        'array' => ':attributeの項目数は:min〜:max個でなければなりません。',
        'file' => ':attributeのサイズは:min〜:maxキロバイトでなければなりません。',
        'numeric' => ':attributeは:min〜:maxの間でなければなりません。',
        'string' => ':attributeは:min〜:max文字で入力してください。',
    ],

    'boolean' => ':attributeはtrueかfalseでなければなりません。',
    'confirmed' => ':attributeと確認フィールドが一致しません。',
    'date' => ':attributeは有効な日付ではありません。',
    'date_equals' => ':attributeは:dateと同じ日付でなければなりません。',
    'date_format' => ':attributeの形式を:formatに合わせてください。',
    'different' => ':attributeと:otherは異なる値にしてください。',
    'digits' => ':attributeは:digits桁で入力してください。',
    'digits_between' => ':attributeは:min〜:max桁で入力してください。',
    'email' => ':attributeは有効なメールアドレスで入力してください。',
    'exists' => '選択された:attributeは無効です。',
    'file' => ':attributeはファイルでなければなりません。',
    'filled' => ':attributeに値を入力してください。',
    'gt' => [
        'array' => ':attributeの項目数は:valueより多くなければなりません。',
        'file' => ':attributeは:valueキロバイトより大きくなければなりません。',
        'numeric' => ':attributeは:valueより大きくなければなりません。',
        'string' => ':attributeは:value文字より多くなければなりません。',
    ],
    'gte' => [
        'array' => ':attributeの項目数は:value個以上でなければなりません。',
        'file' => ':attributeは:valueキロバイト以上でなければなりません。',
        'numeric' => ':attributeは:value以上でなければなりません。',
        'string' => ':attributeは:value文字以上でなければなりません。',
    ],
    'image' => ':attributeは画像でなければなりません。',
    'in' => '選択された:attributeは無効です。',
    'integer' => ':attributeは整数で入力してください。',
    'ip' => ':attributeは有効なIPアドレスでなければなりません。',
    'json' => ':attributeは有効なJSON形式で入力してください。',

    'lt' => [
        'array' => ':attributeの項目数は:value未満でなければなりません。',
        'file' => ':attributeは:valueキロバイト未満でなければなりません。',
        'numeric' => ':attributeは:value未満でなければなりません。',
        'string' => ':attributeは:value文字未満でなければなりません。',
    ],
    'lte' => [
        'array' => ':attributeの項目数は:value以下でなければなりません。',
        'file' => ':attributeは:valueキロバイト以下でなければなりません。',
        'numeric' => ':attributeは:value以下でなければなりません。',
        'string' => ':attributeは:value文字以下でなければなりません。',
    ],

    'max' => [
        'array' => ':attributeの項目数は:max個以下でなければなりません。',
        'file' => ':attributeは:maxキロバイト以下でなければなりません。',
        'numeric' => ':attributeは:max以下でなければなりません。',
        'string' => ':attributeは:max文字以下で入力してください。',
    ],

    'mimes' => ':attributeは次のタイプのファイルでなければなりません: :values。',
    'mimetypes' => ':attributeは次のタイプのファイルでなければなりません: :values。',

    'min' => [
        'array' => ':attributeの項目数は最低:min個必要です。',
        'file' => ':attributeは最低:minキロバイト必要です。',
        'numeric' => ':attributeは最低:min必要です。',
        'string' => ':attributeは最低:min文字で入力してください。',
    ],

    'not_in' => '選択された:attributeは無効です。',
    'not_regex' => ':attributeの形式が正しくありません。',
    'numeric' => ':attributeは数値で入力してください。',
    'present' => ':attributeを必ず送信してください。',
    'regex' => ':attributeの形式が正しくありません。',
    'required' => ':attributeは必須です。',
    'required_if' => ':otherが:valueの場合、:attributeは必須です。',
    'required_unless' => ':otherが:valuesに含まれない場合、:attributeは必須です。',
    'required_with' => ':valuesが存在する場合、:attributeは必須です。',
    'required_with_all' => ':valuesが存在する場合、:attributeは必須です。',
    'required_without' => ':valuesが存在しない場合、:attributeは必須です。',
    'required_without_all' => 'どれの:valuesも存在しない場合、:attributeは必須です。',
    'same' => ':attributeと:otherは一致している必要があります。',

    'size' => [
        'array' => ':attributeは:size個でなければなりません。',
        'file' => ':attributeは:sizeキロバイトでなければなりません。',
        'numeric' => ':attributeは:sizeでなければなりません。',
        'string' => ':attributeは:size文字で入力してください。',
    ],

    'string' => ':attributeは文字列で入力してください。',
    'timezone' => ':attributeは有効なタイムゾーンでなければなりません。',
    'unique' => ':attributeは既に存在します。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'url' => ':attributeは有効なURLで入力してください。',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    'attributes' => [
        'title' => 'タイトル',
        'client_name' => 'クライアント',
        'unit_price' => '単価',
        'start_date' => '開始日',
        'end_date' => '終了日',
        'status' => 'ステータス',
        'memo' => 'メモ',
    ],

];
