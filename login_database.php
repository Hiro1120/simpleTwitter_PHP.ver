<?php
//クリックジャッキング対策
header('X-FRAME-OPTIONS:DENY');

    require_once("login.php");

    // echo '<pre>';
    // var_dump ($_POST['token']);
    // //var_dump ($_POST['login_password']);
    // echo '<pre>';

//ログインフォームで入力された値を受け取る
$accountOrEmail = $_POST["accountOrEmail"];
$login_password = $_POST["login_password"];

$pageFlag = 0;

//if文でページを切り替える処理
//ログイン入力画面で値が入力されていたらログイン画面に切り替える
if(!empty($_POST['btn_login'])){
    $pageFlag = 3;
}

//MySQLへの接続確認
   define('HOSTNAME', 'localhost');
   define('DATABASE', 'simple_twitter');
   define('USERNAME', 'root');
   define('PASSWORD', 'root');
   
//--------------------------------------------------------------------------------------------------------------------------
   try {
       //DBからユーザーデータを取得する
    $db = new PDO('mysql:host=' . HOSTNAME . ';dbname=' . DATABASE, USERNAME, PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $selectData = $db -> prepare('SELECT * FROM `users` WHERE (`account` = ? OR `email` = ?) AND `password` = ?');
    $selectData -> bindValue(1, $accountOrEmail);
    $selectData -> bindValue(2, $accountOrEmail);
    $selectData -> bindValue(3, $login_password);

    // executeでクエリを実行
    $selectData->execute();

    $result = $selectData -> fetchAll(PDO::FETCH_ASSOC);

    if(empty($result)){
        $msg = 'そのユーザーは登録されておりません。再度入力してください';
    }else{
        $msg = 'データを正常に取得できました。';
    }

    foreach($result as $row){
        $login_account = $row['account'];
        $login_email = $row['email'];
        $login_password = $row['password'];
    }

   } catch (PDOException $e) {
     $isConnect = false;
     $msg       = "データを正常に取得できませんでした。<br>(" . $e->getMessage() . ")";
   } 
//--------------------------------------------------------------------------------------------------------------------------
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>MySQL接続確認</title>
  </head>
  <body>
    <p>【MySQL接続確認】</p>
    <p><?php echo $msg; ?></p>
    <?php 
    if($pageFlag = 3 && !empty($result)){
            echo '<h2>'.'ユーザー「'.$login_account.'」でログインしました。'.'</h2>';
    }
    ?>
  </body>
</html>