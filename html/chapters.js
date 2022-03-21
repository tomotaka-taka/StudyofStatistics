// 選択肢一覧を取得
const answersList = document.querySelectorAll('ol.answers li');
// クリック時の処理
answersList.forEach(li => li.addEventListener('click', checkClickedAnswer));

// 答えクリック時の処理
function checkClickedAnswer(event) {
    // addEventListenerによってイベント検知した対象(li要素)を取得
    const clickedAnswerElement = event.currentTarget;
    // 選択した答え
    const selectedAnswer = clickedAnswerElement.dataset.answer;
    // 親要素のolから、data-idの値を取得
    const chapterId = clickedAnswerElement.closest('ol.answers').dataset.id;

    // 送信するデータを作成
    const formData = new FormData();
    formData.append('id', chapterId);
    formData.append('selectedAnswer', selectedAnswer);

    // リクエスト
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'answer.php');
    xhr.send(formData);
    
    // 読み込みが終わったときのイベントを追加
    xhr.addEventListener('loadend', function(event) {
        const xhr = event.currentTarget;

        if (xhr.status === 200) {
            // リクエストが成功したとき、レスポンスの値をJavaScriptで利用できるように準備
            const response = JSON.parse(xhr.response);

            // レスポンスの値をわかりやすい変数に代入
            const result = response.result;
            const correctAnswer = response.correctAnswer;
            const correctAnswerValue = response.correctAnswerValue;
            const explanation = response.explanation;

            // 表示処理
            displayResult(result, correctAnswer, correctAnswerValue, explanation);
        } else {
            alert('Error: データの取得に失敗しました');
        }
    });
}

// 結果の表示
function displayResult(result, correctAnswer, correctAnswerValue, explanation) {
    // メッセージを入れる変数
    let message;
    // カラーコードを入れる変数
    let answerColorCode;

    // 答えが正しいか判定
    if (result) {
        // 正しい答えだったとき
        message = '正解です！';
        answerColorCode = '';
    } else {
        // 間違えた答えだったとき
        message = '不正解です。';
        answerColorCode = '#f05959';
    }

    // アラートで正解/不正解を出力
    alert(message);

    // 正解の内容をHTMLに埋め込む
    document.querySelector('span#correct-answer').innerHTML = correctAnswer + '. ' + correctAnswerValue;
    document.querySelector('span#explanation').innerHTML = explanation;

    // 色を変更(間違っていたとき->色が変わる)
    document.querySelector('span#correct-answer').style.color = answerColorCode;
    // 答え全体を表示
    document.querySelector('div#section-correct-answer').style.display = 'block';
}
