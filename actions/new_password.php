<div class="wrap">
    <div class="content">
        <?php include "../layout/header.html";?>
        <div id="main">
<h2 class="pt-md-3">Личный кабинет абитуриента</h2>
<h5>Восстановление пароля</h5>
<hr> 

<?php
$user = R::findOne('users','password = ?', array(htmlspecialchars(trim($_GET['token']))));

$part2 = true;
$new_pass = trim($_POST['pass']);
if($new_pass != ''){
	$user->password = password_hash($new_pass, PASSWORD_DEFAULT);
    R::store($user);
    echo "<p>Пароль успешно изменен!</p>";
    $part2 = false;
}

if(isset($user['password']) && $part2): ?>
<div class="row justify-content-center pt-md-5 pt-2">
	<div class="col-lg-8 col-md-10">
<div class="card mt-5">
	<div class="card-header"><strong>Восстановление пароля</strong></div>
	<div class="card-body">
		<br>
		<p>Введите новый пароль: </p>
		<form method="POST" action="#">
		<div class="form-group row">
		    <div class="col-sm-8">
		        <input type="password" class="form-control" name="pass" id="pass">
		    </div>
		    <div class="row justify-content-center col-sm-4">
		        <button type="submit" class="btn px-md-5" id="reset_pass">Продолжить</button>
		    </div>
		</div>
		    <br>
		</form>
</div>
	<div class="card-footer">
	    Пароль должен соответствовать критериям:
		 <ul class="rules">
		  <li class="rules symb no" id="letter" class="invalid">Минимум <strong>одна латинская буква</strong></li>
		  <li class="rules symb no" id="capital">Минимум <strong>одна заглавная латинская буква</strong></li>
		  <li class="rules symb no" id="number">Минимум <strong>одна цифра</strong> или <strong>спец символ</strong></li>
		  <li class="rules symb no"id="length">Быть не менее <strong>8 символов</strong></li>
		 </ul>
	</div>
</div>
	</div>
</div>


<?php else : 
	echo ("<p><a href='../index.php'>Перейти на главную страницу</a></p>");
?>

<?php endif; ?>
</div>
    </div>
    <div class="footer"><?php include "../layout/footer.html"; ?></div>
		</div>


<script>
$('#pass').focus(function(){
    $(this).keyup(function(){
        var pass = $(this).val(); // Получаем пароль пользователя
        var regex_az = /[a-z]/;
        var regex_AZ = /[A-Z]/;
        var regex_09 = /[0-9]|[\W]/;
        var regex_min8 = /^.{8,}$/;
        if(regex_az.test(pass)){$('#letter').removeClass('no').addClass('yes');}else{$('#letter').removeClass('yes').addClass('no');};
        if(regex_AZ.test(pass)){$('#capital').removeClass('no').addClass('yes');}else{$('#capital').removeClass('yes').addClass('no');};
        if(regex_09.test(pass)){$('#number').removeClass('no').addClass('yes');}else{$('#number').removeClass('yes').addClass('no');};
        if(regex_min8.test(pass)){$('#length').removeClass('no').addClass('yes');}else{$('#length').removeClass('yes').addClass('no');};            
    });
});
$("#reset_pass").click(function(){
        if($('.no').length == 0){
            return true;
        }else{
            return false;
        }   
    });
</script>