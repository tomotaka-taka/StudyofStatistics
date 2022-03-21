<?php

// function.phpの取り込み
require_once __DIR__.'/../lib/functions.php';

// 入力値を変数に入れる
$id = $_GET['id'] ?? '';

// データ情報を取得
$data = fetchById($id);

// データが見つからない場合
if (empty($data)) {
    error404();
}

// データを整形
$formattedData = generateFormattedData($data);

// HTML内に埋め込む情報を変数にまとめる
$assignData = [
    'id' => escape($id),
    'chapter' => $formattedData['chapter'],
    'answers' => $formattedData['answers'],
];

// 出力
loadTemplate('chapter', $assignData);
