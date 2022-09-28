<?php
    header('X-FRAME-OPTIONS:DENY');
?>

<!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ログイン</title>
    </head>

    <body>
        <div class="main-content">
            <form action="login" method="post"><br />
                <label for="accountOrEmail">アカウント名かメールアドレス</label>
                <input name="accountOrEmail" id="accountOrEmail"/> <br />

                <label for="password">パスワード</label>
                <input name="password" type="password" id="password"/> <br />

                <input type="submit" value="ログイン" class="button1"/> <br />
                <a href="top.php">戻る</a>
            </form>
        </div>
    </body>
</html>