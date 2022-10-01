<?php 
//クリックジャッキング対策
header('X-FRAME-OPTIONS:DENY');

// if(!empty($_POST['accountOrEmail'])){
//     echo '<pre>';
//     var_dump ($_POST['login_password']);
//     echo '<pre>';
// }

include("signup.php");

//ユーザー登録フォームで入力された値を受け取る
$signup_name = $_POST['signup'];
$name = $_POST["name"];
$account = $_POST["account"];
$password = $_POST["password"];
$email = $_POST["email"];
$description = $_POST["description"];


//MySQLへの接続確認
   define('HOSTNAME', 'localhost');
   define('DATABASE', 'simple_twitter');
   define('USERNAME', 'root');
   define('PASSWORD', 'root');
   
//--------------------------------------------------------------------------------------------------------------------------
   try {
       //DBにユーザーデータを登録する
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

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>MySQL接続確認</title>
  </head>
  <body>
    <?php echo $finish_register?>
    <h1>MySQL接続確認</h1>
    <p><?php echo $msg; ?></p>
    <br>
    <a href="top.php">トップに戻る</a>
  </body>
</html>