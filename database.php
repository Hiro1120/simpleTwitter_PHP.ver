<?php 
//クリックジャッキング対策
header('X-FRAME-OPTIONS:DENY');

require_once("signup.php");

//ユーザー登録フォームで入力された値を受け取る
$signup_name = $_POST['signup'];
$name = $_POST["name"];
$account = $_POST["account"];
$password = $_POST["password"];
$email = $_POST["email"];
$description = $_POST["description"];


//ユーザー情報の登録
//--------------------------------------------------------------------------------------------------------------------------
   define('HOSTNAME', 'localhost');
   define('DATABASE', 'simple_twitter');
   define('USERNAME', 'root');
   define('PASSWORD', 'root');
   
    //DBにユーザーデータを登録する
   try {
      $db  = new PDO('mysql:host=' . HOSTNAME . ';dbname=' . DATABASE, USERNAME, PASSWORD);
      $addData = $db -> exec("INSERT INTO `users` (`id`, `account`, `NAME`, `email`, `password`, `description`, `created_date`, `updated_date`)
                              VALUES(NULL, '$account', '$name', '$email', '$password', '$description', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);");

      $msg = "設定された内容が正常にデータベースに登録されました。";

      $finish_register = <<<_EOD_
          <?php if($pageFlag === 2) : ?>
          <?php if({$_POST['csrf']} === {$_SESSION['csrfToken']}) :?>
                  登録が完了しました。
              <!--完了画面が表示されるとトークンを削除する-->
              <?php unset({$_SESSION['csrfToken']}); ?>
          <?php endif; ?>
          <?php endif; ?>

          _EOD_;

   } catch (PDOException $e) {
     $isConnect = false;
     $msg       = "データは正常に登録できませんでした。<br>(" . $e->getMessage() . ")";
   } 
//--------------------------------------------------------------------------------------------------------------------------
?>

<!DOCTYPE html>
  <html lang="ja">
    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">

          <!-- Bootstrap CSS -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
          <link rel="stylesheet" href="css/style.css">
      <title>ユーザー登録完了</title>
    </head>

  <body>
    <div class="center">
      <div class="message-block">
        <?php echo $finish_register?>
        <br>
        <p><?php echo $msg; ?></p>
      <br>
      </div>
        <a href="top.php">トップに戻る</a>
    </div>
  </body>
</html>