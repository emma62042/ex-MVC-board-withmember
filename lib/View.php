<?php
//! View 類
/*
 * 針對各個功能(list、post、delete)的各種View子類
 * 被Controller呼叫,完成不同功能的網頁顯示
 */
class View {
    function __construct(){
        ;
    }
    function sign() {
        if(empty($_SESSION["login_id"])){?>
            <div class='sign'>
            	<a href="index.php?action=login">會員登入</a>
        	</div>
        	<?php 
        }else{?>
            <div class='sign'>
            	<span>歡迎<?php echo $_SESSION["login_id"] ; ?></span>&nbsp;&nbsp;
           		<a href="index.php?action=logout">登出→</a>
            </div>
  <?php }
    }
    function banner() {?>
    	<div class='banner'>
			<p><a href="index.php?action=list">center88留言板</a></p>
		</div>
    <?php    
    }
    function sidebar() {?>
			<div class='sidebar'>
                <table class='bar_tb'>
          <?php if(isset($_SESSION["login_id"])){?>
                    <tr>
                        <td>
                            <a href="index.php?action=post">新增留言</a>
                        </td>
                    </tr>
          <?php }?>
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
          <?php if(isset($_SESSION["login_id"])){?>
          			<tr>
                        <td style="border-style:none;color:white;">
							---會員專區---
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="index.php?action=modifyMyData">修改資料</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="index.php?action=modifyMyPwd">修改密碼</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="index.php?action=listMyMsg">我的留言</a>
                        </td>
                    </tr>
          <?php }?>
                </table>
            </div>
	<?php 
    }
    
