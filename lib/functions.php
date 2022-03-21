<?php

function loadTemplate($filename, array $assignData = [])
{
    if ($assignData) {
        extract($assignData);
    }

    include __DIR__ . '/../template/'.$filename.'.tpl.php';
}

function error404()
{
    // レスポンスのヘッダ->404
    header('HTTP/1.1 404 Not Found');
    // レスポンス種類
    header('Content-Type: text/html; charset=UTF-8');

    loadTemplate('404');

    // PHPスクリプト終了(0は正常に終了)
    exit(0);
}

/**
 * 404のJsonを出力して、終了する
 */
function error404Json($response)
{
    // レスポンスのヘッダ->404
    header('HTTP/1.1 404 Not Found');

    // レスポンス種類
    header('Content-Type: application/json; charset=UTF-8');

    // json出力
    echo json_encode($response);

    // PHPスクリプト終了(0は正常に終了)
    exit(0);
}

/**
 * 全データを取得
 */
function fetchAll()
{
    // 問題の情報一覧をを保存する入れ物を用意
    $chapters = [];

    // ファイル操作の準備をする(r: 読み込み専用)
    $handle = fopen(__DIR__.'/data.csv', 'r');

    // ファイルが操作できるか判定
    if ($handle === false) {
        // 操作できないときは空を返す
        return $chapters;
    }

    // ファイルの中身を1行ずつ取得する
    while ($row = fgetcsv($handle)) {
        // 問題データ以外は無視する
        if (isDataRow($row)) {
            // 問題だけを配列に追加する
            $chapters[] = $row;
        }
    }

    // ファイルの操作を終了する
    fclose($handle);

    // 取得できた値を返す
    return $chapters;
}

//  指定されたIDの問題を取得
function fetchById($id)
{
    foreach (fetchAll() as $row) {
        // 指定されたIDと一致するか確認
        if ($row[0] === $id) {
            // 一致した行を返す
            return $row;
        }
    }

    // IDがヒットしない場合（空要素）
    return [];
}

// 問題データの行か判定
function isDataRow(array $row)
{
    // データの項目数が足りているか判定
    if (count($row) !== 8) {
        return false;
    }

    // データの項目の中身がすべて埋まっているか確認
    foreach ($row as $value) {
        // 項目の値が空か判定
        if (empty($value)) {
            return false;
        }
    }

    // idの項目が数字ではない場合は無視
    if (!is_numeric($row[0])) {
        return false;
    }

    // 正しい答えはどれか
    $correctAnswer = strtoupper($row[6]);
    $availableAnswers = ['A', 'B', 'C', 'D'];
    if (!in_array($correctAnswer, $availableAnswers)) {
        return false;
    }

    // 問題なければtrue
    return true;
}

/**
 * 取得できたデータを利用しやすいように連想配列に変換
 * 値をHTMLに組み込めるようにエスケープも行う
 */
function generateFormattedData($data)
{
    // 構造化した配列を作成する
    $formattedData = [
        'id' => escape($data[0]),
        'chapter' => escape($data[1], true),
        'answers' => [
            'A' => escape($data[2]),
            'B' => escape($data[3]),
            'C' => escape($data[4]),
            'D' => escape($data[5]),
        ],
        'correctAnswer' => escape(strtoupper($data[6])),
        'explanation' => escape($data[7], true),
    ];

    return $formattedData;
}

/**
 * エスケープ処理
 */
function escape($data, $nl2br = false)
{
    $convertedData = htmlspecialchars($data, ENT_HTML5);

    // 改行コードを<br>タグに変換するか判定
    if ($nl2br) {
        /// 改行コードを<br>タグに変換したものをを返却
        return nl2br($convertedData);
    }

    return $convertedData;
}
