<?php 
    session_start();

    if($_POST["login_name"]){
        $_SESSION["login_name"] = $_POST["login_name"];
    }

    if($_POST["login_account"]){
        $_SESSION["login_account"] = $_POST["login_account"];
    }

    //クリックジャッキング対策
    header('X-FRAME-OPTIONS:DENY');

?>

    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>簡易Twitter</title>
    </head>

    <body>
            <div class="header">
                <a href="top.php">トップページ</a>
                <a href="">ホーム</a>
                <a href="">ログアウト</a>
            </div>
        </div>
        <br />
        <br />
        <div class="form-area">
            <form action="timeline.php" method="POST">
                いま、どうしてる？<br />
                <textarea name="message" cols="100" rows="5" value="<?php if(!empty($_POST['message'])){echo $_POST['message'];} ?>"></textarea>
                <br />
                <input type="submit" name="btn_tweet" value="つぶやく">（140文字まで）
            </form>
        </div>
        <br />
        <br />
        <?php if(!empty($_POST['btn_tweet'])) :?>
        <div class="message-area">
            <?php echo $_SESSION["login_name"].'@'.$_SESSION["login_account"];?><br />
            <?php echo $_POST['message'];?>
        </div>
        <?php endif ;?>
    </body>
</html>