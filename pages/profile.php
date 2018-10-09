<div class="wrap">
  	<div class="content">
  			<?php include "../layout/header.html"; ?>		
		<div id="main">

<?php if(isset($_SESSION['logged_user'])): //если пользователь авторизован 

$applicants = R::getRow('SELECT * FROM applicants WHERE users_id=?', array($_SESSION['logged_user']->id));
if(isset($applicants[created_time])): // если пользователь уже создавал заявление

$parents = R::getAll('SELECT * FROM parents WHERE applicants_id=?', array($applicants[id]));//2 массива на выходе
$contest = R::getAll('SELECT * FROM contest WHERE applicants_id=?', array($applicants[id]));//несколько массивов на выходе

if(!$applicants[fill_all]){echo '<div class="alert alert-warning pl-lg-5 mt-lg-4 mt-md-3 mt-1 mb-1" role="alert"><h4>Заявление заполнено не полностью</h4></div>';}
?>
<div class="row justify-content-between">
    <div class="col-auto">
      <a class="btn btn-success m-3 px-5" href="register.php">Редактировать</a>
      <!-- <a class="btn btn-outline-danger m-3 px-3"  href="../action/del.php" onclick="return confirm('Вы действительно хотите удалить заявление?')">Удалить заявление</a> -->
    </div>
    <div class="col-auto">
      <a class= <?if($applicants[fill_all]){echo "'btn btn-primary m-3 px-3'";}else{echo"'btn btn-secondary m-3 px-3 disabled'";}?> href="../actions/print.php"  >Печатать заявление</a>
    </div>
  </div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Личная информация</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Заявление</a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">

<!--первая вкладка-->
<div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab"> <?php include "../layout/profile_tab1.html"; ?> </div> <!--end first tab-->
 
<!--вторая вкладка-->    
<div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab"><?php include "../layout/profile_tab2.html"; ?></div>
		
</div>

<a class="btn btn-success m-3 px-5" href="register.php">Редактировать</a>

		</div> <!--main-->
  	</div>
  		<div class="footer"><?php include "../layout/footer.html"; ?></div>
</div>

<?php else : // если пользователь еще не создавал заявление
    header ('Location: register.php'); //перенаправление на страницу создания заявления
    exit();
endif; ?>

<?php else : // если пользователь не авторизован
    header ('Location: ../index.php'); //перенаправление на главную страницу
    exit();
endif; ?>