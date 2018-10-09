<?php 
$to  = $data['email']; 

$subject = "Восстановление пароля"; 

$fullname = $user->lastname.' '.$user->name.' '.$user->middlename;

//var_dump($data);

$activation_link = 'http://abit.polessu.by/actions/new_password.php?token='.$user->password; 

$message = '
<html>
<head>
 <title>Восстановление пароля</title>
</head>
<body>
<h1>Здравствуйте, '.$fullname.'!</h1>
<p>Ваш адрес электронной почты был указан для восстановления пароля на ресурсе <a href="#">Личный кабинет абитуриента ПолесГУ</a> (<a href="#">https://abit.polessu.by</a>).</p>

<p>Если это запрос отправили Вы, тогда продолжайте чтение письма, иначе просто удалите это письмо и примите наши извинения. Спасибо, что Вы хотите присоединиться к нашему ресурсу.</p>

<p>Для сброса пароля, пожалуйста, перейдите по этой ссылке: 
<a href="'.$activation_link.'">'.$activation_link.'</a></p>

<p>Если переход по ссылке не работает, то скопируйте её в адресную строку Вашего браузера.</p>
</body>
</html>
';

$headers  = "Content-type: text/html; charset=utf-8 \r\n";  //В первой строчке ми определяем тип отправляемого письма-HTML и кодировку windows-1251.
$headers .= "From: От кого письмо <box@polessu.by>\r\n"; //В 2-ом мы указываем от кого пришло письмо.
$headers .= "Reply-To: box@polessu.by\r\n";  //В 3-ем указываем e-mail адрес, для ответа на письмо.

$status = mail($to, $subject, $message, $headers);

?>