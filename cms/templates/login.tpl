<html>
    <head>
        <title>%config.sitename%</title>
        <style>
            body { font-family: Tahoma, Verdana, Arial, sans-serif; }
        </style>
    </head>
    <body>
        <table width="100%" height="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">
                    <table id="loginform">
                        <tr><td align="center" style="color: rgb(0, 153, 255); font-size: 22px;"><img src="/public/logo2.png"/><br /><br />
                            <table cellpadding="5" style="width:100px;">
                                <tr>
                                    <td><img src="/public/user.jpg" /></td>
                                    <td><input type="text" id="login_mail" style="color: rgb(0, 153, 255); font-size: 22px; width: 200px;" /></td>
        
                                    <td><img src="/public/pass.jpg" /></td>
                                    <td><input id="login_pass" type="password" style="color: rgb(0, 153, 255); font-size: 22px; width: 200px;" /></td>
                                    <td><a id="login_button" class="menu" href="#" onclick="catcher('loginform');return false;">Войти</a></td>
                                </tr>
                                <tr>
                                    <td colspan="5" align="center"></td>
                                </tr>
                            </table>
        
                        </td></tr>
                        <tr><td>
                            <div id="msg" style="font-size:16px;color:red;padding:20px;border:1px solid red;display:none;">
                            </div>
                        </td></tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <script>
        $(document).ready(function(){
            $('#login_pass').keypress(function(event){
                if (event.which == '13') {
                    $('#login_button').click();
                }
            });
        });
        </script>
    </body>
</html>