ex-MVC-board-withmember
===

## Table of Contents
- [ex-MVC-board](#ex-MVC-board)
  * [Beginners Guide](#beginners-guide)

## Beginners Guide

center88留言板 with MVC + member  
* 2019/11/08  
    * 複製ex-MVC-board
    * 將index當中的sign, banner移到class View{}中, sidebar寫成函式供各個class function呼叫
    * ##
    * View加入會員登入畫面，先加入欄位是否空判斷
    * 加入"註冊去"按鈕
    * View加入註冊畫面
    * 直接建立center88_member表
    * 在center88_board表中加入mb_id欄位(用來關聯member)
    * 加入註冊欄位判斷，'密碼'和'確認密碼'欄位是否相同的判斷
    * 條件都符合後加入sql query
    * 秀出"註冊成功"
    ##
    * 會員登入的條件有:會員帳號不可跟資料庫內重複、密碼與確認密碼欄位相同
    * 會員登入的欄位條件都符合後，加入sql query，並將mb_id存於$_SESSION["login_id"]中★
    * 新增會員登入/會員專區的<div class="sign">內容
    * View製作會員專區畫面
    * 新增會員專區用的sidebar "修改資料" "修改密碼" "我的留言" "回首頁"
    * 會員專區的首頁在"修改資料"
    ##
    * 【修改資料頁面】
    * 從資料庫中取出同id的除id、密碼外的資料，大致跟修改留言很像(id=$_SESSION["login_id"])
    * 修改後執行sql query UPDATE進去
    * 【修改密碼頁面】
    * 有 "目前密碼" "新密碼" "確認新密碼" 欄位
    * 通過條件是:目前密碼 == mb_pwd 、"新密碼" "確認新密碼"相同
    * 修改後執行sql query UPDATE進去
    * 【我的留言頁面】
    * 利用留言板首頁listView的模板，加入WHERE mb_id = $_SESSION["login_id"]
    * 沿用首頁的分頁功能
    * 可以對留言的mb_id == $_SESSION["login_id"]的留言顯示修改與刪除按鈕(更改listView)★
    ##
    * 修改首頁viewMessage function 若留言mb_id == $_SESSION["login_id"]則顯示修改與刪除按鈕
* 2019/11/11
    * 【登出功能】
    * 使用session_destroy();刪除所有session★
    * 回到首頁
    * 【限制登入者使用】
    * 在View這個父類別中，新增一個function pleaseLogin()，為"請先登入"的content區塊畫面
    * 在各個controller 呼叫model之前，確認isset $_SESSION["login_id"]，若未登入則顯示(view) pleaseLogin()
    * 新增、修改、刪除、會員專區的功能都限制會員使用。
    * 【將<div class="content">拆出】
    * 將div header設為class建構子，其餘動作設為class 中的 function
    ##
    未做工作:function整理，看要不要拆成兩個mvc
    
    
    

