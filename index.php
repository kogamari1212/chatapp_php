<?php
// エラー表示を有効化
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB接続
include('functions.php');


try {
    $pdo = new PDO($dbn, $user, $pwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["db error" => $e->getMessage()]);
    exit();
}

// SQL作成&実行
$sql = "SELECT * FROM message";
$stmt = $pdo->prepare($sql);

try {
    $status = $stmt->execute();
    if (!$status) {
        echo json_encode(["sql error" => $stmt->errorInfo()]);
        exit();
    }
} catch (PDOException $e) {
    echo json_encode(["sql error" => $e->getMessage()]);
    exit();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = "";

foreach ($result as $record) {
    $name = htmlspecialchars($record["name"] ?? '名無し', ENT_QUOTES, 'UTF-8');
    $text = htmlspecialchars($record["text"] ?? '（メッセージなし）', ENT_QUOTES, 'UTF-8');

    $output .= "
    <figure>
      <div><span>{$name}</span></div>
    </figure>
    <div class=\"text_area\">
      <p class=\"line_name\">{$name}</p>
      <p class=\"line_text\">{$text}</p>
      <span class=\"line_date\"></span>
    </div>
    ";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# website: https://ogp.me/ns/website#">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
	if (!/iPhone|iPod|Android/.test(navigator.userAgent)) {
		document.querySelector('meta[name=viewport]').content = 'width=1400';
	}
</script>
<meta name="format-detection" content="telephone=no">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>チャットアプリ</title>
<meta name="description" content="">

<meta property="og:title" content="チャットアプリ">
<meta property="og:type" content="website" />
<meta property="og:url" content="#" />
<meta property="og:site_name" content="チャットアプリ" />
<meta property="og:description" content="" />
<meta name="format-detection" content="telephone=no">
<link rel="canonical" href="#">
<link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon">
<link rel="apple-touch-icon" href="img/web_icon.png">
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/sanitize.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />

</head>

<body>
  <section class="title_section">
    <h1>チャットアプリ</h1>
  </section>
  <!-- //title_section -->

  <section class="container">
    <div class="chat__title">LINEにオマージュされたチャット</div>
    <!-- 出力場所 -->
    <div class="line__contents scroll">
     <ul id="output"><?= $output ?></ul>
    </div>

    <!-- 入力場所 -->
    <form action="index.php" method="POST">
      <fieldset>
        <div class="in_name">
          <label class="ef">
            name : <input type="text" id="name" placeholder="お名前">
          </label>
        </div>
        <div class="in_text">
          <label class="ef">
            text : <textarea id="text" placeholder="コメント"></textarea>
          </label>
        </div>
        <div class="in_btn">
          <button type="button" id="send">送信</button>
        </div>
      </fieldset>
    </form>
  </section>
  <!-- //container -->


</body>
</html>