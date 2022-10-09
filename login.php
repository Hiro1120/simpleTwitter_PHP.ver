<?php
    
    //クリックジャッキング対策
    header('X-FRAME-OPTIONS:DENY');

    require_once('validation.php');

    //クロスサイトスクリプティング攻撃対策用の関数を定義
    function h($str){
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    $pageFlag = 0;
    $btn_login = 1;

    if(!empty($_POST['btn_login'])){
        $pageFlag = 3;
    }

?>

<?php if($pageFlag === 0 && $btn_login === 1) :?>

    <?php $btn_login = ""; ?>

    <!DOCTYPE html>
        <html lang="ja">
        <head>
            <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
            <link rel="stylesheet" href="css/style.css">
            <title>ログイン</title>
        </head>

        <body>
            <div class="row">
                <div class="m-5">
                    <form class="row g-3" action="login_database.php" method="POST">
                        <div class="login-block">

                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="login-accountOrEmail" class="form-label">アカウント名かメールアドレス</label>
                                </div>

                                <div class="mb-3">
                                    <input type="text" class="form-control" name="accountOrEmail" id="login-accountOrEmail" placeholder="必須" value="<?php if(!empty($_POST['accountOrEmail'])){echo h($_POST['accountOrEmail']);} ?>" required><br/>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="login-password" class="form-label">パスワード</label>
                                </div>

                                <div class="mb-3">
                                    <input type="password" class="form-control" name="login_password" id="login-password" placeholder="必須" value="<?php if(!empty($_POST['login_password'])){echo h($_POST['login_password']);} ?>" required><br/>
                                </div>
                            </div>

                        </div><!--login-block-->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" name="btn_login" value="ログイン">ログイン</button>
                            </div>
                    </form>
                </div><!--m-5-->
            </div> <!--row--> 

            <div class="header">
                <a href="top.php">トップに戻る</a>
            </div>
            
        </body>
    </html>
<?php endif ;?>