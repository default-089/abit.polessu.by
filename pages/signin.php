<div class="wrap">
    <div class="content">
        <?php include_once "../layout/header.html"; ?>
        
<?php if(isset($_SESSION['logged_user'])): ?>
<?php
    header ('Location: register.php');  // если пользователь авторизован, перенаправление на нужную страницу
    exit();
?>

<?php else : ?>
<div id="main">
    <h2 class="pt-md-3">Личный кабинет абитуриента</h2>
    <h5>Вход в систему</h5>
    <hr>
<?php
    $data = $_POST;
    if(isset($data['do_signin']))
    {
        $errors = array();
        $user = R::findOne('users', 'email = ?', array($data['email']));
        if($user)
        {//если пользователь с таким email существет

            if($user->verified == 1){ //проверка на подтвержденность email
                if( password_verify( $data['pass'] , $user->password )) //проверка пароля
                {//логиним пользователя
                    $_SESSION['logged_user'] = $user;
                    header ('Location: profile.php');  // перенаправление на нужную страницу
                    exit();
                } else {
                    $errors[] = 'Пароль введен неверно!';
                }
            }else{
                $errors[] = 'Email адрес не подтвержден!';
            }
        } else {
            $errors[] = 'Пользователь с таким email не найден!';
        }
        if(!empty($errors))
        {
            echo '<div class="alert alert-danger pl-lg-5" role="alert">'.array_shift($errors).'</div>';
        } 
    }
?>
        <div class="row justify-content-center text-center mt-md-5 pt-md-5 mt-2 pt-2">
            <div class="col-lg-4 col-md-6 px-sm-5 mx-md-5">
                <form method="POST" action="signin.php">
                <div class="form-group">
                    <label for="email">Email адрес</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo @$data['email'];?>">
                </div>
                <div class="form-group">
                    <label for="pass">Пароль</label>
                    <input type="password" class="form-control" id="pass" name="pass" value="<?php echo @$data['pass'];?>">
                </div>
                <button type="submit" class="btn btn-success btn-block my-lg-3" name="do_signin">Войти</button>
                <a href="forgot_password.php">Забыли пароль?</a>
                &nbsp;|&nbsp;
                <a href="signup.php">Регистрация</a>
                </form>
            </div>
        </div>
        </div>
    </div>
        <div class="footer"><?php include "../layout/footer.html"; ?></div>
</div>

<?php endif; ?>







