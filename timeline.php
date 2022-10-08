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
    $selectData = $db -> prepare('SELECT messages.id, messages.user_id, messages.text, messages.created_date, users.account, users.NAME FROM `messages` INNER JOIN `users` ON messages.user_id = users.id ORDER BY id DESC');

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

        $display_messages[] = '<br>'.'<font color="#08ffc8">'.$name.'@'.$account.'</font>'.'<br>'.$message_text.'<br>'.'<font color="#5bd1d7">'.$created_date.'</font>'.'<br>';
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
        <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <title>簡易Twitter（ログイン済み）</title>
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
        <div class="center">
        <font color="#FCD271"><?php echo h($msg) ;?></font>
    </div>
    <?php endif ;?>

    <!--送信確認画面-->
    <?php  if($pageFlag === 2) :?>
        <div class="header">
            <a href="top.php">トップページ</a>
            <a href="timeline.php">タイムライン</a>
        </div>
        <br />
        <br />
        <div class="center">
        <form action="timeline.php" method="POST">
        <?php if(!empty($errors) && !empty($_POST['btn_confirm'])) : ?>
            <div class="validation">  
            <?php echo '<ul>' ;?>
                <?php
                    foreach($errors as $error){
                        echo '<li>' . $error . '</li>';
                    }
                ?>
                <?php echo '</ul>' ;?>
                </div>
            <?php endif ;?>
            <br />
            <br />
            <?php if(empty($errors)) :?>
            <font color="#FCD271">つぶやき内容</font>
            <br>
            <div class="message-block">
            <?php echo $_POST['message_text']; ?>
            <input type="hidden" name="message_text" value="<?php echo h($_POST['message_text']); ?>">
            <input type="hidden" name="login_id" value="<?php echo h($_POST['login_id']); ?>">
            <br>
            </div>
            <br>
                    <button type="submit" class="btn btn-primary" name="btn_tweet" value="つぶやく">つぶやく</button>
                <?php endif ;?>
                
        </form>
        <br/>
            <a href="timeline.php">つぶやき入力画面に戻る</a>
            </div>
    <?php endif ;?>

    <!--つぶやき画面-->
    <?php  if($pageFlag === 1) :?>
        <div class="header">
            <a href="top.php">トップページ</a>
            <a href="login.php">ログアウト</a>
        </div>
        <br />
        <br />
        <div class="center--login-timeline">
        <div class="sticky">
            <form action="timeline.php" method="POST">
                <?php 
                    $key = array_key_last($_SESSION['login_name']);
                    echo '<font color="08ffc8">'.$_SESSION['login_name'][$key].'</font>';
                 ?>
                 <font color="aliceblue">さん</font>
                 <br />
                 <font color="aliceblue">いま、どうしてる？</font>
                <br />
                <textarea name="message_text" cols="60" rows="5" value="<?php echo h($_POST['message_text']); ?>"></textarea>
                <input type="hidden" name="login_id" value="<?php echo h($_POST['login_id']); ?>">
                <br />
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="btn_confirm" value="確認する">確認する</button>
                    <font color="#FCD271">（140文字まで）</font>
                </div>
    </div>
                <br />
                <div class="message-block">
                <?php foreach($display_messages as $display_message) :?>
                    <?php echo nl2br($display_message); ?>
                <?php endforeach ;?>
                </div>
            </form>
                </div>
    <?php endif ;?>
    </body>
</html>
