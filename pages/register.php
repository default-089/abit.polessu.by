<div class="wrap">
  	<div class="content">
  			<?php include "../layout/header.html"; ?>		
		<div id="main">

<?php if(isset($_SESSION['logged_user'])): ?>
<?

$applicants = R::getRow('SELECT * FROM applicants WHERE users_id=?', array($_SESSION['logged_user']->id));
$parents = R::getAll('SELECT * FROM parents WHERE applicants_id=?', array($applicants[id]));//2 массива на выходе
//разделение на 2 обычных массива
if($parents[0][sex] == "m"){$parent_f = $parents[0];}else{$parent_m = $parents[0];}
if($parents[1][sex] == "f"){$parent_m = $parents[1];}

$contest_free = R::getAll('SELECT * FROM contest WHERE applicants_id=? AND type = "f"', array($applicants[id]));//выбранные бюджетные специальности
$contests_row_free = R::getRow('SELECT faculty_id, educ_form_id FROM contests WHERE specialty_id = ? LIMIT 1', array($contest_free[0][specialty_id]));//id факультета и формы образования

$contest_paid = R::getAll('SELECT * FROM contest WHERE applicants_id=? AND type = "p"', array($applicants[id]));//выбранные платные специальности
$contests_row_paid = R::getRow('SELECT faculty_id, educ_form_id FROM contests WHERE specialty_id = ? LIMIT 1', array($contest_paid[0][specialty_id]));//id факультета и формы образования
?>

<button type="button" class="btn btn-success my-3 BtSave" <?if(isset($applicants[created_time])){echo'style = "display:none"';}?>>Сохранить и перейти к выбору специальностей</button>
<a href="profile.php" class="btn btn-outline-dark my-3 ml-md-3 NotSave" <?if(isset($applicants[created_time])){}else{echo'style = "display:none"';}?>>Закрыть без сохранения изменений</a>
<button type="submit" class="btn btn-success my-3 ml-md-2 BtSave BtChange" form="abit" <?if(isset($applicants[created_time])){}else{echo'style = "display:none"';}?>>Сохранить изменения в заявлении</button>


<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Личная информация</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Специальности</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Специальности (платное)</a>
  </li>
</ul>


<form action="../actions/save.php" method="post" id="abit" novalidate>
<div class="tab-content" id="myTabContent">

<!--первая вкладка-->
<div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab"> <?php include "../layout/tab1.html"; ?> </div> <!--end first tab-->
 
<!--вторая вкладка-->    
<div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab"> <?php include "../layout/tab2_free.html"; ?> </div> <!--end second tab-->

<!--третья вкладка-->    
<div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab"> <?php include "../layout/tab3_paid.html"; ?> </div> <!--end third tab-->
		
</div>
</form>

<button type="button" class="btn btn-success my-3 BtSave" <?if(isset($applicants[created_time])){echo'style = "display:none"';}?>>Сохранить и перейти к выбору специальностей</button>
<a href="profile.php" class="btn btn-outline-dark my-3 ml-md-3 NotSave" <?if(isset($applicants[created_time])){}else{echo'style = "display:none"';}?>>Закрыть без сохранения изменений</a>
<button type="submit" class="btn btn-success my-3 ml-md-2 BtSave BtChange" form="abit" <?if(isset($applicants[created_time])){}else{echo'style = "display:none"';}?>>Сохранить изменения в заявлении</button>
		</div> <!--main-->
  	</div> <!-- content -->
  		<div class="footer"><?php include "../layout/footer.html"; ?></div>
</div>


<?php else : // если пользователь не авторизован?> 
    <?php header ('Location: ../index.php'); //перенаправление на главную страницу
    exit();?>
<?php endif; ?>




<style type="text/css">
  @media (max-width: 768px) {
    html{font-size: 0.9rem;}
    .btn{display:block; width:100%; white-space: normal; margin: 0 auto;}
    
    }
</style>




<script type="text/javascript">
  jQuery('document').ready(function () {
  
  //всплывающие подсказки / вызывает ошибки в консоли браузера (исправить)
  $('[data-toggle="tooltip"]').focusin(
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
  );

  $(".textnumb").change(function(){
      var textrus = $(this).val();
      var regex = /^[0-9]{1,}$/;
      if(regex.test(textrus)){
          $(this).addClass('is-valid').removeClass('is-invalid');
      }else{
          $(this).addClass('is-invalid').removeClass('is-valid');
          var errortxt = $("<div></div>").text("Поле "+ $("label[for='" + this.id + "']").text().toLowerCase() + " может содержать только цифры").addClass('invalid-feedback');
          $(this).after(errortxt);
      }
  });

  $(".textrus").change(function(){
      var textrus = $(this).val();
      var regex = /^[а-яА-Я\s]{1,}$/;
      if(regex.test(textrus)){
          $(this).addClass('is-valid').removeClass('is-invalid');
      }else{
          $(this).addClass('is-invalid').removeClass('is-valid');
          var errortxt = $("<div></div>").text("Поле "+ $("label[for='" + this.id + "']").text().toLowerCase() + " может содержать только русские буквы").addClass('invalid-feedback');
          $(this).after(errortxt);
      }
  });

  $(".text_rus_numb").change(function(){
      var textrus = $(this).val();
      var regex = /^[а-яА-Я0-9\s\-№\.\,]{1,}$/;
      if(regex.test(textrus)){
          $(this).addClass('is-valid').removeClass('is-invalid');
      }else{
          $(this).addClass('is-invalid').removeClass('is-valid');
          var errortxt = $("<div></div>").text("Поле "+ $("label[for='" + this.id + "']").text().toLowerCase() + " может содержать только русские буквы и цифры").addClass('invalid-feedback');
          $(this).after(errortxt);
      }
  });

  /*
    при необходимости добавить еще проверок
  */

  //удаляем класс invalid при изменении объекта
  $(document).on("click", ".is-invalid", function(){
      $(this).keyup(function(){
          $(this).removeClass('is-invalid');
          $("div.invalid-feedback").remove();
      });

  }); // для input



  //маски для полей
  $.mask.definitions['9'] = '';
  $.mask.definitions['m'] = '[0-9]';
  $.mask.definitions['n']='[-0-9]';
  $.mask.definitions['e']='[-0-9]';
  $("#KodNumber").mask("m?mmmm", {placeholder: " " });
  $("#HomeNumber").mask("mmnnnn?nnn", {placeholder: " " });
  $("#MobNumber").mask("+mnnnnnnnnnnn?nn", {placeholder: " " });
  $("#WorkExp1").mask("m?m", {placeholder: " " });
  $("#WorkExp2").mask("m?m", {placeholder: " " });
  $("#WorkExpPro1").mask("m?m", {placeholder: " " });
  $("#WorkExpPro2").mask("m?m", {placeholder: " " });

});
</script>

<script src="../js/load.js"></script>

