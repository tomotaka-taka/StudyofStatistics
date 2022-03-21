<?php

// function.phpの取り込み
require_once __DIR__.'/../lib/functions.php';

// 入力値を変数に入れる
$id = $_POST['id'] ?? '';
$selectedAnswer = $_POST['selectedAnswer'] ?? '';

// データ情報を取得
$data = fetchById($id);

// // データが見つからない場合
if (empty($data)) {
    $response = [
        'message' => 'The specified id could not be found',
    ];
    error404Json($response);
}

// データ整形
$formattedData = generateFormattedData($data);

// 変数を利用しやすい変数に入れる
$correctAnswer = $formattedData['correctAnswer'];
$correctAnswerValue = $formattedData['answers'][$correctAnswer];
$explanation = $formattedData['explanation'];

// 回答判定（結果を$resultへ）
$result = $selectedAnswer == $correctAnswer;

// レスポンス情報を変数にまとめる
$response = [
    'result' => $result,
    'correctAnswer' => $correctAnswer,
    'correctAnswerValue' => $correctAnswerValue,
    'explanation' => $explanation,
];

// JSON
echo json_encode($response);
