<html>
<head>
<!-- <script src="//js/jquery-3.5.1.min.js"></script> -->
<!-- <script>
    $( document ).ready(function() {
    
        $("#search").click(function(){
            $("#search").attr('disabled', true);
            $.ajax({
                url:"/admin/transaction/search_record",
                method:'get',
                datatype:'json',
                data:'uid='+$("#uid").val(), 
                success:function(res){
                    $("#search").attr('disabled', false);
                    $("search_result").html(res);
                },
                error:function(err){
                    $("#search").attr('disabled', false);
                    console.log(err)
                },
            });
        });

    });
    
</script> -->
</head>
<body>
    <form action ="">
    <input type="text" id="uid" name="uid" placeholder="請輸入uid">
    <label><input type="radio" name="uidtype" value="tempUser" checked> 遊客</label>
    <label><input type="radio" name="uidtype" value="User"> 會員 </label>

        <!-- <input type="submit" value="即时取得"> -->
        <button id="search">查詢</button>&nbsp;
    </form>
    <div id="search_result"></div>
</body>
</html>