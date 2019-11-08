<?php

//! Model類
/*
 * 它的主要部分是對應於留言本各種資料操作的函式
 * 如:留言資料的顯示、插入、刪除等
 */
class Model {
    
    var $dao; //DataAccess類的一個例項(物件)//conn
    //! 建構函式
    /*
     * 構造一個新的Model物件
     * @param $dao是一個DataAccess物件
     * 該引數以地址傳遞(&;$dao)的形式傳給Model
     * 並儲存在Model的成員變數$this->dao中
     * Model通過呼叫$this->dao的fetch方法執行所需的SQL語句
     */
    function __construct(&$dao) {
        $this->dao=$dao;
    }
    function listNote($page, $per) {    //獲取全部留言
        $start = ($page - 1) * $per; //每一頁開始的資料序號
        $notes = $this->dao->fetchRows("SELECT * 
                                        FROM center88_board 
                                        ORDER BY time DESC 
                                        LIMIT ".$start.", ".$per);
        //執行dataAccess裡的function
        return $notes;
    }
    function searchNote($page, $per, $search) {    //獲取全部留言
        $start = ($page - 1) * $per; //每一頁開始的資料序號
        $notes = $this->dao->fetchRows("SELECT *
                                        FROM center88_board
                                        WHERE msg_title LIKE '%" . $search . "%'
                                        ORDER BY time DESC
                                        LIMIT ".$start.", ".$per);
        //執行dataAccess裡的function
        return $notes;
    }
    function pageArray($page, $dowhat, $search = NULL) {
        
        $page_array["page"] = $page;
        $page_array["per"] = $per = 3;//每頁顯示筆數
        switch($dowhat){
            case "list":
                $page_array["data_rows"] = $this->dao->rowsNum("SELECT *
                                                                FROM center88_board
                                                                ORDER BY time DESC");
                break;
            case "search":
                $page_array["data_rows"] = $this->dao->rowsNum("SELECT *
                                                                FROM center88_board
                                                                WHERE msg_title LIKE '%" . $search . "%'
                                                                ORDER BY time DESC");
                break;
            case "listMyMsg":
                $page_array["data_rows"] = $this->dao->rowsNum("SELECT *
                                                                FROM center88_board
                                                                WHERE mb_id ='" . $_SESSION["login_id"] . "'
                                                                ORDER BY time DESC");
                break;
        }
        $page_array["allpages"] = ceil($page_array["data_rows"]/$per);
        return $page_array;
    }
    function postNote() {
        $msg_array["id"] = "";
        $msg_array["nickname"] = "";
        $msg_array["msg_title"] = "";
        $msg_array["msg"] = "";
        $msg_array["errname"] = "";
        $msg_array["errtitle"] = "";
        $msg_array["errmsg"] = "";
        if (isset($_POST["nickname"]) && isset($_POST["msg_title"]) && isset($_POST["msg"]))
        {
            if (!empty($_POST["nickname"]) && !empty($_POST["msg_title"]) && !empty($_POST["msg"])){//不允許add送空字串
                $msg_array["nickname"] = $_POST["nickname"];
                $msg_array["msg_title"] = $_POST["msg_title"];
                $msg_array["msg"] = $_POST["msg"];
                $sql = "INSERT INTO center88_board (nickname, msg_title, msg)
                        VALUES ('" . $msg_array["nickname"] . "' , '" . $msg_array["msg_title"] . "' , '" . $msg_array["msg"] . "' )";
                
                if ( $this->dao->query($sql) ){
                    echo "新增成功!!";
                    header("Refresh: 3; URL=index.php");
                }else{
                    echo "新增失敗!!";
                }
            }else{
                $msg_array["nickname"] = empty($_POST["nickname"]) ? "" : $_POST["nickname"];
                $msg_array["msg_title"] = empty($_POST["msg_title"]) ? "" : $_POST["msg_title"];
                $msg_array["msg"] = empty($_POST["msg"]) ? "" : $_POST["msg"];
                if(empty($_POST["nickname"])){
                    $msg_array["errname"] = "請輸入暱稱";
                }
                if(empty($_POST["msg_title"])){
                    $msg_array["errtitle"] = "請輸入標題";
                }
                if(empty($_POST["msg"])){
                    $msg_array["errmsg"] = "請輸入留言";
                }
            }
       }
       return $msg_array;
    }
    function modifyNote() {
        $msg_array["id"] = "";
        $msg_array["nickname"] = "";
        $msg_array["msg_title"] = "";
        $msg_array["msg"] = "";
        $msg_array["errname"] = "";
        $msg_array["errtitle"] = "";
        $msg_array["errmsg"] = "";
        if(isset($_GET['id'])){ //未修改前，輸出原本內容
            $msg_array["id"] = $_GET['id'];
            $sql = "SELECT *
                    FROM center88_board
                    WHERE msg_id = " . $msg_array["id"]. "
                    ORDER BY time DESC";
            $notes = $this->dao->fetchRows($sql);
            foreach ($notes as $value)
            {
                $msg_array["nickname"] = isset($value["nickname"])?$value["nickname"]:"";
                $msg_array["msg_title"] = isset($value["msg_title"])?$value["msg_title"]:"";
                $msg_array["msg"] = isset($value["msg"])?$value["msg"]:"";
            }
        }
        if(isset($_POST["msg_id"])){
            $time = date("Y-m-d H:i:s",time()+8*60*60); //GMT+8
            $id = $_POST["msg_id"];
            $msg = $_POST["msg"];
            $sql = "UPDATE center88_board
                    SET msg='" . $msg . "', time= '" . $time . "'
                    WHERE msg_id= '" . $id . "'";
            if ( $this->dao->query($sql) ){
                echo "修改成功!! 將於3秒後跳回首頁";
                header("Refresh: 2; URL=index.php");
            }else{
                echo "修改失敗!!";
            }
            $msg_array["nickname"] = $_POST["nickname"];
            $msg_array["msg_title"] = $_POST["msg_title"];
            $msg_array["msg"] = $_POST["msg"];
        }
        return $msg_array;
    }
    function deleteNote() {
        if(isset($_GET["id"])){ //未修改前，輸出原本內容
            $sql = "SELECT *
                    FROM center88_board
                    WHERE msg_id = " . $_GET['id'] . "
                    ORDER BY time DESC";
            $notes = $this->dao->fetchRows($sql);
            foreach ($notes as $value)
            {
                return $value;
            }
        }
        if(isset($_POST["msg_id"])){ //未修改前，輸出原本內容
            $sql = "DELETE FROM center88_board 
                    WHERE msg_id = '" . $_POST["msg_id"] . "'" ;
            if ( $this->dao->query($sql) ){
                echo "刪除成功!! 將於3秒後跳回首頁";
                header("Refresh: 2; URL=index.php");
            }else{
                echo "刪除失敗!!";
            }
        }
    }
    function loginNote() {
        $member_array["success"] = 0;
        $member_array["mb_id"] = "";
        $member_array["mb_pwd"] = "";
        $member_array["errid"] = "";
        $member_array["errpwd"] = "";
        if(isset($_POST["mb_id"])){ //未修改前，輸出原本內容
            if (!empty($_POST["mb_id"]) && !empty($_POST["mb_pwd"]) ){
                $sql = "SELECT *
                        FROM center88_member
                        WHERE mb_id='" . $_POST["mb_id"] . "'";
                if($this->dao->rowsNum($sql) == 0){
                    $member_array["mb_id"] = $_POST["mb_id"];
                    $member_array["errid"] = "無此帳號!";
                    return $member_array;
                }
                $sql = "SELECT *
                        FROM center88_member
                        WHERE mb_id='" . $_POST["mb_id"] . "' and mb_pwd='" . $_POST["mb_pwd"] . "'";
                if($this->dao->rowsNum($sql) == 0){
                    $member_array["mb_id"] = $_POST["mb_id"];
                    $member_array["errpwd"] = "密碼錯誤!";
                    return $member_array;
                }else{
                    $member_array["success"] = 1;
                    $_SESSION["login_id"] = $_POST["mb_id"];
                }
            }
            else{
                $member_array["mb_id"] = empty($_POST["mb_id"]) ? "" : $_POST["mb_id"];
                if(empty($_POST["mb_id"])){
                    $member_array["errid"] = "請輸入帳號";
                }
                if(empty($_POST["mb_pwd"])){
                    $member_array["errpwd"] = "請輸入密碼";
                }
            }
        }
        return $member_array;
    }
    function signupNote() {
        $signup_array["success"] = 0;
        $signup_array["set_id"] = "";
        $signup_array["set_pwd"] = "";
        $signup_array["check_pwd"] = "";
        $signup_array["set_email"] = "";
        $signup_array["errid"] = "";
        $signup_array["errpwd"] = "";
        $signup_array["errckpwd"] = "";
        $signup_array["erremail"] = "";
        if (isset($_POST["set_id"]))
        {
            if (!empty($_POST["set_id"]) && !empty($_POST["set_pwd"]) && !empty($_POST["check_pwd"]) && !empty($_POST["set_email"])){//不允許signup送空字串
                if($_POST["set_pwd"] != $_POST["check_pwd"]){
                    $signup_array["set_id"] = $_POST["set_id"];
                    $signup_array["set_email"] = $_POST["set_email"];
                    $signup_array["errckpwd"] = "密碼不相符";
                    return $signup_array;
                }
                $sql = "SELECT * 
                        FROM center88_member 
                        WHERE mb_id='" . $_POST["set_id"] . "'";
                if($this->dao->rowsNum($sql) > 0){
                    $signup_array["set_id"] = $_POST["set_id"];
                    $signup_array["set_email"] = $_POST["set_email"];
                    $signup_array["errid"] = "此帳號已被使用!";
                    return $signup_array;
                }
                $sql = "INSERT INTO center88_member
                        VALUES ('" . $_POST["set_id"] . "','" . $_POST["set_pwd"] . "','" . $_POST["set_email"] . "')";
                if ( $this->dao->query($sql) ){
                    $signup_array["success"] = 1;
                }
            }else{
                $signup_array["set_id"] = empty($_POST["set_id"]) ? "" : $_POST["set_id"];
                $signup_array["set_email"] = empty($_POST["set_email"]) ? "" : $_POST["set_email"];
                
                if(empty($_POST["set_id"])){
                    $signup_array["errid"] = "請輸入帳號";
                }
                if(empty($_POST["set_pwd"])){
                    $signup_array["errpwd"] = "請輸入密碼";
                }
                if(empty($_POST["set_email"])){
                    $signup_array["erremail"] = "請輸入email";
                }
            }
        }
        return $signup_array;
    }
    function modifyMyDataNote() {
        $md_array["email"] = "";
        if(isset($_POST["new_email"])){
            $sql = "UPDATE center88_member
                    SET mb_email='" . $_POST["new_email"] . "'
                    WHERE mb_id= '" . $_SESSION["login_id"] . "'";
            if ( $this->dao->query($sql) ){
                $md_array["email"] = $_POST["new_email"];
                echo "修改成功!! 將於3秒後跳回首頁";
                header("Refresh: 2; URL=index.php");
            }else{
                echo "修改失敗!!";
            }
        }else{
            $sql = "SELECT mb_email
                    FROM center88_member
                    WHERE mb_id = '" . $_SESSION["login_id"] . "'";
            $notes = $this->dao->fetchRows($sql);
            foreach ($notes as $value)
            {
                $md_array["email"] = isset($value["mb_email"]) ? $value["mb_email"] : "";
            }
        }
        return $md_array;
    }
    function modifyMyPwdNote() {
        $mdpwd_array["erroldpwd"] = "";
        $mdpwd_array["errpwd"] = "";
        $mdpwd_array["errckpwd"] = "";
        if (isset($_POST["new_mb_pwd"]))
        {
            if (!empty($_POST["old_mb_pwd"]) && !empty($_POST["new_mb_pwd"]) && !empty($_POST["new_check_pwd"])){//不允許signup送空字串
                $sql = "SELECT mb_pwd
                        FROM center88_member
                        WHERE mb_id='" . $_SESSION["login_id"] . "'";
                $notes = $this->dao->fetchRows($sql);
                foreach ($notes as $value)
                {
                    $pwd = isset($value["mb_pwd"]) ? $value["mb_pwd"] : "";
                }
                if($_POST["old_mb_pwd"] != $pwd){
                    $mdpwd_array["erroldpwd"] = "密碼錯誤";
                    return $mdpwd_array;
                }
                if($_POST["new_mb_pwd"] != $_POST["new_check_pwd"]){
                    $mdpwd_array["errckpwd"] = "密碼不相符";
                    return $mdpwd_array;
                }
                $sql = "UPDATE center88_member
                        SET mb_pwd='" . $_POST["new_mb_pwd"] . "'
                        WHERE mb_id= '" . $_SESSION["login_id"] . "'";
                if ( $this->dao->query($sql) ){
                    echo "修改成功!! 將於3秒後跳回首頁";
                    header("Refresh: 2; URL=index.php");
                }else{
                    echo "修改失敗!!";
                }
            }
        }
        return $mdpwd_array;
    }
    function listMyMsgNote($page, $per) {
        $start = ($page - 1) * $per; //每一頁開始的資料序號
        $notes = $this->dao->fetchRows("SELECT *
                                        FROM center88_board
                                        WHERE mb_id ='" . $_SESSION["login_id"] . "'
                                        ORDER BY time DESC
                                        LIMIT ".$start.", ".$per);
        //執行dataAccess裡的function
        return $notes;
    }
}
?>