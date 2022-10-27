<?php
echo (password_hash("12345", PASSWORD_BCRYPT));
$pathFile = file(__DIR__ . '/path.txt');
$path = explode('：',$pathFile[0]);
//パスワード（暗号化）
(password_hash($path[1], PASSWORD_BCRYPT));
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
        <link rel="stylesheet" href="../css/top.css">

        <title>ようこそ</title>
    </head>
    <body>
        <div class="btn_block">
            <div class="btn-border-gradient-wrap btn-border-gradient-wrap--gold">
                <a href="../top.php" class="btn btn-border-gradient"><span class="btn-text-gradient--gold">つぶやきの世界へ</span></a>
            </div>
        </div>
        <table>
            <tr>
                <td>
                    <div class="image_block">
                        <img src="../image/icon2.png">
                    </div>
                </td>
                <td>
                    <div class="your_message">
                        <div class="heading06">
                            <h2 class="heading06" data-en="言葉が世界をつなぎ、あなたが世界を変える"><font weight="bold"><span class="btn-text-gradient--gold">世界中へあなたの言葉を届けよう！！</span></font></h2>
                        </div>
                        <h4><span class="btn-text-gradient--color2">つぶやきで世界を変えろ。</span></h4>
                        <h4><span class="btn-text-gradient--color2">　　　　　　つぶやきで未来を作れ。</span></h4>
                        <br>
                        <br>
                        <h3><span class="btn-text-gradient--color">つぶやきの世界を体験しよう！</span></h3>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>