<?php
	//$dsnの式の中にスペースを入れないこと！



	//5-1やるで

$name_value = "なまえ";
$comment_value = "コメント";
$hidden_num = 0;
$date = date("Y/m/d H:i:s");

	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//データベース内にテーブルを作成する。テーブル作成の際にはcreateコマンドを使う。
	$sql = "CREATE TABLE IF NOT EXISTS bulletin_board3"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass_word TEXT"
	.");";
	$stmt = $pdo->query($sql);

//名前とコメントをテキストファイルに書き込み
if (empty($_POST["name"]) or empty($_POST["comment"]) or empty($_POST["pass"])) {
//	echo "名前・コメント・パスワードをすべて入力してください．<br>";
} elseif (!empty($_POST["name"]) and !empty($_POST["comment"]) and empty($_POST["edit_num"]) and !empty($_POST["pass"])) {
	//入力
	$sql = $pdo -> prepare("INSERT INTO bulletin_board3 (name, comment, pass_word) VALUES (:name, :comment, :pass_word)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':pass_word', $pass_word, PDO::PARAM_STR);
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$pass_word = $_POST["pass"];
	$sql -> execute();
} else {
	$id = $_POST["edit_num"]; //変更する投稿番号
	$name = $_POST["name"];
	$comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
	$pass_word = $_POST["pass_word"];
	$sql = 'update bulletin_board3 set name=:name,comment=:comment,pass_word=:pass_word where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':pass_word', $pass_word, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
}

//削除
//入力された削除番号の行を空行にする
if (!empty($_POST["delete"]) and !empty($_POST["pass_del"])) {
	$sql = 'SELECT * FROM bulletin_board3';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		if ($row['id'] == $_POST["delete"]) {
			if ($row['pass_word'] == $_POST["pass_del"]) {
				$id = $_POST["delete"];
				$sql = 'delete from bulletin_board3 where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			} else {
				echo "パスワードが違います．<br>";
			}
		}
	}
}

//編集
if (!empty($_POST["edit"]) and !empty($_POST["pass_edi"])) {
	$sql = 'SELECT * FROM bulletin_board3';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		if ($row['id'] == $_POST["edit"]) {
			if ($row['pass_word'] == $_POST["pass_edi"]) {
				$name_value = $row['name'];
				$comment_value = $row['comment'];
				$hidden_num = $row['id'];
			} else {
				echo "パスワードが違います．<br>";
			}
		}
	}
}

?>



<html>
<body>

冷蔵庫の奥から賞味期限のわからないイチゴ大福が出てきた．食べる？<br>
お名前と，コメント欄に「食べる」か「食べない」をご記入ください．

<!-- 投稿フォーム -->
<form method="post" action="mission_5-1.php">
名前：<input type="text" name="name" value=<?php echo $name_value;?>><br>
コメント：<input type="text" name="comment" value=<?php echo $comment_value;?>>
<input type="hidden" name="edit_num" value=<?php echo $hidden_num;?>><br>
パスワード：<input type="text" name="pass" value=1><br>
<input type="submit" value="送信する">
</form>

<!-- 削除フォーム -->
<form method="post" action="mission_5-1.php">
削除番号：<input type="text" name="delete" value="0"><br>
パスワード：<input type="text" name="pass_del"><br>
<input type="submit" value="削除する">
</form>

<!-- 編集番号指定フォーム -->
<form method="post" action="mission_5-1.php">
編集番号：<input type="text" name="edit" value="0"><br>
パスワード：<input type="text" name="pass_edi"><br>
<input type="submit" value="編集する">
</form>

</body>
</html>



<?php

//出力
$sql = 'SELECT * FROM bulletin_board3';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$row["pass_word"]を消せばパスワードは表示されなくなる
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row["pass_word"].",";
		echo $date.'<br>';
	echo "<hr>";
	}
?>