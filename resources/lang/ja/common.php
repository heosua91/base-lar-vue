<?php

return [

   'import' => [
        'no_header' => 'ヘッダー行を追加してください。',
        'header_not_match' => 'フォーマットが違います。',
        'max_row' => '一度に登録できる件数は' . config('member.max_row_import_csv') . '件までです。',
   ]

];
