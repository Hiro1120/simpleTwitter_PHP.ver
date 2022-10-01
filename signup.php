<?php 

    session_start();

    require_once('validation.php');
    //クリックジャッキング対策
    header('X-FRAME-OPTIONS:DENY');

    // if(!empty($_POST)){
    //     echo '<pre>';
    //     var_dump ($_POST);
    //     echo '<pre>';
    // }

    //クロスサイトスクリプティング攻撃対策用の関数を定義
    function h($str){
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    //入力「0」、確認「1」、登録「2」
    $pageFlag = 0;
    $errors = validation($_POST);

    //if文でページを切り替える処理
    //入力画面で値が入力されていたら確認画面に切り替える
    if(!empty($_POST['btn_confirm']) && empty($errors)){
        $pageFlag = 1;
    }
    if(!empty($_POST['btn_register'])){
        $pageFlag = 2;
    }

?>

<!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ユーザー登録</title>
    </head>

    <body>

    <!--確認画面-->
    <?php if($pageFlag === 1) : ?>
    <!--トークンが一致しているか判定する-->
    <?php if($_POST['csrf'] === $_SESSION['csrfToken']) :?>
            <form action="database.php" method="POST">
                名前
                <?php echo h($_POST['name']); ?>
                <br>
                アカウント名
                <?php echo h($_POST['account']); ?>
                <br>
                パスワード
                <?php echo h($_POST['password']); ?>
                <br>
                メールアドレス
                <?php echo h($_POST['email']); ?>
                <br>
                説明
                <?php echo h($_POST['description']); ?>
                <br>
                <input type="submit" name="btn_register" value="登録する">
                <input type="hidden" name="name" value="<?php echo h($_POST['name']); ?>">
                <input type="hidden" name="account" value="<?php echo h($_POST['account']); ?>">
                <input type="hidden" name="password" value="<?php echo h($_POST['password']); ?>">
                <input type="hidden" name="email" value="<?php echo h($_POST['email']); ?>">
                <input type="hidden" name="description" value="<?php echo h($_POST['description']); ?>">
                <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']);?>">
            </form>
            <form action="signup.php" method="POST">
                <input type="submit" name="back" value="戻る">
                <input type="hidden" name="name" value="<?php echo h($_POST['name']); ?>">
                <input type="hidden" name="account" value="<?php echo h($_POST['account']); ?>">
                <input type="hidden" name="password" value="<?php echo h($_POST['password']); ?>">
                <input type="hidden" name="email" value="<?php echo h($_POST['email']); ?>">
                <input type="hidden" name="description" value="<?php echo h($_POST['description']); ?>">
            </form>
            <a href="top.php">トップに戻る</a>
        <?php endif; ?>
        <?php endif; ?>
        
        <!--入力画面-->
        <?php if($pageFlag === 0) : ?>
        <!--csrfTokenがなければ変数に代入する-->
        <?php
            if(!isset($_SESSION['csrfToken'])){
                $csrfToken = bin2hex(random_bytes(32));
                $_SESSION['csrfToken'] = $csrfToken;
            }
            $token = $_SESSION['csrfToken'];
        ?>

        <?php if(!empty($errors) && !empty($_POST['btn_confirm'])) : ?>
            <?php echo '<ul>' ;?>
                <?php
                    foreach($errors as $error){
                        echo '<li>' . $error . '</li>';
                    }
                ?>
            <?php echo '</ul>' ;?>
        <?php endif ;?>


            <form action="signup.php" method="POST">
                名前
                <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])){echo h($_POST['name']);} ?>">
                <br>
                アカウント名
                <input type="text" name="account" id="account" value="<?php if(!empty($_POST['account'])){echo h($_POST['account']);} ?>">
                <br>
                パスワード
                <input type="password" name="password" id="password" value="<?php if(!empty($_POST['password'])){echo h($_POST['password']);} ?>">
                <br>
                メールアドレス
                <input type="email" name="email" value="<?php if(!empty($_POST['email'])){echo h($_POST['email']);}?>">
                <br>
                説明
                <textarea name="description" cols="35" rows="5" id="description" value="<?php if(!empty($_POST['description'])){echo h($_POST['description']);}?>"></textarea>
                <br>
                <input type="submit" name="btn_confirm" value="確認する">
                <input type="hidden" name="csrf" value="<?php echo $token; ?>">
            </form>
            <a href="top.php">トップに戻る</a>
        <?php endif; ?>
    </body>
</html>