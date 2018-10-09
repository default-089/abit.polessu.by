(function($) {
	
	// Включаем строгий режим ECMA-Script
	"use strict";
	
	$.extend({
		request: function( options ) {
			
			// В методе request будут различные опции (настройки)
			// Это своего рода настройки по умолчанию, созданные
			// в объекте options
			options = $.extend({
				
				type: "POST",
				url: "../actions/requests.php",	
				data: null,							// Данные, которые мы будем передавать серверу
				async: false,						// Асинхронность выполнения AJAX-запроса
				dataType: "json",					// Тип данных, в котором они передаются
				before: null,						// Код, выполняемый перед AJAX-запросом
				error: function(jqXHR, textStatus, errorThrown) {console.log('Ошибка: ' + textStatus + ' | ' + errorThrown)},
				complete: options.callback,			// Код, выполняемый после AJAX-запроса	
				success: function( result ) {$.response.result = result;},
				result: null,						// Результат работы
				callback: null						// Функция обратного вызова
				
			}, options );
			
			// Тело AJAX-запроса
			$.ajax({
				
				type: options.type,
				url: options.url,
				data: options.data,
				async: options.async,
				dataType: options.dataType,
				before: options.before,
				error: options.error,
				complete: options.complete,
				success: options.success
				
			});
			return this;
		},
		// Объект, в котором хранится ответ от сервера, полученный через AJAX-запрос
		response: {
			result: {}
		}
	});
	
	jQuery(function() {


		//Обработчик события тип документа 
	$('#DocsType').change(function(){
		if($(this).val()==0){
			$( '#IdentDocsFieldset' ).prop( 'disabled', true );
			$( '#DocsID' ).val('');
			$( '#DocsSeries' ).val('').removeClass('CheckErr is-invalid');
			$( '#docsNumb' ).val('').removeClass('CheckErr is-invalid');
			$( '#DocsData' ).val('').removeClass('CheckErr is-invalid');
			$( '#DocsIssued' ).val('').removeClass('CheckErr is-invalid');
	   }else{
	   		$(' #IdentDocsFieldset' ).prop( 'disabled', false );
	   		$( '#DocsID' );
			$( '#DocsSeries' ).addClass('CheckErr');
			$( '#docsNumb' ).addClass('CheckErr');
			$( '#DocsData' ).addClass('CheckErr');
			$( '#DocsIssued' ).addClass('CheckErr');
	   };
	});//Обработчик события тип документа 



	// Обработчик события выбора уровня образования
	$( '#level' ).change(function() {
		
		var educ_level_id = $( this ).val();	// Идентификатор выбранного уровня

		//Отключаем поля и удаляем все значения и отключаем проверку на правильность введенных данных
		$( '#EducFieldset' ).prop( 'disabled', true );
		$( '#institution' ).removeClass('CheckErr is-invalid').find( 'option:not( :first )' ).remove();
		$( '#docsLevel' ).removeClass('CheckErr is-invalid').find( 'option:not( :first )' ).remove();
		$( '#InstNumb' ).val('').removeClass('CheckErr is-invalid');
		// $( '#InstDocsNumb' ).val('').removeClass('CheckErr is-invalid');
		$( '#DateEnd' ).val('').removeClass('CheckErr is-invalid');
		

		if ( educ_level_id != 0 ) { //если выбран уровень

			$("#edLevel_free").children().text($('#level option:selected').text()); // вставляем выбранное значение на вторую вкладку
			$("input[name='edLevel_free']").val($('#level option:selected').val()); // вставляем выбранное значение на вторую вкладку
			if(educ_level_id > 1 && educ_level_id < 4){$("#educPeriod_free option[value='2']").prop( 'disabled', false );				
			}else{$("#educPeriod_free option[value='2']").prop( 'disabled', true );
			$("#educPeriod_free option[value='1']").prop('selected', true);}//срок обучения в зависимости от уровня образвания

			$("#edLevel_paid").children().text($('#level option:selected').text()); // вставляем выбранное значение на третью вкладку
			$("input[name='edLevel_paid']").val($('#level option:selected').val()); // вставляем выбранное значение на третью вкладку
			if(educ_level_id > 1 && educ_level_id < 4){$("#educPeriod_paid option[value='2']").prop( 'disabled', false ); 
			}else{$("#educPeriod_paid option[value='2']").prop( 'disabled', true );
			$("#educPeriod_paid option[value='1']").prop('selected', true);}//срок обучения в зависимости от уровня образвания

			// Создаем AJAX-запрос
			$.request({
				data: "request=getLevels&educ_level_id=" + educ_level_id,
			});
			//вставляем данные из AJAX запроса в Select
			var i = 0, institution = $.response.result.p1;
			for ( i; i < institution.length; i++ ) {
				$( '#institution' ).append( '<option value="' + institution[ i ].school_type_id + '">' + institution[ i ].name + '</option>' );
			}
			var i = 0, docsLevel = $.response.result.p2;
			for ( i; i < docsLevel.length; i++ ) {
				$( '#docsLevel' ).append( '<option value="' + docsLevel[ i ].educ_doc_type_id + '">' + docsLevel[ i ].name + '</option>' );
			}
			
			// Включаем поля и добавляем проверку на ошибки
			$( '#EducFieldset' ).prop( 'disabled', false );
			$( '#institution' ).addClass('CheckErr');
			$( '#docsLevel' ).addClass('CheckErr');
			$( '#InstNumb' ).addClass('CheckErr');
			// $( '#InstDocsNumb' ).addClass('CheckErr');
			$( '#DateEnd' ).addClass('CheckErr');
		}
	}); // Обработчик события выбора уровня образования



	// Обработчик события выбора области
	$( '#Oblast' ).change(function() {

		$( '#regiondis' ).prop( 'disabled', false );

		//Удаляем предыдущие загруженные значения
		$( '#DistrictList' ).find( 'option:not( :first )' ).remove();
		$( '#NameLocalList' ).find( 'option:not( :first )' ).remove();

		var oblast_id = $( this ).val();	// Идентификатор выбранной области
		if(oblast_id != ''){
			$.request({
				data: "request=getRaions&oblast_id=" + oblast_id,
			});
			//вставляем данные из AJAX запроса в input
			var i = 0, district = $.response.result.p1;
			for ( i; i < district.length; i++ ) {
				$( '#DistrictList' ).append( '<option value="' + district[ i ].name + '">');
			}
			var i = 0, namelocal = $.response.result.p2;
			for ( i; i < namelocal.length; i++ ) {
				$( '#NameLocalList' ).append( '<option value="' + namelocal[ i ].name + '">');
			}
		} 
	}); // Обработчик события выбора области



	//функция изменения кода телефона в зависимости от выбранной страны
	$( '#Country' ).change(function() {

		$( '#Oblast' ).prop( 'disabled', false );

		var country = $( this ).val();	// Идентификатор выбранной страны

		if(country != ''){
		$.request({
				data: "request=getMobNumb&country=" + country,
		});
		var kod = $.response.result;
		//alert(kod);
		if(kod == null){
			$("#MobNumber").mask("+mnnnnnnnnnnn?nn", {placeholder: " " });
		}else{
			$("#MobNumber").mask("+" + kod + " (mm) mmm-mm-mm");
		}}
	});//функция изменения кода телефона в зависимости от выбранной страны




	//функция изменения кода города в зависимости от выбранного населенного пункта
	$( '#NameLocal' ).change(function() {

		var settlement = $( this ).val();	// Идентификатор выбранного города

		if(settlement != ''){
		$.request({
				data: "request=getMobNumbSett&settlement=" + settlement,
		});
		$('#KodNumber').val($.response.result);
		}
	});//функция изменения кода города в зависимости от выбранного населенного пункта


	// Обработчик события трудовой деятельности
	$( '#OccupType' ).change(function() {
		if($(this).val()==0){
			$('#occup').prop('disabled', true );
			$( '#WorkPlace' ).val('').removeClass('CheckErr is-invalid');
		}else{
			$( '#occup' ).prop( 'disabled', false );
			$( '#WorkPlace' ).addClass('CheckErr'); 
		}
	}); // Обработчик события трудовой деятельности




	// Обработчик события выбора отца
	$( '#TypeF' ).change(function() {
		if($(this).val()==0){
			$('#FatherFieldset').prop( 'disabled', true );
			$('#lastnameF').val('').removeClass('CheckErr is-invalid');
			$('#nameF').val('').removeClass('CheckErr is-invalid');
			$('#middlenameF').val('').removeClass('CheckErr is-invalid');
			$('#AddressF').val('');
			$('#AddressAreaF').val('');
			$('#WorkPlaceF').val(null);
		}else{
			$( '#FatherFieldset').prop( 'disabled', false );
			$( '#lastnameF' ).addClass('CheckErr');
			$( '#nameF' ).addClass('CheckErr');
			$( '#middlenameF' ).addClass('CheckErr');
		}
	}); // Обработчик события выбора отца
	//адрес отца
	$('#AddressF').click(function(){
    if ($(this).is(':checked')){
        $('#AddressAreaF').prop('disabled', true ).val('');
    } else {
        $( '#AddressAreaF' ).prop( 'disabled', false )
    }});//адрес отца


	// Обработчик события выбора матери
	$( '#TypeM' ).change(function() {
		if($(this).val()==0){
			$('#MotherFieldset').prop( 'disabled', true );
			$('#lastnameM').val('').removeClass('CheckErr is-invalid');
			$('#nameM').val('').removeClass('CheckErr is-invalid');
			$('#middlenameM').val('').removeClass('CheckErr is-invalid');
			$('#AddressM').val('');
			$('#AddressAreaM').val('');
			$('#WorkPlaceM').val('');
		}else{
			$( '#MotherFieldset').prop( 'disabled', false );
			$( '#lastnameM' ).addClass('CheckErr');
			$( '#nameM' ).addClass('CheckErr');
			$( '#middlenameM' ).addClass('CheckErr');
		}
	}); // Обработчик события выбора матери
	//адрес матери
	$('#AddressM').click(function(){
    if ($(this).is(':checked')){
        $('#AddressAreaM').prop('disabled', true ).val('');
    } else {
        $( '#AddressAreaM' ).prop( 'disabled', false )
    }});//адрес матери



		// Обработчик события отправки данных для сохранения на сервер с первой вкладки
    $(".BtSave").click(function(){

    	var errorsum = 0;

    	$("div.invalid-feedback").remove();
    	$(".CheckErr").each(function(index, element){
			if($(element).val() == 0 || $(element).val() == ''){
    			$(element).addClass('is-invalid').removeClass('is-valid');
    			var errortxt = $("<div></div>").text("Пожалуйста, заполните поле "+ $("label[for='" + this.id + "']").text().toLowerCase()).addClass('invalid-feedback');
    			$(this).after(errortxt);
    			errorsum++;
    		}else{
    			$(element).addClass('is-valid').removeClass('is-invalid');
    		}
    	});


    	//при необходимости добавить проверок, например:
		/*var phone = $('input[name="phone"]').val(),
		intRegex = /[0-9 -()+]+$/;
		if((phone.length < 6) || (!intRegex.test(phone))){
			alert('Пожалуйста введите ваш настоящий телефон');
			return false;
		}*/


    	//удаляем класс invalid при изменении объекта
    	$('.CheckErr').click(function() {
    	$(this).keyup(function(){
    		$(this).removeClass('is-invalid'); //дописать условия проверок при необходимости для Live проверки
		});}); // для input

		$('.CheckErrRadio').click(function() {$("input[name = '" + this.name + "']").removeClass('is-invalid');}); //для radio
		$("input[type= 'date' ]").change(function() {$(this).removeClass('is-invalid');}); //для даты
		$('select').change(function() {$(this).removeClass('is-invalid');}); //для select
		// end -> удаляем класс invalid при изменении объекта


		if(errorsum == 0){ // если нет ошибок переходим на вторую вкладку и включаем кнопки для сохранения
			$('.BtSave').hide();
			$('.NotSave').show();
			$('.BtChange').show();
			$("#tab2-tab").trigger("click");
		}else{
			$("#tab1-tab").trigger("click");
			$('.is-invalid:first').focus();
			//alert(errorsum);
			return false; 
		}  	
	}); // Обработчик события отправки данных для сохранения на сервер с первой вкладки



//обработчик для второй вкладки (бюджет)-------------------------------------------------------------------------------------------------------------------------------------------------
		var specialty_arr_free = new Array(); // для хранения групп специальностей
		var specialtySumFree = 2; //для приоритета других выбранных специалностей

$( "#show_specialty_free" ).click(function(){ // задаем функцию при нажатиии на элемент <button>

		$('#info_speciality_free').show();
		$('#specialty_row_free').children().remove();
		$('#show_specialty_free').text('Показать специальности');

		var educ_form_id = $('#educForm_free option:selected').val();
		var faculty_id = $('#faculty_free option:selected').val();
		var educ_period = $('#educPeriod_free option:selected').val();
		var educ_level_id = $("input[name='edLevel_free']").val();
		// Создаем AJAX-запрос
		$.request({
			data: "request=getSpecialtyFree&educ_form_id=" + educ_form_id + "&educ_period=" + educ_period + "&faculty_id=" + faculty_id + "&educ_level_id=" + educ_level_id, //&financing_id=1, - для сортировки по финансированию
		});
		
		SetCardSpecialty(1, "free");
		var i = 0, specialty = $.response.result;
		for ( i; i < specialty.length; i++ ) {
			$("select[name^='specialty_free_']:last").append( '<option value="' + specialty[ i ].specialty_id + '">' + specialty[ i ].name.name + '</option>' );
			specialty_arr_free[i] = {
				"specialty_id": specialty[ i ].specialty_id,
    			"contest_group_id": specialty[ i ].contest_group_id,
    			"name": specialty[ i ].name.name
			} // сохранение групп специальностей в массив
		}
 	  });

	//Обработчик события выбор специальности
	$(document).on("change", "select[name ='specialty_free_1']", function(){

		$('#specialty_row_free').children().not("div#specialty_free_1").remove();
		var specialtySumFree = 2; // "обнуление" счетчика

		if($(this).val()==0){
			//если ничего не выбрано
	   }else{
	   		for (var i = 0; i < specialty_arr_free.length; i++) {
	   			if($(this).val() == specialty_arr_free[i].specialty_id){
	   				for (var j = 0; j < specialty_arr_free.length; j++){
	   					if(specialty_arr_free[i].contest_group_id == specialty_arr_free[j].contest_group_id){
	   						if($(this).val() != specialty_arr_free[j].specialty_id){
	   							SetCardSpecialty(specialtySumFree, "free");
	   							specialtySumFree++;
	   							for (var z = 0; z < specialty_arr_free.length; z++) {
	   								if($(this).val() != specialty_arr_free[z].specialty_id && specialty_arr_free[z].contest_group_id == specialty_arr_free[i].contest_group_id){
	   									$("select[name^='specialty_free_']:last").append( '<option value="' + specialty_arr_free[ z ].specialty_id + '">' + specialty_arr_free[ z ].name + '</option>' );
	   								}
	   							};
	   						}else{
	   							//если в группе больше нет значений
	   						}
	   		}}}};};          
    });//Обработчик события выбор специальности

//скрываем выбранное значение, чтобы пользователь не выбрал его дважды
$(document).on("change", $("select[name^='specialty_free_']"), function(){
    var chosen = $("select[name^='specialty_free_']").map(function(i, el){
        return $(':selected',el);
    });
    var specId = 0;
    $('option:not(:first-child)', $("select[name^='specialty_free_']")).show().prop('disabled', false);
    chosen.each(function(i, el){
        specId = $(el).val();
        $('option:not(:first-child)', $("select[name^='specialty_free_']")).not(el).filter(function(){
            return $(this).val() == specId;
        }).hide().prop('disabled', true);
    });
    return false;
}); //скрываем выбранное значение, чтобы пользователь не выбрал его дважды
// конец обработчика для второй вкладки (бюджет)-------------------------------------------------------------------------------------------------------------------------------------------------


//обработчик для треьей вкладки (платно)-------------------------------------------------------------------------------------------------------------------------------------------------
		var specialty_arr_paid = new Array(); // для хранения групп специальностей
		var specialtySumPaid = 2; //для приоритета других выбранных специалностей

$( "#show_specialty_paid" ).click(function(){ // задаем функцию при нажатиии на элемент <button>

		$('#info_speciality_paid').show();
		$('#specialty_row_paid').children().remove();
		$('#show_specialty_paid').text('Показать специальности');

		var educ_form_id = $('#educForm_paid option:selected').val();
		var faculty_id = $('#faculty_paid option:selected').val();
		var educ_period = $('#educPeriod_paid option:selected').val();
		var educ_level_id = $("input[name='edLevel_paid']").val();
		// Создаем AJAX-запрос
		$.request({
			data: "request=getSpecialtyPaid&educ_form_id=" + educ_form_id + "&educ_period=" + educ_period +  "&faculty_id=" + faculty_id + "&educ_level_id=" + educ_level_id, //&financing_id=1, - для сортировки по финансированию
		});
		
		SetCardSpecialty(1, "paid");
		var i = 0, specialty = $.response.result;
		for ( i; i < specialty.length; i++ ) {
			$("select[name^='specialty_paid_']:last").append( '<option value="' + specialty[ i ].specialty_id + '">' + specialty[ i ].name.name + '</option>' );
			specialty_arr_paid[i] = {
				"specialty_id": specialty[ i ].specialty_id,
    			"contest_group_id": specialty[ i ].contest_group_id,
    			"name": specialty[ i ].name.name
			} // сохранение групп специальностей в массив
		}
 	  });

	//Обработчик события выбор специальности
	$(document).on("change", "select[name ='specialty_paid_1']", function(){

		$('#specialty_row_paid').children().not("div#specialty_paid_1").remove();
		var specialtySumPaid = 2; // "обнуление" счетчика

		if($(this).val()==0){
			//если ничего не выбрано
	   }else{
	   		for (var i = 0; i < specialty_arr_paid.length; i++) {
	   			if($(this).val() == specialty_arr_paid[i].specialty_id){
	   				for (var j = 0; j < specialty_arr_paid.length; j++){
	   					if(specialty_arr_paid[i].contest_group_id == specialty_arr_paid[j].contest_group_id){
	   						if($(this).val() != specialty_arr_paid[j].specialty_id){
	   							SetCardSpecialty(specialtySumPaid, "paid");
	   							specialtySumPaid++;
	   							for (var z = 0; z < specialty_arr_paid.length; z++) {
	   								if($(this).val() != specialty_arr_paid[z].specialty_id && specialty_arr_paid[z].contest_group_id == specialty_arr_paid[i].contest_group_id){
	   									$("select[name^='specialty_paid_']:last").append( '<option value="' + specialty_arr_paid[ z ].specialty_id + '">' + specialty_arr_paid[ z ].name + '</option>' );
	   								}
	   							};
	   						}else{
	   							//если в группе больше нет значений
	   						}
	   		}}}};};          
    });//Обработчик события выбор специальности

//скрываем выбранное значение, чтобы пользователь не выбрал его дважды
$(document).on("change", $("select[name^='specialty_paid_']"), function(){
    var chosen = $("select[name^='specialty_paid_']").map(function(i, el){
        return $(':selected',el);
    });
    var specId = 0;
    $('option:not(:first-child)', $("select[name^='specialty_paid_']")).show().prop('disabled', false);
    chosen.each(function(i, el){
        specId = $(el).val();
        $('option:not(:first-child)', $("select[name^='specialty_paid_']")).not(el).filter(function(){
            return $(this).val() == specId;
        }).hide().prop('disabled', true);
    });
    return false;
}); //скрываем выбранное значение, чтобы пользователь не выбрал его дважды		
// конец обработчика для третьей вкладки (платно)-------------------------------------------------------------------------------------------------------------------------------------------------


//добавление блоков specialty
function SetCardSpecialty(prio, type){
var specialtyAdd = '<div class="col-12" id="specialty_' + type + '_' + prio + '">' + 
    '<div class="card">' +
      '<div class="card-body">'+
		  '<div class="form-group row display-4">'+
		  '<label class="col-2 text-center col-form-label">' + prio + '</label>'+
			'<input type="text" name="prio_' + type + '_' + prio + '" value="' + prio + '" hidden>'	+
		      '<div class="col-10 align-self-center">'+
				'<select class="form-control" name="specialty_' + type + '_' + prio + '">'+
					'<option value=0></option>'+ 
				'</select>'+
			  '</div>'+
		  '</div>'+
      '</div>'+
    '</div>'+
  '</div>';
$('#specialty_row_' + type).append(specialtyAdd);
}//добавление блоков specialty





//анимация загрузки для ajax запросов
$( document ).ajaxStart(function() {startLoadingAnimation()}).ajaxComplete(function() {$("#loader").hide();});

function startLoadingAnimation() // - функция запуска анимации
{
  // найдем элемент с изображением загрузки и уберем невидимость:
  var imgObj = $("#loadImg");
  $("#loader").show();
  // вычислим в какие координаты нужно поместить изображение загрузки, чтобы оно оказалось в серидине страницы:
  var centerY = $(window).scrollTop() + ($(window).height() + imgObj.height())/2;
  var centerX = $(window).scrollLeft() + ($(window).width() + imgObj.width())/2;
  // поменяем координаты изображения на нужные:
  imgObj.offset({top:centerY, left:centerX});
}//анимация загрузки для ajax запросов

	


	});
	
})(jQuery); // Используем немедленно вызываемую анонимную функцию