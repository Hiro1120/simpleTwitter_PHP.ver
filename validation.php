<?php

    function validation($request){

        $errors = [];

        if(empty($request['name']) || 20 < mb_strlen($request['name'])){
            $errors[] = '「名前」は必須です。20文字以内で入力してください。';
        }

        if(empty($request['account']) || 20 < strlen($request['account'])){
            $errors[] = '「アカウント名」は必須です。20文字以内で入力してください。';
        }

        if(empty($request['password'])){

            $errors[] = '「パスワード」は必須です。';

        }else{
            
            if(0 < strlen($request['password']) && 4 > strlen($request['password'])){
                $errors[] = '「パスワード」は4文字以上で入力してください。';
            }

            if(20 < strlen($request['password'])){
                $errors[] = '「パスワード」は20文字以内で入力してください。';
            }
        }

        if(empty($request['email']) || !filter_var($request['email'], FILTER_VALIDATE_EMAIL)){
            $errors[] = '「メールアドレス」は必須です。正しい形式で入力してください。';
        }

        return $errors;
    }

?>