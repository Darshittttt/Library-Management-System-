<?php
session_start();
include('includes/config.php');

if(isset($_GET['msg'])){

    $msg = strtolower($_GET['msg']);
    $sid = $_SESSION['stdid']; // current student

    // ================= 📚 ISSUE BOOK =================
    if(strpos($msg, "issue") !== false){

        $bookname = str_replace("issue", "", $msg);
        $bookname = trim($bookname);

        if($bookname != ""){

            $sql = "SELECT id, BookName FROM tblbooks WHERE BookName LIKE :name LIMIT 1";
            $query = $dbh->prepare($sql);
            $query->bindValue(':name', "%$bookname%", PDO::PARAM_STR);
            $query->execute();
            $book = $query->fetch(PDO::FETCH_OBJ);

            if($book){

                $sql2 = "INSERT INTO tblissuedbookdetails (BookId, StudentID, IssuesDate)
                         VALUES (:bid, :sid, NOW())";

                $query2 = $dbh->prepare($sql2);
                $query2->bindParam(':bid', $book->id, PDO::PARAM_STR);
                $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
                $query2->execute();

                echo "✅ Book Issued: " . $book->BookName;

            } else {
                echo "❌ Book not found";
            }

        } else {
            echo "👉 Use like: issue java";
        }
    }

    // ================= 📚 RETURN BOOK =================
    elseif(strpos($msg, "return") !== false){

        $bookname = str_replace("return", "", $msg);
        $bookname = trim($bookname);

        if($bookname != ""){

            $sql = "SELECT id, BookName FROM tblbooks WHERE BookName LIKE :name LIMIT 1";
            $query = $dbh->prepare($sql);
            $query->bindValue(':name', "%$bookname%", PDO::PARAM_STR);
            $query->execute();
            $book = $query->fetch(PDO::FETCH_OBJ);

            if($book){

                $sql2 = "UPDATE tblissuedbookdetails 
                         SET RetrunStatus = 1 
                         WHERE BookId = :bid 
                         AND StudentID = :sid 
                         AND (RetrunStatus = 0 OR RetrunStatus IS NULL)
                         LIMIT 1";

                $query2 = $dbh->prepare($sql2);
                $query2->bindParam(':bid', $book->id, PDO::PARAM_STR);
                $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
                $query2->execute();

                if($query2->rowCount() > 0){
                    echo "✅ Book Returned: " . $book->BookName;
                } else {
                    echo "⚠️ No issued book found to return";
                }

            } else {
                echo "❌ Book not found";
            }

        } else {
            echo "👉 Use like: return java";
        }
    }

    // ================= 🔍 SEARCH BOOK =================
    elseif(strpos($msg, "book") !== false){

        $keyword = str_replace("book", "", $msg);
        $keyword = trim($keyword);

        if($keyword != ""){

            $sql = "SELECT BookName FROM tblbooks WHERE BookName LIKE :key LIMIT 3";
            $query = $dbh->prepare($sql);
            $query->bindValue(':key', "%$keyword%", PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if($results){
                echo "📚 Found books:<br>";
                foreach($results as $b){
                    echo "👉 ".$b->BookName."<br>";
                }
            } else {
                echo "❌ No book found";
            }

        } else {
            echo "👉 Try: book java";
        }
    }

    // ================= BASIC CHAT =================
    elseif(strpos($msg, "hello") !== false){
        echo "Hello 👋 How can I help you?";
    }

    elseif(strpos($msg, "thank") !== false){
        echo "You're welcome 😊";
    }

    else{
        echo "🤖 Try commands:<br>
              👉 issue java<br>
              👉 return java<br>
              👉 book python";
    }
}
?>