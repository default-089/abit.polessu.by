<div class="wrap">
    <div class="content">
        <?php include "../layout/header.html";?>
        <div id="main">

<?php
$user = R::findOne('users','token = ?', array(htmlspecialchars(trim($_GET['token']))));

if($user){
	echo "Запись успешно активирована!";
	$user->verified = true;
    $user->token = null;
    R::store($user);
	echo ("<p><a href='../pages/register.php'>приступить к заполнению анкеты!</a></p>");
	$_SESSION['logged_user'] = $user;
}else{
	echo "что-то пошло не так :("; //токен не найден в большинстве случаев
	echo ("<p><a href='../index.php'>Перейти на главную страницу</a></p>");
}
?>

<p>через 10 секунд переход будет осуществлен автоматически: </p>
<span id="timer">10</span>

</div>
    </div>
    <div class="footer"><?php include "../layout/footer.html"; ?></div>
		</div>



<script type="text/javascript">
var i = 10;
setInterval(function() {
	$('#timer').text(i); i--;
	if(i==0){
		window.location.replace('../pages/register.php');
		return;
	}
}, 1000);
</script>