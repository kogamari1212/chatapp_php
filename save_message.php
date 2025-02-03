<?php

// データベース接続情報
include('functions.php');

// JSON形式で受け取る
$input = json_decode(file_get_contents('php://input'), true);
$name = $input['name'] ?? '';
$text = $input['text'] ?? '';

if (!$name || !$text) {
    echo json_encode(['success' => false, 'error' => '名前とコメントは必須です。']);
    exit;
}

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データを挿入
    $stmt = $pdo->prepare("INSERT INTO messages (name, text) VALUES (:name, :text)");
    $stmt->execute([':name' => $name, ':text' => $text]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
