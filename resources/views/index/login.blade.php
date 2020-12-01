<h1>登录页面</h1>
<br>
<form action="" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    用户名：<input type="text" name="user_name">
    <br>
    密码：<input type="password" name="password">
    <br>
    <input type="submit" value="登录">
</form>