<?php 
$to  = $data['email']; 

$subject = "Активация учетной записи"; 

$fullname = $data['lastname'].' '.$data['name'].' '.$data['middlename'];

//var_dump($data);

$activation_link = 'http://abit.polessu.by/actions/confirm_email.php?token='.getActivationLink($data['email']); 

$message = '
<html>
<head>
 <title>Активация учетной записи</title>
</head>
<body>
<h1>Здравствуйте, '.$fullname.'!</h1>
<p>Ваш адрес электронной почты был указан при регистрации на ресурсе <a href="#">Личный кабинет абитуриента ПолесГУ</a> (<a href="#">https://abit.polessu.by</a>).</p>

<p>Если это Вы прошли регистрацию на сайте, то продолжайте чтение письма, иначе просто удалите это письмо и примите наши извинения. Спасибо, что Вы хотите присоединиться к нашему ресурсу.</p>

<p>Для активации Вашей учетной записи, пожалуйста, перейдите по этой ссылке: 
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