<?php 

    session_start();

    include('validation.php');

    //クリックジャッキング対策
    header('X-FRAME-OPTIONS:DENY');

    //クロスサイトスクリプティング攻撃対策用関数の定義
    function h($str){
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    $pageFlag = 0;
    $errors = validation($_POST);


    //画面の切り替え
    //----------------------------------------------------------------------------------------------------
    if(!empty($_POST['btn_confirm']) && empty($errors)){
        $pageFlag = 1;
    }
    if(!empty($_POST['btn_register'])){
        $pageFlag = 2;
    }
//----------------------------------------------------------------------------------------------------
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
        <title>ユーザー登録</title>
    </head>

    <body>
        <!--確認画面-->
        <?php if($pageFlag === 1) : ?>
            <!--トークンが一致しているか判定する-->
            <?php if($_POST['csrf'] === $_SESSION['csrfToken']) :?>
                <div class="center">
                    <form action="database.php" method="POST">
                        <div class="signup-block">
                            名前：
                            <font color="aliceblue"><?php echo h($_POST['name']); ?></font>
                            <br>
                            アカウント名：
                            <font color="aliceblue"><?php echo h($_POST['account']); ?></font>
                            <br>
                            パスワード：
                            <font color="aliceblue"><?php echo h(str_repeat('●', strlen($_POST['password']))); ?></font>
                            <br>
                            メールアドレス：
                            <font color="aliceblue"><?php echo h($_POST['email']); ?></font>
                            <br>
                            説明：
                            <font color="aliceblue"><?php echo h($_POST['description']); ?></font>
                        </div>
                        <br>
                        <div style="margin:10px" class="col-12">
                            <button type="submit" class="btn btn-primary" name="btn_register" value="登録する">登録する</button>
                        </div>

                        <input type="hidden" name="name" value="<?php echo h($_POST['name']); ?>">
                        <input type="hidden" name="account" value="<?php echo h($_POST['account']); ?>">
                        <input type="hidden" name="password" value="<?php echo h($_POST['password']); ?>">
                        <input type="hidden" name="email" value="<?php echo h($_POST['email']); ?>">
                        <input type="hidden" name="description" value="<?php echo h($_POST['description']); ?>">
                        <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']);?>">

                    </form>

                    <form action="signup.php" method="POST">
                        <div div style="margin:10px" class="col-12">
                            <button type="submit" class="btn btn-primary" name="back" value="戻る">戻る</button>
                        </div>

                        <input type="hidden" name="name" value="<?php echo h($_POST['name']); ?>">
                        <input type="hidden" name="account" value="<?php echo h($_POST['account']); ?>">
                        <input type="hidden" name="password" value="<?php echo h($_POST['password']); ?>">
                        <input type="hidden" name="email" value="<?php echo h($_POST['email']); ?>">
                        <input type="hidden" name="description" value="<?php echo h($_POST['description']); ?>">

                    </form>
                        <a href="top.php">トップに戻る</a>
                </div><!--center-->
            <?php endif; ?><!--csrfToken-->
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

            <!--エラーメッセージの表示-->
            <div class="validation">    
                <?php if(!empty($errors) && !empty($_POST['btn_confirm'])) : ?>
                    <?php echo '<ul>' ;?>
                        <?php
                            foreach($errors as $error){
                                echo '<li>' . $error . '</li>';
                            }
                        ?>
                    <?php echo '</ul>' ;?>
                <?php endif ;?>
            </div>
            <div class="row">
                <div class="m-5">
                    <form class="row g-3" action="signup.php" method="POST">
                        <div class="signup-block">

                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="signup-name" class="form-label">名前</label>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="name" id="signup-name" placeholder="マリオ" value="<?php if(!empty($_POST['name'])){echo h($_POST['name']);} ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="signup-account" class="form-label">アカウント名</label>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="account" id="signup-account" placeholder="mario" value="<?php if(!empty($_POST['account'])){echo h($_POST['account']);} ?>">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="mb-2">
                                    <label for="signup-password" class="form-label">パスワード</label>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" name="password" id="signup-password" placeholder="4文字以上で入力してください" value="<?php if(!empty($_POST['password'])){echo h($_POST['password']);} ?>">
                                </div>
                            </div>

                            <div class="col-8">
                                <div class="mb-2">
                                    <label for="signup-email" class="form-label">メールアドレス</label>
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control" name="email" id="signup-name" placeholder="〇〇〇@〇〇〇.com" value="<?php if(!empty($_POST['email'])){echo h($_POST['email']);}?>">
                                </div>
                            </div>

                            <div class="col-8">
                                <div class="mb-2">
                                    <label for="signup-description" class="form-label">説明</label>
                                </div>
                                <div class="mb-3">
                                    <textarea name="description" class="form-control" cols="35" placeholder="ユーザー自身の紹介文を記載してください。" id="signup-description" value="<?php if(!empty($_POST['description'])){echo h($_POST['description']);}?>"></textarea>
                                </div>
                            </div>

                        </div><!--signup-block-->
                            
                            <input type="hidden" name="csrf" value="<?php echo $token; ?>">

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" name="btn_confirm" value="確認する">確認する</button>
                            </div>

                    </form>
                </div><!--m-5-->
            </div><!--row-->

            <div class="header">
                <a href="top.php">トップに戻る</a>
            </div>
            
        <?php endif; ?>
    </body>
</html>