    function pleaseLogin() {?>
        <div class='content'>
        <h2>請先登入!</h2>
        <button onclick="location.href='index.php?action=login'">登入</button>
        </div>
    <?php     
    }
}
class listView    //顯示所有留言的子類
{
    function __construct(){
        ?>
        <div class='content'>
        <?php 
    }
    function viewMsgResult(&$notes) {
        foreach ($notes as $value)
        {
            ?>
			<table class="cont_tb">
				<tr>
					<td colspan="2">
						#<?php echo $value["msg_id"] ?>
					</td>
				</tr>
				<tr>
					<td>留言標題：</td>
                    	<td width="450">
                        	<?php echo $value["msg_title"] ?>
                    </td>
                </tr>
                <tr>
					<td>留言內容：</td>
					<td width="450">
					<?php
					$msg = str_replace("\n","<br/>",$value["msg"]);
                        echo $msg;
                    ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;">
					<?php echo $value["nickname"] . "&nbsp;發表於&nbsp;" . $value["time"] ?>
					</td>
				</tr>
				<?php if(isset($_SESSION["login_id"]) && $value["mb_id"] == $_SESSION["login_id"] ){?>
				<tr>
					<td colspan="2" style="text-align: right;">
						<button type="button" onclick="location.href='index.php?action=delete&id=<?php echo $value["msg_id"];?>'">刪除</button>
						<button type="button" onclick="location.href='index.php?action=modify&id=<?php echo $value["msg_id"];?>'">修改</button>
					</td>
				</tr>
		  <?php }?>
			</table>
			<br/>            
			<?php 
        }
    }
    function viewPage($action, $page_array, $search = NULL) {
        ?>
    
            <p>共<?php echo $page_array["data_rows"] ?>筆-在<?php echo $page_array["page"] ?>頁-共<?php echo $page_array["allpages"] ?>頁</p>
            <p><a href=index.php?action=<?php echo $action; echo $search ?>&page=1>首頁</a>-第
            <?php
            for( $i = 1 ; $i <= $page_array["allpages"] ; $i++ ) 
            {
                if ( $page_array["page"] -3 < $i && $i < $page_array["page"] +3 ) /*前2頁 後兩頁*/
                {?>
                	<a href=index.php?action=<?php echo $action; echo $search ?>&page=<?php echo $i ?> ><?php echo $i ?></a>
                    <?php
                }
            }?>
    		頁-<a href=index.php?action=<?php echo $action; echo $search ?>&page=<?php echo $page_array["allpages"] ?> >末頁</a>
            </p>
        <?php ;
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}

class searchView extends listView   //顯示所有留言的子類
{
    function __construct($search = NULL)//一呼叫就要做的
    {?>
    	<div class='content'>
    	<form action="index.php" method="get">
                <table cellpadding="10" width="600" border="1" align="center">
                    <tr>
                        <td>
                        	<input type="hidden" name="action" value="search">
    						搜尋：<input type="text" name="input" size="41" style="font-size:20px" 
                            value="<?php echo $search ?>">
    						<button type="submit">START</button>
    					</td>
    				</tr>
    			</table>
    		</form>
    		<br/>
    <?php   
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}

class postView   //顯示所有留言的子類
{
    function __construct(){?>
        <div class='content'>
    <?php 
    }
    function post(&$msg_array, $title){
    ?>
        <h3><?php echo $title=="post" ? "新增" : "修改" ?>留言</h3>
        	<form action="index.php?action=<?php echo $title?>" method="post">
            <table  style="border:3px #000000 dashed;" cellpadding="10" width="600" border="1" align="center">
                <tr>
                    <td>
                       	 您的暱稱：
                    </td>
                    <td>
                        <input type="text" name="nickname" size="38" style="font-size:20px" value="<?php echo $msg_array["nickname"] ?>">
                        <br/>
                        <?php echo $msg_array["errname"] ?>
                    </td>
                </tr>
                <tr>
                    <td>
						留言標題：
                    </td>
                    <td>
                        <input type="text" name="msg_title" size="38" style="font-size:20px" value="<?php echo $msg_array["msg_title"] ?>">
                        <br/>
                        <?php echo $msg_array["errtitle"] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                                                                        留言內容：
                    </td>
                    <td>
                        <textarea cols="45" rows="5" type="text" name="msg" style="font-size:16px"><?php echo $msg_array["msg"] ?></textarea>
                        <br/>
                        <?php echo $msg_array["errmsg"] ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                    	<input type="hidden" name="msg_id" value="<?php echo $msg_array["id"]?>">
                        <button type="submit"><?php echo $title=='post' ? "新增" : "修改" ?>完成</button>
                    </td>
                </tr>
            </table>
            </form>
    	<?php
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}

class modifyView extends postView{   //extends表示繼承
    function __construct () { 
        parent::__construct();
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}

class deleteView {
    function __construct(){?>
        <div class='content'>
    <?php
    }
    function delete (&$value) {
        if(isset($_GET['id'])){?>
            <form action="index.php?action=delete" method="post">
            	<table class='cont_tb'>
    				<tr>
    					<td colspan="2">
    						#<?php echo $value["msg_id"] ?>
    					</td>
    				</tr>
    				<tr>
    					<td>留言標題：</td>
                        	<td width="450">
                            	<?php echo $value["msg_title"] ?>
                        </td>
                    </tr>
                    <tr>
    					<td>留言內容：</td>
    					<td width="450">
    					<?php
    					$msg = str_replace("\n","<br/>",$value["msg"]);
                            echo $msg;
                        ?>
    					</td>
    				</tr>
    				<tr>
    					<td colspan="2" style="text-align: right;">
    					<?php echo $value["nickname"] . "&nbsp;發表於&nbsp;" . $value["time"] ?>
    					</td>
    				</tr>
    			</table>
    			<input type="hidden" name="msg_id" value="<?php echo $value["msg_id"]?>">
    			<button type="submit">確認刪除</button>
    		</form>
		
		<?php
        }
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}

class loginView{
    function __construct(){?>
        <div class='login_content'>
    <?php
    }
    function login (&$member_array) {
        if($member_array["success"] == 0){?>
        		<h2>會員登入</h2>
        		<form action="index.php?action=login" method="post">
            		<table  style="border:3px #000000 dashed;" cellpadding="10" width="400" border="1" align="center">
            			<tr>
            				<td>
                               	 帳號：
                            </td>
                            <td>
                                <input type="text" name="mb_id" style="font-size:20px" value="<?php echo $member_array["mb_id"] ?>">
                                <br/>
                                <?php echo $member_array["errid"] ?>
                            </td>
            			</tr>
            			<tr>
            				<td>
                               	 密碼：
                            </td>
                            <td>
                                <input type="password" name="mb_pwd" style="font-size:20px" value="<?php echo $member_array["mb_pwd"] ?>">
                                <br/>
                                <?php echo $member_array["errpwd"] ?>
                            </td>
            			</tr>
            		</table>
            		<br/>
            		<button type="submit">登入</button>
        		</form>
        		<br/>
        		<br/>
        		還沒有帳號嗎?&nbsp;&nbsp;<button onclick="location.href='index.php?action=signup'">註冊去→</button>
    	<?php 
        }elseif($member_array["success"] == 1){?>
            <h2>會員登入</h2>
        	登入成功!!&nbsp;&nbsp;將於3秒後跳轉至首頁
    		<?php 
    		header("Refresh: 2; URL=index.php");
        }
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}

class signupView{
    function __construct(){?>
        <div class='login_content'>
    <?php
    }
    function signup (&$signup_array) {
        if($signup_array["success"] == 0){?>
        		<h2>會員註冊</h2>
        		<form action="index.php?action=signup" method="post">
            		<table  style="border:3px #000000 dashed;" cellpadding="10" width="400" border="1" align="center">
            			<tr>
            				<td>
                               	 帳號：
                            </td>
                            <td>
                                <input type="text" name="set_id" style="font-size:20px" value="<?php echo $signup_array["set_id"] ?>">
                                <br/>
                                <?php echo $signup_array["errid"] ?>
                            </td>
            			</tr>
            			<tr>
            				<td>
                               	 密碼：
                            </td>
                            <td>
                                <input type="password" name="set_pwd" style="font-size:20px" value="<?php echo $signup_array["set_pwd"] ?>">
                                <br/>
                                <?php echo $signup_array["errpwd"] ?>
                            </td>
            			</tr>
            			<tr>
            				<td>
                               	 確認密碼：
                            </td>
                            <td>
                                <input type="password" name="check_pwd" style="font-size:20px" value="<?php echo $signup_array["check_pwd"] ?>">
                                <br/>
                                <?php echo $signup_array["errckpwd"] ?>
                            </td>
            			</tr>
            			<tr>
            				<td>
                               	 email：
                            </td>
                            <td>
                                <input type="email" name="set_email" style="font-size:20px" value="<?php echo $signup_array["set_email"] ?>">
                                <br/>
                                <?php echo $signup_array["erremail"] ?>
                            </td>
            			</tr>
            		</table>
            		<br/>
            		<button type="submit">註冊</button>
        		</form>
    	<?php 
        }elseif($signup_array["success"]){?>
            <h2>會員註冊</h2>
        	註冊成功!!&nbsp;&nbsp;將於3秒後跳轉至登入頁面
    		<?php 
    		header("Refresh: 2; URL=index.php?action=login");
        }
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}
class modifyMyDataView {
    function __construct(){?>
        <div class='content'>
    <?php
    }
    function modifyMyData (&$mb_array) {?>
        	<h2>會員專區</h2>
            <form action="index.php?action=modifyMyData" method="post">
            	<table class='cont_tb'>
    				<tr>
    					<td colspan="2">
    						修改會員資料
    					</td>
    				</tr>
    				<tr>
    					<td>
    						email：
    					</td>
                        <td width="450">
                            <input type="text" name="new_email" value="<?php echo $mb_array["email"] ?>">
                        </td>
                    </tr>
    				<tr>
    					<td colspan="2">
        					<button type="submit">確認修改</button>
    					</td>
    				</tr>
    			</table>
    		</form>
		
		<?php
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}
class modifyMyPwdView {
    function __construct(){?>
        <div class='content'>
    <?php
    }
    function modifyMyPwd (&$mb_array) {?>
        	<h2>會員專區</h2>
            <form action="index.php?action=modifyMyPwd" method="post">
            	<table class='cont_tb'>
    				<tr>
    					<td colspan="2">
    						修改密碼
    					</td>
    				</tr>
    				<tr>
    					<td>
    						目前密碼：
    					</td>
                        <td width="450">
                            <input type="password" name="old_mb_pwd">
                            <br/>
                            <?php echo $mb_array["erroldpwd"] ?>	
                        </td>
                    </tr>
                    <tr>
    					<td>
    						新密碼：
    					</td>
                        <td width="450">
                            <input type="password" name="new_mb_pwd">
                            <br/>
                            <?php echo $mb_array["errpwd"] ?>
                        </td>
                    </tr>
                    <tr>
    					<td>
    						確認新密碼：
    					</td>
                        <td width="450">
                            <input type="password" name="new_check_pwd">
                            <br/>
                            <?php echo $mb_array["errckpwd"] ?>
                        </td>
                    </tr>
    				<tr>
    					<td colspan="2" >
        					<button type="submit">確認修改密碼</button>
    					</td>
    				</tr>
    			</table>
    		</form>
		
		<?php
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}
class listMyMsgView extends listView {
    function __construct () {
        parent::__construct();?>
        	<h2>會員專區-我的留言</h2>
		<?php
    }
    function __destruct(){
        ?>
        </div>
        <?php 
    }
}
?>