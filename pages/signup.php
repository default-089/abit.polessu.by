<div class="wrap">
            <div class="content">
                <?php include "../layout/header.html"; ?>
        
<?php   
    $data = $_POST;
    if(isset($data['do_signup']))
    {
        $errors = array();
        if(trim($data['lastname']) == '')
        {
            $errors[]='Введите фамилию';
        }
        if(trim($data['name']) == '')
        {
            $errors[]='Введите имя';
        }
        if(trim($data['middlename']) == '')
        {
            $errors[]='Введите отчество';
        }
        if(trim($data['email']) == '')
        {
            $errors[]='Введите email';
        }
        if($data['pass1'] == '')
        {
            $errors[]='Введите пароль';
        }
        if($data['pass2'] != $data['pass1'])
        {
            $errors[]='Повторный пароль введен не верно';
        }
        if(R::count('users', "email = ?", array($data['email']))>0)
        {
            $errors[]='Пользователь с таким email уже существует!';
        }
        if(empty($errors)) // если все данные введены верно, выполняется этот код
        {
            $user = R::dispense('users');
            $user->lastname = $data['lastname'];
            $user->name = $data['name'];
            $user->middlename = $data['middlename'];
            $user->email = $data['email'];
            $user->verified = false;
            $user->token = getActivationLink($data['email']);
            $user->password = password_hash($data['pass1'], PASSWORD_DEFAULT);
            R::store($user);
            require __DIR__.'/../layout/sending_token.php';
            echo '<div class="alert alert-success pl-lg-5" role="alert"><p><strong>Проверьте свой почтовый ящик</strong></p><p>На ваш адрес электронной почты отправлено письмо с инструкцией по подтверждению регистрации.</p></div>';
            
            //удалить!!
            //echo '<a href="'.$activation_link.'">'.$activation_link.'</a><br>';
            //удалить!!
        } 
        else{
            echo '<div class="alert alert-danger pl-lg-5" role="alert">'.array_shift($errors).'</div>';
        }
    }


function getActivationLink($email){
    $secret = "Alaska";
    return md5($secret.$email);
}
?>
<div id="main">
<form method="POST" action="signup.php">
	<div class="row justify-content-lg-center text-center mt-lg-4 pt-lg-5 mt-2 pt-2">
		<div class="col-xl-6 col-lg-8">
		<div class="form-group row">
	<label for="lastname" class="col-sm-4 text-right col-form-label">Фамилия</label>
    <div class="col-sm-6">		
    <input type="text" class="form-control textrus" id="lastname" name="lastname" value="<?php echo @$data['lastname'];?>"></div></div>
		<div class="form-group row">
	<label for="name" class="col-sm-4 text-right col-form-label">Имя</label>
	<div class="col-sm-6">	
		<input type="text" class="form-control textrus" id="name" name="name" value="<?php echo @$data['name'];?>"></div></div>	
		<div class="form-group row">
	<label for="middlename" class="col-sm-4 text-right col-form-label">Отчество</label>
	<div class="col-sm-6">	
		<input type="text" class="form-control textrus" id="middlename" name="middlename" value="<?php echo @$data['middlename'];?>"></div></div>
		<div class="form-group row">
	<label for="email" class="col-sm-4 text-right col-form-label">Email</label>
	<div class="col-sm-6">	
		<input type="email" class="form-control" id="email" name="email" value="<?php echo @$data['email'];?>"></div></div>
        

<div class="form-group row mb-0 pt-2">
    <label class="col-sm-4 col-form-label"></label>
<div class="text-left col-sm-7 rules">
        Пароль должен соответствовать критериям:
 <ul class="rules">
  <li class="rules symb no" id="letter" class="invalid">Минимум <strong>одна латинская буква</strong></li>
  <li class="rules symb no" id="capital">Минимум <strong>одна заглавная латинская буква</strong></li>
  <li class="rules symb no" id="number">Минимум <strong>одна цифра</strong> или <strong>спец символ</strong></li>
  <li class="rules symb no"id="length">Быть не менее <strong>8 символов</strong></li>
 </ul>
</div>
</div> 
<div class="form-group row">
	<label for="pass1" class="col-sm-4 text-right col-form-label">Пароль</label>
	<div class="col-sm-6">
		<input type="password" class="form-control" id="pass1" name="pass1" value="<?php echo @$data['pass1'];?>"></div></div>
		<div class="form-group row">
	<label for="pass2" class="col-sm-4 text-right col-form-label">Подтвердите пароль</label>
	<div class="col-sm-6">
		<input type="password" class="form-control" id="pass2" name="pass2"value="<?php echo @$data['pass2'];?>"></div></div>	
	<div class="form-group row mt-lg-4">
   		<div class="col-12">
    <button type="submit" class="btn btn-success px-4" name="do_signup" id="do_signup">Зарегистрироваться</button>
		</div>
    </div>
</div>
</form>
</div>
</div>
    </div>
    <div class="footer"><?php include "../layout/footer.html"; ?></div>
</div>



<!-- Проверка полей на правильность введенных данных -->
<script>
    $('#pass1').focus(function(){
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


    $("#email").change(function(){
        var email = $(this).val(); // Получаем e-mail пользователя
        var regex = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        if(regex.test(email)) {
            $(this).addClass('is-valid').removeClass('is-invalid');
        } else {
            $(this).addClass('is-invalid').removeClass('is-valid');
            var errortxt = $("<div></div>").text("email введен некорректно").addClass('invalid-feedback');
            $(this).after(errortxt);
        }
    }); 

    $(".textrus").change(function(){
        var textrus = $(this).val();
        var regex = /^[а-яА-Я]{1,}$/;
        if(regex.test(textrus)){
            $(this).addClass('is-valid').removeClass('is-invalid');
        }else{
            $(this).addClass('is-invalid').removeClass('is-valid');
            var errortxt = $("<div></div>").text("Поле "+ $("label[for='" + this.id + "']").text().toLowerCase() + " должно содержать только русские буквы").addClass('invalid-feedback');
            $(this).after(errortxt);
        }
    });

    //удаляем класс invalid при изменении объекта
    $(document).on("click", ".is-invalid", function(){
        $(this).keyup(function(){
            $(this).removeClass('is-invalid');
            $("div.invalid-feedback").remove();
        });

    }); // для input

    $("#do_signup").click(function(){
        if($('.is-invalid').length == 0 && $('.no').length == 0){
            return true;
        }else{
            //alert('.is-invalid" = '+$('.is-invalid').length+', .no = '+$('.no').length);
            return false;
        }   
    });

</script>
		
        

        