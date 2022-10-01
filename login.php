<?php
    
    //クリックジャッキング対策
    header('X-FRAME-OPTIONS:DENY');

    require_once('validation.php');

    // var_dump ($_SESSION);

    //クロスサイトスクリプティング攻撃対策用の関数を定義
    function h($str){
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    $pageFlag = 0;

?>

<!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ログイン</title>
    </head>

    <body>
            <form action="login_database.php" method="POST">
                アカウント名かメールアドレス
                <input type="text" name="accountOrEmail" id="accountOrEmail" value="<?php if(!empty($_POST['accountOrEmail'])){echo h($_POST['accountOrEmail']);} ?>"><br/>
                パスワード
                <input type="password" name="login_password" id="login_password" value="<?php if(!empty($_POST['login_password'])){echo h($_POST['login_password']);} ?>"><br/>

                <input type="submit" name="btn_login" value="ログイン"/> <br />
            </form>
            <a href="top.php">トップに戻る</a>
    </body>
</html>