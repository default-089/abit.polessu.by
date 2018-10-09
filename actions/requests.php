<?php
require_once __DIR__.'/../libs/db.php';
//проверки на пустоту
if ( empty( $_POST ) ) { die( "Массив \$_POST пустой" ); }
elseif (empty( $_POST['request'])) { die("Не передан запрос"); }

else {
	// Очищаем строку с типом запроса от лишних пробелов и защищаемся от возможных SQL-инъекций
	$request = htmlspecialchars(trim($_POST['request']));
}

// В переменной $response будем возвращать данные AJAX-запросу
$response = NULL;

switch ( $request ) {
	case "getLevels":
		$response['p1'] = R::getAll('SELECT school_type_id, name FROM ref_school_types WHERE educ_level_id = ?', array(htmlspecialchars(trim($_POST['educ_level_id']))));
		$response['p2'] = R::getAll('SELECT educ_doc_type_id, name  FROM ref_educ_doc_types WHERE educ_level_id = ?', array(htmlspecialchars(trim($_POST['educ_level_id']))));
	break;

	case "getRaions":
		$oblast = R::findOne('ref_BLR_oblasts','name = ?', array(htmlspecialchars(trim($_POST['oblast_id']))));
		$oblast_id = $oblast->oblast_id;
		$response['p1'] = R::getAll('SELECT name FROM ref_BLR_raions WHERE oblast_id = ?', array($oblast_id));
		$response['p2'] = R::getAll('SELECT name, settlement_type_id  FROM ref_BLR_settlements WHERE oblast_id = ?', array($oblast_id));
	break;

	case "getMobNumb":
		$country = R::findOne('ref_countries','name = ?', array(htmlspecialchars(trim($_POST['country']))));
		$response = $country->phone_code;
	break;

	case "getMobNumbSett":
		$settlement = R::findOne('ref_BLR_settlements','name = ?', array(htmlspecialchars(trim($_POST['settlement']))));
		$settlement_id = $settlement->settlement_id;
		//R::getCell( 'SELECT title FROM page LIMIT 1' );
		$settlementcode = R::findOne('ref_BLR_phone_codes','settlement_id = ?', array($settlement_id));
		$response = $settlementcode->phone_code;
	break;

	case "getSpecialtyFree":
		$educ_period = htmlspecialchars(trim($_POST['educ_period']));
		$educ_level_id = htmlspecialchars(trim($_POST['educ_level_id']));
		if($educ_period == 2){$educ_lvl_min = 2;
		}elseif ($educ_level_id == 4) {$educ_lvl_min = 4;
		}else{$educ_lvl_min = 1;}
		$educ_lvl_max = htmlspecialchars(trim($_POST['educ_level_id'])); if($educ_period == 1 && $educ_level_id != 4){$educ_lvl_max = 1;}
		$contests = R::findAll('contests','faculty_id = ? AND educ_form_id = ? AND educ_level_id >= ? AND educ_level_id <= ? AND max_enrol_free > 0', array(htmlspecialchars(trim($_POST['faculty_id'])), htmlspecialchars(trim($_POST['educ_form_id'])), $educ_lvl_min, $educ_lvl_max));
		$i = 0;
		foreach ($contests as $contest) {
			$response[$i]['contest_group_id'] = $contest->contest_group_id;
			$response[$i]['specialty_id'] = $contest->specialty_id;   
			$response[$i]['name'] = R::getRow('SELECT name FROM ref_specialties WHERE specialty_id = ?', array($contest->specialty_id));
			$i++;
		}
	break;

	case "getSpecialtyPaid":
		$educ_period = htmlspecialchars(trim($_POST['educ_period']));
		$educ_level_id = htmlspecialchars(trim($_POST['educ_level_id']));
		if($educ_period == 2){$educ_lvl_min = 2;
		}elseif ($educ_level_id == 4) {$educ_lvl_min = 4;
		}else{$educ_lvl_min = 1;}
		$educ_lvl_max = htmlspecialchars(trim($_POST['educ_level_id'])); if($educ_period == 1 && $educ_level_id != 4){$educ_lvl_max = 1;}
		$contests = R::findAll('contests','faculty_id = ? AND educ_form_id = ? AND educ_level_id >= ? AND educ_level_id <= ? AND max_enrol_paid > 0', array(htmlspecialchars(trim($_POST['faculty_id'])), htmlspecialchars(trim($_POST['educ_form_id'])), $educ_lvl_min, $educ_lvl_max));
		$i = 0;
		foreach ($contests as $contest) {
			$response[$i]['contest_group_id'] = $contest->contest_group_id;
			$response[$i]['specialty_id'] = $contest->specialty_id;   
			$response[$i]['name'] = R::getRow('SELECT name FROM ref_specialties WHERE specialty_id = ?', array($contest->specialty_id));
			$i++;
		}
	break;

	case "getAbitEmail":
		$email = R::getAll('SELECT email FROM users WHERE email LIKE ?', array(htmlspecialchars(trim($_POST['search']))));
		$response = $email;
	break;
}


echo json_encode($response);
?>