<?php

//! Controller
/*
 * 控制器將$_GET['action']中不同的引數(list、post、delete)
 * 對應於完成該功能控制的相應子類
 */
class Controller {
    var $model;  // Model 物件
    var $view;   // View  物件
    //! 建構函式
    /*
     * 構造一個Model物件儲存於成員變數$this->model;
     */
    /*
     * & 表示引用(類似c的指標),傳送的是參數位址
     * 對此變數修改的話會改到原本的變數?
     * 對於不會修改的大型陣列使用這種方法比較省記憶體空間
     */
    function __construct (&$dao) { 
        $this->model = new Model($dao);
        $this->view = new View();
        $this->view->sign();
        $this->view->banner();
    }
    
}
//用於控制顯示留言列表的子類
class listController extends Controller{   //extends表示繼承
    function __construct (&$dao, $page) { //建立model
        parent::__construct($dao);  //繼承其父類的建構函式然後執行(執行1號)
        //該行的含義可以簡單理解為:
        //將其父類的建構函式程式碼複製過來
        $this->view->sidebar();
        
        $page_array = $this->model->pageArray($page, "list");
        $notes = $this->model->listNote($page, $page_array["per"]);
        //執行model裡的listNote function
        //取得全部留言的result
        
        $this->view = new listView($notes);
        $this->view->viewMsgResult($notes);
        $this->view->viewPage("list", $page_array);
        //建立相應的View子類的物件來完成顯示
    }
}
class searchController extends Controller{   //extends表示繼承
    function __construct (&$dao, $page, $search) { //建立model
        parent::__construct($dao);  //繼承其父類的建構函式
        //該行的含義可以簡單理解為:
        //將其父類的建構函式程式碼複製過來
        $this->view->sidebar();
        if(empty($search)){
            $this->view = new searchView();
        }
        else{
            $page_array = $this->model->pageArray($page, "search", $search);
            $notes = $this->model->searchNote($page, $page_array["per"], $search);
            $this->view = new searchView($search);
            $this->view->viewMsgResult($notes);
            $this->view->viewPage("search", $page_array, "&input=".$search);
        }
        /*$page_array = $this->pageArray($page);
        $notes = $this->model->listNote($page, $per);
        //執行model裡的listNote function
        //取得全部留言的result
        
        $this->view = new listView($notes, $page_array);*/
        //建立相應的View子類的物件來完成顯示
    }
}
//用於控制新增留言的子類
class postController extends Controller{
    function __construct ($dao) {//連資料庫
        parent::__construct($dao);//建立model
        $this->view->sidebar();
        if(!isset($_SESSION["login_id"])){
            $this->view->pleaseLogin();
        }
        else{
            $this->view = new postView();
            $msg_array = $this->model->postNote();//先收看看有沒有資料近來
            $this->view->post($msg_array, "post");
        }
    }
}
//用於控制修改留言的子類
class modifyController extends Controller{
    function __construct (&$dao) {//連資料庫
        parent::__construct($dao);//建立model
        $this->view->sidebar();
        if(!isset($_SESSION["login_id"])){
            $this->view->pleaseLogin();
        }
        else{
            $this->view = new modifyView();
            $msg_array = $this->model->modifyNote();//先收看看有沒有資料近來
            $this->view->post($msg_array, "modify");
        }
    }
}

class deleteController extends Controller{
    function __construct (&$dao) {
        parent::__construct($dao);
        $this->view->sidebar();
        if(!isset($_SESSION["login_id"])){
            $this->view->pleaseLogin();
        }
        else{
            $this->view = new deleteView();
            $value = $this->model->deleteNote();
            $this->view->delete($value);
        }
    }
}
class loginController extends Controller{
    function __construct (&$dao) {
        parent::__construct($dao);
        $this->view = new loginView();
        $member_array = $this->model->loginNote();
        $this->view->login($member_array);
    }
}
class signupController extends Controller{
    function __construct (&$dao) {
        parent::__construct($dao);
        $this->view = new signupView();
        $signup_array = $this->model->signupNote();
        $this->view->signup($signup_array);
    }
}
class modifyMyDataController extends Controller{
    function __construct (&$dao) {
        parent::__construct($dao);
        $this->view->sidebar();
        if(!isset($_SESSION["login_id"])){
            $this->view->pleaseLogin();
        }
        else{
            $this->view = new modifyMyDataView();
            $md_array = $this->model->modifyMyDataNote();
            $this->view->modifyMyData($md_array);
        }
    }
}
class modifyMyPwdController extends Controller{
    function __construct (&$dao) {
        parent::__construct($dao);
        $this->view->sidebar();
        if(!isset($_SESSION["login_id"])){
            $this->view->pleaseLogin();
        }
        else{
            $this->view = new modifyMyPwdView();
            $mdpwd_array = $this->model->modifyMyPwdNote();
            $this->view->modifyMyPwd($mdpwd_array);
        }
    }
}
class listMyMsgController extends Controller{
    function __construct (&$dao, $page) {
        parent::__construct($dao);
        $this->view->sidebar();
        if(!isset($_SESSION["login_id"])){
            $this->view->pleaseLogin();
        }
        else{
            $page_array = $this->model->pageArray($page, "listMyMsg");
            $notes = $this->model->listMyMsgNote($page, $page_array["per"]);
            $this->view = new listMyMsgView();
            $this->view->viewMsgResult($notes);
            $this->view->viewPage("listMyMsg", $page_array);
        }
    }
}

?>