<!DOCTYPE html><!-- html 5 文件類型聲明  -->
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
        <title>center88留言板</title>
        <link rel = stylesheet type = "text/css" href = "css/board.css">
    </head>

    <body>
        <div class='container'>
            <div class='sign'>
            </div>
            <div class='banner'>
                <p><a href="index.php?action=list">center88留言板</a></p>
            </div>
            <div class='sidebar'>
                <table class='bar_tb'>
                    <tr>
                        <td>
                            <a href="index.php?action=post">新增留言</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="index.php?action=search">查詢留言</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="index.php?action=list">回首頁</a>
                        </td>
                    </tr>
                </table>
            </div>
            <div class='content'>
            <?php
            //!index.php 總入口
            /*
             * index.php的呼叫形式為:
             * 顯示所有留言:index.php?action=list
             * 新增留言    :index.php?action=post
             * 刪除留言    :index.php?action=delete&;id=x
             */
            /*function add(&$b) {
                $b = $b + 10;
                return $b;
            }
            $a='123';
            $b=add($a);
            echo "a=".$a."b=".$b;*/
            require_once("lib/DataAccess.php");
            require_once("lib/Model.php");
            require_once("lib/View.php");
            require_once("lib/Controller.php");
            //建立DataAccess物件(請根據你的需要修改引數值)
            $dao=new DataAccess ("localhost:33060", "root", "root","center88_DB");
            //$dao = $conn
            //根據$_GET["action"]取值的不同調用不同的控制器子類
            if(isset($_GET["action"])){
                $action=$_GET["action"];
                switch ($action)
                {
                    case "list":
                        if(isset($_GET["page"])){
                            $controller = new listController($dao, $page = $_GET["page"]);
                        }
                        else{
                            $controller = new listController($dao, $page = 1);
                        }
                        break;
                    case "search":
                        if(isset($_GET["input"]) && isset($_GET["page"])){
                            $controller = new searchController($dao, $page = $_GET["page"], $search = $_GET["input"]);
                        }
                        elseif(isset($_GET["input"])){
                            $controller = new searchController($dao, $page = 1, $search = $_GET["input"]);
                        }
                        else{
                            $controller = new searchController($dao, $page = 1, $search = NULL);
                        }
                        break;
                    case "post":
                        $controller = new postController($dao); 
                        break;
                    case "modify":
                        $controller = new modifyController($dao);
                        break;
                    case "delete":
                        $controller = new deleteController($dao);
                        break;
                    default:
                        $controller = new listController($dao);
                        break; //預設為顯示留言
                }
            }else{
                $controller = new listController($dao, $page = 1);
            }
            ?>
            </div>
        </div>
    </body>
</html>
