<?php
$pathFile = file(__DIR__ . '/path.txt');
$path = explode('：',$pathFile[0]);
//パスワード（暗号化）
(password_hash($path[1], PASSWORD_BCRYPT));
?>

<!DOCTYPE html>
<html>
<head>
    <!--キャッシュを無効にする-->
    <meta http-equiv="Cache-Control" content="no-store">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/top.css">

	<title>ようこそ</title>
	<link rel="stylesheet" type="text/css" href="../css/top.css">
</head>
<body>
	<header>
		<h1>PHP Twitter</h1>
	</header>
	<section>
		<h2>アプリの説明</h2>
		<p>PHP/HTML/CSSのみで実装したアプリです。</p>
	</section>
	<section>
		<h2>アプリの特徴</h2>
		<ul>
			<li>アカウントが作成できる</li>
			<li>ログイン機能がある</li>
			<li>ツイートができる</li>
		</ul>
	</section>
	<section>
		<h2>ご利用いただくには</h2>
		<p>アカウント作成後、アプリにログインすることで本アプリを利用できます。</p>
	</section>
	<button class="app-btn" onclick="location.href='../top.php'">アプリを開く</button>
</body>
</html>
