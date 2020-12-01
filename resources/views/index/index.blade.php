<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>实时显示票数</title>
<script src="http://apps.bdimg.com/libs/jquery/1.6.4/jquery.js"></script>
<script src="http://apps.bdimg.com/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript">
    function startLottery(){
        $.ajax({
            type:'post',
            url:'/start',
            dataType:'text',
            headers:{
                //'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                'X-CSRF-TOKEN' : '{{ csrf_token() }}'
            },
            success:function(data){
                console.log(data);
                alert(data);
                location.reload();
            },
            error: function(){
                alert('Ajax error!');
            }
        });
    }
</script>
</head>
<body>
    <span>{{$user_name}}的首页</span>
    <span><input type="button" value="退出登录" onclick="location.href='/logout'"></span>
    <h1>抽奖首页</h1>
    <h3 id="chance">今日剩余机会：{{$chance}}次</h3>
    <h3>奖品列表：</h3>
    <table style="border:1px solid black">
        <tr>
            <td>iphone</td>
            <td>戴森吹风机</td>
            <td>小米手环</td>
        </tr>
    </table>
    <br>
    <input type="button" value="开始抽奖" onclick="startLottery();" >
    <br>
    <h3>中奖记录：</h3>
    @foreach($self_info as $sfk=>$sfv)
        <span>{{$sfv->award_name}}</span>
    @endforeach
    <br>
</body>