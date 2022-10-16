<?php

session_start();
//クリックジャッキング対策
header('X-FRAME-OPTIONS:DENY');


//クロスサイトスクリプティング攻撃対策用関数の定義
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//ログインフォームで入力された値を受け取る
$accountOrEmail = $_POST["accountOrEmail"];
$login_password = $_POST["login_password"];

$pageFlag = 0;
$btn_login = '';

if(!empty($_POST['btn_login'])){
    $pageFlag = 3;
}

//ユーザー情報とつぶやきをタイムラインに表示
//--------------------------------------------------------------------------------------------------------------------------
   define('HOSTNAME', 'localhost');
   define('DATABASE', 'simple_twitter');
   define('USERNAME', 'root');
   define('PASSWORD', 'root');
   
    //DBからユーザーデータを取得する
   try {
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
            $login_id = $row['id'];
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
    <!--トークンが一致しているか判定する-->
    <?php if($_POST['login_csrf'] === $_SESSION['login_csrfToken']) :?>
        <!DOCTYPE html>
            <html lang="ja">
            <head>
                <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

                <!-- Bootstrap CSS -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
                <link rel="stylesheet" href="css/style.css">
                <title>ログイン完了</title>
            </head>

            <body>
                <div class="center">
                    <div class="message-block">
                        <?php 
                            if(!empty($result)){
                                echo '<font color="#FCD271">'.'ログイン完了'.'</font>';
                            }else{
                                echo $msg;
                            }
                        ?>

                        <?php if($pageFlag = 3 && !empty($result)) :?>      
                            <?php $token = $_SESSION['login_csrfToken']; ?>
                            <h2><?PHP echo $login_name ;?>でログインしました。</h2>
                            <br />
                            <form action="timeline.php" method="POST">     

                                <button type="submit" class="btn btn-primary" name="btn_timeline" value="タイムラインへ">タイムラインへ</button>

                                <input type="hidden" name="login_id" value="<?php echo h($login_id); ?>">
                                <input type="hidden" name="login_account" value="<?php echo h($login_account); ?>">
                                <input type="hidden" name="login_name" value="<?php echo h($login_name); ?>">
                                <input type="hidden" name="login_csrf" value="<?php echo $token; ?>">
                                <input type="hidden" name="login_csrf" value="<?php echo h($_POST['login_csrf']);?>">
                            </form>
                        <?php endif ;?>

                    </div><!--message-block-->
                    <br />
                        <a href="login.php">ログイン情報入力画面に戻る</a>
                </div><!--center-->
            </body>
        </html>
    <?php endif ;?>
<?php endif ;?>