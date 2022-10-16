<?php
    //クリックジャッキング対策
    header('X-FRAME-OPTIONS:DENY');

    //ユーザー情報とつぶやきをタイムラインに表示
    //--------------------------------------------------------------------------------------------------------------------------
    define('HOSTNAME', 'localhost');
    define('DATABASE', 'simple_twitter');
    define('USERNAME', 'root');
    define('PASSWORD', 'root');
   
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

?>

<!DOCTYPE html>
    <html lang="ja">
    <head>
        <!--キャッシュを無効にする-->
    <meta http-equiv="Cache-Control" content="no-store">

        <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">

        <title>簡易Twitter</title>
    </head>
    <body>
        <div class="header">
            <div class="col1">
                <a href="login.php">ログイン</a>
                <a href="signup.php">登録する</a>
            </div>
        </div>
        <div class="main-content">
            <div class="colum1">
                <div class="main">
                    <div class="message-block">
                        <?php foreach($display_messages as $display_message) :?>
                            <div class="border-bottom border-light p-2">
                                <?php echo nl2br($display_message); ?>
                            </div>
                        <?php endforeach ;?>
                    </div>
                </div><!--main-->
                <div class="left-sidebar">
                    <iframe  class= "frame_center" src="https://www.famitsu.com" width="100%" height="1500"></iframe>
                </div>
            </div><!--colum1-->

            <div class="colum2">
                <input type="image" onclick="location.href='maintenance/index.php'" name="btn_image" src="./image/icon.PNG" value="スタート画面に戻る">
                
                <div class="right-sidebar">
                    <div class="video">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/hBl5r_uxbSY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="video2">
                        <iframe width="500" height="315" src="https://www.youtube.com/embed/Jgm4D0n4gxk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="video3">
                        <iframe width="500" height="315" src="https://www.youtube.com/embed/DtE1nxSb4d4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div><!--right-sidebar-->
            </div><!--colum2-->
        </div><!--main-content-->
    </body>
</html>