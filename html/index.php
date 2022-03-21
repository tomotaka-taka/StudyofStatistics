<?php

// function.phpの取り込み
require_once __DIR__.'/../lib/functions.php';

// データ一覧を取得
$dataList = fetchAll();

// データが見つからない場合
if (empty($dataList)) {
    error404();
}

// データを整形してエスケープ
$chapters = [];
foreach ($dataList as $data) {
    $chapters[] = generateFormattedData($data);
}

// HTML内に埋め込む情報を変数にまとめる
$assignData = [
    'chapters' => $chapters,
];

// 出力
loadTemplate('index', $assignData);
