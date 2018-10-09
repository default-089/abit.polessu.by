<div class="wrap">
    <div class="content">
        <?php include "../layout/header.html"; ?>

<div id="main">           
<h2 class="pt-md-3">Личный кабинет абитуриента</h2>
<h5>Восстановление пароля</h5>
<hr>       

<?php
    $data = $_POST;
    if(isset($data['email']))
    {
        $errors = array();
        $user = R::findOne('users', 'email = ?', array($data['email']));
        if($user)
        {//если пользователь с таким email существет
            require __DIR__.'/../layout/password_recovery.php';
            echo '<div class="alert alert-success pl-lg-5" role="alert"><p><strong>Проверьте свой почтовый ящик</strong></p><p>На ваш адрес электронной почты отправлено письмо с инструкцией по восстановлению пароля.</p></div>';
        } else {
            $errors[] = 'Пользователь с таким email не найден!';
        }
        if(!empty($errors))
        {
            echo '<div class="alert alert-danger pl-lg-5" role="alert">'.array_shift($errors).'</div>';
        } 
    }
?>


            
<div class="row justify-content-center pt-md-5 pt-2">
    <div class="card mt-5">
<div class="card-header"><strong>Восстановление пароля</strong></div>
<div class="card-body">
<br>
<p>Введите email, котрый вы использовали при регистрации: </p>
<form method="POST" action="#">
<div class="form-group row">
    <div class="col-sm-8">
        <input type="text" class="form-control" name="email">
    </div>
    <div class="row justify-content-center col-sm-4">
        <button type="submit" class="btn px-5">Продолжить</button>
    </div>
</div>
    <br>
</form>
</div>
<div class="card-footer">
    Если вы не помните какой вводили email, рекомендуется пройти регистрацию заново, для этого кликните <a href="signup.php">здесь</a>.
  </div>
</div>
    </div>
        </div>
        </div>
    </div>
        <div class="footer"><?php include "../layout/footer.html"; ?></div>
</div>








