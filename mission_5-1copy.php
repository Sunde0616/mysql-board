<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
        $name = $_POST["name"];
        $str = $_POST["str"];
        $date = date("Y/m/d H:i:s");
        $del = $_POST["del"];
        $edit = $_POST["edit"];
        $edit_n = $_POST["edit_n"];
        $pass1 = $_POST["pass1"];
        $pass2 = $_POST["pass2"];
        $pass3 = $_POST["pass3"];
        
        $dsn = 'データベース名';
	    $user = 'ユーザー名';
	    $password = 'パスワード';
	    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    
	    $sql = "CREATE TABLE IF NOT EXISTS tbsunde"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "name char(32),"
	    . "str TEXT,"
	    . "pass char(8),"
	    . "date char(32)"
	    .");";
	    $stmt = $pdo->query($sql);
        
        if((empty($str)) or (empty($name)) or (empty($pass1))){
            echo "必要事項を入力してください";
        }
        else{//入力欄が埋まっているとき
            if(empty($edit_n)){
                echo "名前とコメントを受け付けました";
                $sql = $pdo -> prepare("INSERT INTO tbsunde (name, str, pass, date) VALUES (:name, :str, :pass, :date)");
	            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	            $sql -> bindParam(':str', $str, PDO::PARAM_STR);
	            $sql -> bindParam(':pass', $pass1, PDO::PARAM_INT);
	            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	            $sql -> execute();
            }
            else{
                $sql = 'SELECT * FROM tbsunde';
	            $stmt = $pdo->query($sql);
	            $results = $stmt->fetchAll();
	            foreach ($results as $row){
                    if(($edit_n == $row['id']) and ($pass1 == $row['pass'])){
                        $id = $edit_n; //変更する投稿番号
	                    $sql = 'UPDATE tbsunde SET name=:name,str=:str WHERE id=:id';
	                    $stmt = $pdo->prepare($sql);
	                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	                    $stmt->bindParam(':str', $str, PDO::PARAM_STR);
	                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                    $stmt->execute();   
                    }
                }
            }
        }
        
        if((!empty($edit)) and (!empty($pass3))){
            $sql = 'SELECT * FROM tbsunde';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        foreach ($results as $row){
	            if(($edit == $row['id']) and ($pass3 == $row['pass'])){
	                $newname = $row['name'];
	                $newstr = $row['str'];
	                $newpass = $row['pass'];
	            }
	        }
        }
        
        if((!empty($del)) and (!empty($pass2))){
            $sql = 'SELECT * FROM tbsunde';
	        $stmt = $pdo->query($sql);
    	    $results = $stmt->fetchAll();
            foreach ($results as $row){
                if(($del == $row['id']) and ($pass2 == $row['pass'])){
                    $id = $del;
                    $sql = 'delete from tbsunde where id=:id';
	                $stmt = $pdo->prepare($sql);
	                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                $stmt->execute();
                }
            }
        }
    ?>
    <form action="" method="post">
        名前とコメントを入力してください
        <input type="text" name="name" value="<?php echo $newname ; ?>">
        <input type="text" name="str" value="<?php echo $newstr ; ?>">
        <input type="text" name="pass1" placeholder="パスワード" value="<?php echo $newpass ; ?>">
        <input type="submit" name="submit">
        <br>
        削除したい行の番号を入力して下さい
        <input type="text" name="del">
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="submit">
        <br>
        編集したい行の番号を入力してください
        <input type="text" name="edit">
        <input type="text" name="pass3" placeholder="パスワード">
        <input type="submit" name="submit">
        <input type="hidden" name="edit_n" value="<?php echo $edit ; ?>">
    </form>
    <?php
    //表示
    $sql = 'SELECT * FROM tbsunde';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
		    echo $row['id'].',';
		    echo $row['name'].',';
		    echo $row['str'].',';
		    echo $row['date'].'<br>';
	    echo "<hr>";
	    }
    ?>
</body>
</html>