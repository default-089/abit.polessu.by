<div class="wrap">
	<div class="content">
		<?php include "layout/header.html"; ?>

<h1 class="pl-md-5 pl-3 pt-md-3">Панель администрирования</h1>
		<hr>

<?php
    $data = $_GET;
    if(isset($data['searchAbit']))
    {
        $errors = array();
        $user = R::findOne('users', 'email = ?', array($data['searchAbit']));
        if($user)
        {//если пользователь с таким email существет
        	$applicants = R::getRow('SELECT * FROM applicants WHERE users_id=?', array($user->id));
			$parents = R::getAll('SELECT * FROM parents WHERE applicants_id=?', array($applicants[id]));//2 массива на выходе
			$contest = R::getAll('SELECT * FROM contest WHERE applicants_id=?', array($applicants[id]));//несколько массивов на выходе
        } else {
            $errors[] = 'Пользователь с таким email не найден!';
        }        
        if(!empty($errors))
        {
            echo '<div class="alert alert-danger pl-lg-5" role="alert">'.array_shift($errors).'</div>';
        } 
    }
?>
		
<!-- particles.js container -->
<div id="particles-js"></div>

	<div id="main">
<form method="GET" action="admin.php">
<div class="row justify-content-center text-center mt-md-5 pt-md-5 mt-2 pt-2">
<div class="col-lg-8 col-md-6">
<div class="form-group row">
	<div class="col-sm-10">
		<input type="text" class="form-control" id="searchAbit" name="searchAbit" autocomplete="off" list="search_result">
		<datalist id="search_result"></datalist>
	</div>
	<div class="col-sm-2">
		<button type="submit" class="btn px-5">Поиск</button>
	</div>
</div>
</div>
</div>
</form>
<div id="result">
<?php
if($user){
	echo '<pre>';
	echo "<h4>Информация об абитуриенте:</h4>";
	print_r($applicants);
	echo "<hr>";
	echo "<h4>Информация о родителях абитуриента:</h4>";
	print_r($parents);
	echo "<hr>";
	echo "<h4>Информация о выбранных специальностях:</h4>";
	print_r($contest);
	echo '</pre>';
}
?>
</div>
</div> <!-- end main -->
	</div>
	<div class="footer"><?php include "layout/footer.html"; ?></div>
</div>


<!-- scripts -->
<script src="demo/particles.min.js" defer></script>
<script src="demo/app.js" defer></script>
<style>
	#particles-js{
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: -1;
	}
</style>


<script type="text/javascript">
$(function(){
    
//Живой поиск
$('#searchAbit').on("keyup", function() {
    if(this.value.length >= 3){
    	$( '#search_result' ).find( 'option' ).remove();
        $.ajax({
            type: 'post',
            url: "../actions/requests.php",	 //Путь к обработчику
            data: "request=getAbitEmail&search=" + this.value + "%",
            success: function(data){
            	var i = 0, result = JSON.parse(data);
				for (i; i < result.length; i++ ) {$( '#search_result' ).append( '<option value="' + result[i].email + '">');}
           }
       })
    }
})  
$('#searchAbit').change(function() {
	$("#searchAbit").blur(); //Убираем фокус с input
});
})
</script>