ex-MVC-board
===

## Table of Contents
- [ex-MVC-board](#ex-MVC-board)
  * [Beginners Guide](#beginners-guide)

## Beginners Guide

center88留言板 with MVC  
* 2019/11/06  
    * 利用[範例](https://www.itread01.com/p/962428.html)完成list, search部分  
* 2019/11/07  
    * 修改css id→class  
    * ↑--完成list, search部分--↑  
    * 將原本放在controller的pageArray function移到model  
    * 將view的View大父類別去掉, 由search繼承list(因為輸出大都一樣)  
    * 加入新增留言(post)的功能，使用isset確認是否有$_POST的資料進來(是否第一次進到新增頁面)  
    * "新增完成"按鈕按下後isset通過，用!empty確認是否都有值，若有空值則回到原本畫面並在欄位輸出"請輸入xxx"的提醒文字
    * 若!empty通過則進入sql,query，query成功顯示"新增成功"字樣，3秒後跳回首頁。
    * ↑--完成post部分--↑
    * 修改部分使用與新增相同的view，在model的部分，考慮到安全性的問題，"確認"修改寫進資料庫的sql由$_POST觸發，一開始的修改畫面則由$_GET觸發。
    * 將modifyNote分成兩部分，修改前畫面/修改送出動作與修改後畫面
    * 修改前畫面由$_GET開始，從資料庫取出msg內容放到msg_array，回傳msg_array給view
    * 若接收到$_POST["id"]則進入update資料庫，update成功後秀出"修改成功"並保持欄位值，3秒後跳回首頁
    * ↑--完成modify部分--↑
    * 刪除部分另外寫View，一樣由於安全性的問題，希望"確認"刪除資料庫的sql由$_POST觸發，因此加入了確認刪除畫面，由$_GET觸發。
    * 沿用list的table view，在下方加入"確認刪除"按鈕
    * 大致上流程與修改相同，從資料庫取出資料秀出，從$_POST接收確認id★
    * 由於刪除後VIEW的table會抓不到資料，在view那裏加入了if(isset($_GET["id"]))，有id則秀table
    ，沒有則顯示"刪除成功/失敗"
    * ↑--完成delete部分--↑
    
    

