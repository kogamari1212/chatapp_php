<?php
// POSTデータ確認

//入力チェック
if (
    !isset($_POST['name']) || $_POST['name'] === '' ||
    !isset($_POST['text']) || $_POST['text'] === ''
) {
    exit('データがありません');
}
//データ受け取り
$name = $_POST['name'];
$text = $_POST['text'];

// DB接続
include('functions.php');

// DB接続
try {
    $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
}

// SQL作成&実行

// SQL作成&実行
$sql = 'INSERT INTO message (id, name, text, created_at) VALUES (NULL, :name, :text, now())';

$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':text', $text, PDO::PARAM_STR);

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

header('Location:index.php');
exit();