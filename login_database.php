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
$btn_login = '';

//if文でページを切り替える処理
//ログイン入力画面でボタンが押されたらFlagに3（ログイン完了画面）を代入
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
        $login_name = $row['NAME'];
        $login_email = $row['email'];
        $login_password = $row['password'];
        $created_date = $row['created_date'];
        $updated_date = $row['updated_date'];
    }

   } catch (PDOException $e) {
     $isConnect = false;
     $msg       = "データを正常に取得できませんでした。<br>(" . $e->getMessage() . ")";
   } 
//--------------------------------------------------------------------------------------------------------------------------
?>
<?php if($pageFlag === 3 && empty($btn_timeline)) :?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>MySQL接続確認</title>
    </head>
    <body>
        <?php 
            if(!empty($result)){
                echo '【MySQL接続確認】';
            }else{
                echo $msg;
            }
        ?>

        <?php if($pageFlag = 3 && !empty($result)) :?>
            
            <h2>ユーザー「<?PHP echo $login_name ;?>」でログインしました。</h2>
        
            <form action="timeline.php" method="POST">     
                <input type="submit" name="btn_timeline" value="タイムラインへ"/>
                <input type="hidden" name="login_account" value="<?php echo $login_account; ?>">
                <input type="hidden" name="login_name" value="<?php echo $login_name; ?>">
            </form>
        <?php endif ;?>
        <br />
        <a href="login.php">ログイン情報入力画面に戻る</a>
    </body>
    </html>
<?php endif ;?>