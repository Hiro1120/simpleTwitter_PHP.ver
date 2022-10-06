<?php 

if(!isset($_SESSION)){
    session_start();
}

require_once('validation.php');

    //クリックジャッキング対策
    header('X-FRAME-OPTIONS:DENY');

    //クロスサイトスクリプティング攻撃対策用の関数を定義
    function h($str){
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    //エラーメッセージ
    $errors = validation_tweet($_POST);

    //画面の切り替え
    //----------------------------------------------------------------------------------------------------
    $pageFlag = 1;

    if(!empty($_POST['btn_confirm'])){
        $pageFlag = 2;
    }

    if(!empty($_POST['btn_tweet']) && empty($errors)){
        $pageFlag = 3;
    }
    //-----------------------------------------------------------------------------------------------------

        $_SESSION['message_user_id'][]= $_POST["login_id"];
        $_SESSION['message_text'][] = $_POST["message_text"];
    if(!empty($_POST["login_name"])){
        $_SESSION['login_name'][] = $_POST["login_name"];
    }

    //null値・空文字・0の削除、重複値の整理、キーを振り直す
    $array_message_user_id = array_values(array_unique(array_filter( $_SESSION['message_user_id'])));
    $array_message_text = array_values(array_unique(array_filter( $_SESSION['message_text'])));


    if(isset($_POST["login_id"]) && !empty($_POST["login_id"])){
        $register_login_id = $_POST["login_id"];
    }
    
    if(isset($_POST["message_text"]) && !empty($_POST["message_text"])){
        $register_message_text = $_POST["message_text"];
    }

//タイムラインつぶやき一覧
if($pageFlag === 1){

    //MySQLへの接続確認
   define('HOSTNAME', 'localhost');
   define('DATABASE', 'simple_twitter');
   define('USERNAME', 'root');
   define('PASSWORD', 'root');
   
//--------------------------------------------------------------------------------------------------------------------------
   try {
       //DBからつぶやきデータを取得する
    $db = new PDO('mysql:host=' . HOSTNAME . ';dbname=' . DATABASE, USERNAME, PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $selectData = $db -> prepare('SELECT messages.id, messages.user_id, messages.text, messages.created_date, users.account, users.NAME FROM `messages` INNER JOIN `users` ON messages.user_id = users.id');

    // executeでクエリを実行
    $selectData->execute();

    $result = $selectData -> fetchAll(PDO::FETCH_ASSOC);


    foreach($result as $row){

        $message_id = $row['id'];
        $message_user_id = $row['user_id'];
        $message_text = $row['text'];
        $created_date = $row['created_date'];
        $account = $row['account'];
        $name = $row['NAME'];

        $display_messages[] = '<br>'.'<font color="blue">'.$name.'@'.$account.'</font>'.'<br>'."　　".$message_text.'<br>'.'<font color="blue">'.$created_date.'</font>'.'<br>';
    }

   } catch (PDOException $e) {
     $isConnect = false;
     $msg       = "データを正常に取得できませんでした。<br>(" . $e->getMessage() . ")";
   } 

}

//--------------------------------------------------------------------------------------------------------------------------


//MySQLへの接続確認
   define('HOSTNAME', 'localhost');
   define('DATABASE', 'simple_twitter');
   define('USERNAME', 'root');
   define('PASSWORD', 'root');
   
   if(!empty($_POST['btn_tweet']) && !empty($_POST['message_text'])){ 
//--------------------------------------------------------------------------------------------------------------------------
   //DBにつぶやき情報を登録する
    try {
     $db  = new PDO('mysql:host=' . HOSTNAME . ';dbname=' . DATABASE, USERNAME, PASSWORD);
      $addData = $db -> exec("INSERT INTO `messages` (`id`, `user_id`, `text`, `created_date`, `updated_date`)
                                 VALUES(NULL, '$register_login_id', '$register_message_text', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);");
 
        $msg = "つぶやきを送信しました。";

   } catch (PDOException $e) {
     $isConnect = false;
     $msg       = "つぶやくことができませんでした。<br>(" . $e->getMessage() . ")";
   } 
//--------------------------------------------------------------------------------------------------------------------------
   }
?>

    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>簡易Twitter</title>
    </head>

    <body>

    <!--送信完了画面-->
    <?php  if($pageFlag === 3) :?>
        <div class="header">
            <a href="top.php">トップページ</a>
            <a href="timeline.php">タイムライン</a>
        </div>
        <br />
        <br />
        <?php echo h($msg) ;?>
    <?php endif ;?>

    <!--送信確認画面-->
    <?php  if($pageFlag === 2) :?>
        <div class="header">
            <a href="top.php">トップページ</a>
            <a href="timeline.php">タイムライン</a>
        </div>
        <br />
        <br />
        <form action="timeline.php" method="POST">
        <?php if(!empty($errors) && !empty($_POST['btn_confirm'])) : ?>
            <?php echo '<ul>' ;?>
                <?php
                    foreach($errors as $error){
                        echo '<li>' . $error . '</li>';
                    }
                ?>
                <?php echo '</ul>' ;?>
            <?php endif ;?>
            <br />
            <br />
            <?php if(empty($errors)) :?>
            つぶやき内容
            <br />
            <?php echo $_POST['message_text']; ?>
            <input type="hidden" name="message_text" value="<?php echo h($_POST['message_text']); ?>">
            <input type="hidden" name="login_id" value="<?php echo h($_POST['login_id']); ?>">
            <br />
                    <input type="submit" name="btn_tweet" value="つぶやく">
                <?php endif ;?>
                
        </form>
        <br/>
            <a href="timeline.php">つぶやき入力画面に戻る</a>
    <?php endif ;?>

    <!--つぶやき画面-->
    <?php  if($pageFlag === 1) :?>
        <div class="header">
            <a href="top.php">トップページ</a>
            <a href="">ログアウト</a>
        </div>
        <br />
        <br />
            <form action="timeline.php" method="POST">
                <?php 
                    $key = array_key_last($_SESSION['login_name']);
                    echo $_SESSION['login_name'][$key];
                 ?>さん<br />
                いま、どうしてる？<br />
                <textarea name="message_text" cols="100" rows="5" value="<?php echo h($_POST['message_text']); ?>"></textarea>
                <input type="hidden" name="login_id" value="<?php echo h($_POST['login_id']); ?>">
                <br />
                <input type="submit" name="btn_confirm" value="確認する">（140文字まで）
                <br />
                <?php foreach($display_messages as $display_message) :?>
                    <?php echo nl2br($display_message); ?>
                <?php endforeach ;?>
            </form>
    <?php endif ;?>
    </body>
</html>
