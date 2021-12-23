<?php
//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
//編集ボタンが押された時の処理
    // ファイルからデータ読み取り
   $textfile = "mission_3-5.txt";
    // オプションのパラメータの意味は
    // https://www.php.net/manual/ja/function.file.php
    $line = file($textfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // 編集用データ格納変数
    //空欄にしとく
    $editNumber = '';
    $editName = '';
    $editComment = '';
    $editPassword = '';

    // 送信内容によって処理が分かれる
    if(isset($_POST["hensyu"])) {
        // ここは編集番号よりデータを求める所

        // データ件数分処理
        foreach($line as $lines) 
        {
            // <>で分割して配列に
            $hensyubango= explode("<>", $lines);
            // 編集対象番号のときはデータをセットする
            if($hensyubango[0] == $_POST["henban"]&&$hensyubango[4] == $_POST["hensyupass"]) 
            {
                $editNumber = $hensyubango[0];
                $editName = $hensyubango[1];
                $editComment = $hensyubango[2];
                $editPassword= $hensyubango[4];
                // 即抜ける
                break;
            }
        }
    }
    else if(isset($_POST["submit"])) 
    {
        // 書き込みか上書きかをするところ
        $nitiji=date("Y年m月d日H時i分s秒");
       // 書き込むデータを作る
        $writeData = ($_POST['edit_post'] ?: count($line) + 1) . "<>" . $_POST['name'] . "<>" . $_POST['comment']. "<>" .$nitiji. "<>" . $_POST['Password'];

        // 編集番号があればデータループして場所を特定して上書きする
        if($_POST["edit_post"]) 
        {
            // データ件数分処理(&で参照にしてる)
            foreach($line as &$lines)
            {
                // <>で分割して配列に
                $hensyubango = explode("<>", $lines);
                // 編集番号のところだったら上書き
                if($hensyubango[0] == $_POST["edit_post"])
                {
                    $lines = $writeData;
                }
            }
        }
        else 
        {
            // 新規投稿なので最後に追加
            $line[] = $writeData;
        }

        // ファイルに書き込む(implodeで配列を改行付き文字列へ)
        file_put_contents($textfile, implode("\n", $line));

               
           
    }
    
//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
 else if( isset($_POST["sakujyo"]))
{ 
    if(!empty($sakujyobanngou= $_POST["sakujyobanngou"])&&!empty($hensyupass= $_POST["sakujyopass"]))
       {foreach($line as $lines) 
        {$sakuban=explode("<>", $lines);
	    if($sakuban[4]==$hensyupass&&$sakuban[0]==$sakujyobanngou){
		echo "押されたのは削除です。<br>";
        //ファイルの内容を配列に格納する
        //ここに$textfile="mission_3-3.txt";書いたらエラーなくなったけどよくわかんないのだが？
       $textfile="mission_3-5.txt";
        $file= file($textfile);
        //指定した要素を削除する
        unset($file[$sakujyobanngou-1]);
        //ファイルの書き込み
        //これの意味があんまりわかんない！消して書き込む？？？？？？ナニヲ？？？？？？
        file_put_contents($textfile, $file);
		}
        }     
        }
    
}

?>
<!DOCTYPE html>
<html>
 <head>
 <meta charset="UTF-8">    
 </head>   
 <body>
    <form action="" method="post">
    <!--  編集ナンバーは隠すよ〜ってこと-->
     <input type="hidden" name="edit_post" value="<?php echo $editNumber; ?>">
    <!--名前フォーム-->
     <div>
     　　　　　<label for="name">名前</label><br>
            <input type="text"  name="name" value="<?php echo $editName; ?>">
    </div>
    <!--コメントフォーム-->
    <div>
            <label for="comment">コメント</label>
       <br>  <input type="text" name="comment"value="<?php echo $editComment; ?>" >
    </div>
    <!--パスワードフォーム-->
    <div>
            <label for="comment">パスワード</label>
       <br>  <input type="text" name="Password" value="<?php echo $editPassword; ?>" >
    </div>
    <!--送信ボタン-->
   <br>
  <button type="submit" name="submit">送信</button>
   </form>
　<form action="" method="post">
　 
　   <div>
    <!--削除内容-->
            <label for="sakujyobanngou">削除番号</label><br>
            <input type="text"  name="sakujyobanngou">
    </div>
    <div>
    <!--削除パスワード-->
            <label for="sakujyopass">削除番号パスワード</label><br>
            <input type="text"  name="sakujyopass">
     </div>
　   <!--削除ボタン-->
　    <button type="submit" name="sakujyo">削除</button>
　 </form>
　 
   　<form action="" method="post">
   　<!--編集番号記入-->
    <div>
            <label for="henban"></label>編集番号記入<br>
            <input type="text"  name="henban">
   　</div>
    <!--編集番号パスワード-->
    <div>
            <label for="hensyupass">編集番号パスワード</label><br>
            <input type="text"  name="hensyupass">
    </div>
     
     <!--編集ボタン-->
　   <button type="submit" name="hensyu">編集</button>
    </form>
<?php   
//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
// ここにブラウザに表示の処理を書く
$textfile="mission_3-5.txt";
$huku=file($textfile,FILE_IGNORE_NEW_LINES);
foreach($huku as$hukus)
{
    //exploadで配列を一個一個区切ってくれる！！
    $value=explode("<>",$hukus);
     //$value[0]これで配列の中身を表示してあげている
    echo $value[0]."".$value[1]."".$value[2]."".$value[3]."".$value[4]."<br>";
}
?>
</body>
</html>