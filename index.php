<?php
session_start();

include_once 'db_connect.php'; // Подключение БД
include_once 'config.php'; //Конфиг
include_once 'handler.php'; //Конфиг

echo '<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://getbootstrap.com/favicon.ico">

    <title>Пример авторизации через Telegram</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sticky-footer.css" rel="stylesheet">

    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <style type="text/css">

.login-with-t {
  text-decoration: none;
  display: inline-block;
  vertical-align: middle;
  width: 156px;
  height: 35px;
  line-height: 35px;
  border: 1px solid #40aadc;
  border-radius: 4px;
  color: #40aadc;
  background: url("add_sprite.svg") no-repeat 119px -19px #ffffff;
  padding-left: 15px;
  font-weight: 16px;
  box-sizing: inherit;
  font-size: 17px;
  font-family: Arial,sans-serif;
  margin: -10px 5px -10px 220px;
}
.login-with-t:hover {
  background-position: 119px 7px;
  background-color: #40aadc;
  text-decoration: none;
  color: #ffffff;
}
.login-with-t:active {
  background-color: #3396c5;
  text-decoration: none;
}
.login-with-t:visited {
  text-decoration: none;
}
  
</style>

  </head>

  <body>

    <!-- Begin page content -->
    <div class="container">
      <div class="page-header">
        <h1>Пример авторизации через Telegram</h1>
      </div>
      <p class="lead">Авторизация происходит с помощью бота <a href="http://telegram.me/TestAuthBot">@TestAuthBot</a></p>';
if (!$user)
{
  echo'<br><br><br><br><br><br><br>
      <a href="login.php" class="login-with-t" target="_blank">Войти через</a>';
}
else 
{
  echo '<p> Здравствуй, <b>'.$user['first_name'].' '.$user['last_name'].'</b></p>
  <img src="'.$user['avatarka'].'" alt="">
  <p>Твой Telegram ID: <b>'.$user['telegram_id'].'</b></p>
  <p>Твой username: <b>';
  if ($user['username']!='')
  {
    echo '<a href="http://telegram.me/'.$user['username'].'">@'.$user['username'].'</a></b></p>';
  }
  else 
  {
    echo 'отсуствует</b></p>';
  }
  echo'
  <br><br><br><br><br>
    
    <p class="text-center"><a class="btn btn-primary" href="exit.php" role="button">Выйти</a></p>
    
    ';
}
    echo'
    </div>
    <footer class="footer">
      <div class="container">
        <p class="text-muted">Graffiti © 2016</p>
      </div>
    </footer>


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  

</body></html>';