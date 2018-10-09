<div class="wrap">
	<div class="content">
		<?php include "layout/header.html"; ?>
<?php if(isset($_SESSION['logged_user'])): ?>
<?php
    header ('Location: pages/profile.php');  // если пользователь авторизован, перенаправление на нужную страницу
    exit();
?>
<?php else : ?>

	<div id="main" class="mx-md-5">
		<br><br><br>
		<div class="text-center my-lg-5 py-lg-5 my-md-3 py-md-3">
			<a class="btn btn-primary btn-lg my-2" href="pages/signup.php">Создать кабинет</a>
			<a class="btn btn-success btn-lg my-2"href="pages/signin.php">Войти в кабинет</a>
		</div>
		<div class="card mt-5">
		<div class="card-header"><strong>Уважаемые абитуриенты!</strong></div>
		<div class="card-body">
После регистрации в Личном кабинете Вы сможете:<br>
- ввести информацию о себе, что позволит сократить время приема документов при личном посещении приемной комиссии ПолесГУ;<br>
- выбрать форму обучения и специальность (специальности), на которую(ые) собираетесь поступать;<br>
- сформировать предварительное заявление, которое подается в приемную комиссию.<br>
Все замечания и вопросы по работе Личного кабинета присылайте по адресу box@polessu.by</div></div><br>
	</div>
	</div>
	<div class="footer"><?php include "layout/footer.html"; ?></div>
</div>

<?php endif; ?>