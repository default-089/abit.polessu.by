<?php
require_once __DIR__.'/../libs/db.php';

$data = $_POST;

/*
echo "<pre>";
print_r($data);
echo "</pre>";*/

$fill_all = 0; // счетчик количества заполненных полей

$users = R::load('users', $_SESSION['logged_user']->id); // первая таблица
$users->lastname = $data[lastname];
$users->name = $data[name];
$users->middlename = $data[middlename]; 

$_SESSION['logged_user']->lastname = $data[lastname];
$_SESSION['logged_user']->name = $data[name];
$_SESSION['logged_user']->middlename = $data[middlename];


//таблица applicants
$applicants = R::findOne('applicants', 'users_id = ?', array($_SESSION['logged_user']->id)); // загружаем для редактирвоания 

if(isset($applicants)){//если запись существует
	$applicants->modifiedTime = date('Y-m-d H:i:s'); //записываем дату редактирвоания
	$applicants->modifiedBy = get_ip(); // + get_browser(), get_OS()
	$applicants_id = $applicants->id;

}else{//если запись не найдена, создаем новую
	$applicants = R::dispense('applicants'); // открываем для записи
	$applicants->createdTime = date('Y-m-d H:i:s'); //записываем дату записи
	$applicants->modifiedTime = null;
	$applicants->modifiedBy = get_ip(); // + get_browser(), get_OS()
}


//1. ФИО
$applicants->lastnameBel = $data[lastnameB];
$applicants->nameBel = $data[nameB];
$applicants->middlenameBel = $data[middlenameB];
$applicants->birthDate = $data[dob];
$applicants->sex = $data[Sex];
$applicants->relationship= $data[StatusF];

//2. Документ, удостоверяющий личность 
if($data[DocsType] != 0){
	$applicants->identDocTypeId = $data[DocsType];
	$applicants->identDocIdentNumb = $data[DocsID];
	$applicants->identDocSeries = $data[DocsSeries];
	$applicants->identDocNumb = $data[docsNumb];
	$applicants->identDocIssuedDate = $data[DocsData];
	$applicants->identDocIssuedBy = $data[DocsIssued];
	$fill_all++;
}

//3. Льготы при зачислении
if(trim($data[Privileges]) != ''){
	$applicants->privileges = $data[Privileges];
}


//4. Образование
if($data[level] != 0){
	$applicants->schoolTypeId = $data[institution];
	$applicants->educLevelId = $data[level];
	$applicants->educDocTypeId = $data[docsLevel];
	$applicants->educDocIssuedBy = $data[InstNumb];
	$applicants->educDocNumb = $data[InstDocsNumb];
	$applicants->educDocIssuedDate = $data[DateEnd];
	$fill_all++;
}if(trim($data[Lingo]) != ''){$applicants->foreignLanguage = $data[Lingo];}

//5. Адреc
if(trim($data[Country]) != ''){$applicants->addrCountry = $data[Country]; $fill_all++;}
if(trim($data[PostalCode]) != ''){$applicants->addrPostalCode = $data[PostalCode]; $fill_all++;}
if(trim($data[Oblast]) != ''){$applicants->addrOblast = $data[Oblast];}
if(trim($data[District]) != ''){$applicants->addrRaion = $data[District];}
if($data[TypeLocal] != 0){$applicants->settlementTypeId = $data[TypeLocal];}
if(trim($data[NameLocal]) != ''){$applicants->addrSettlement = $data[NameLocal]; $fill_all++;}
if($data[TypeStreet] != 0){$applicants->streetTypeId = $data[TypeStreet];}
if(trim($data[NameStreet]) != ''){$applicants->addrStreet = $data[NameStreet];}
if(trim($data[addrStreetNr]) != ''){$applicants->addrStreetNr = $data[addrStreetNr]; $fill_all++;}
if(trim($data[addrBuilding]) != ''){$applicants->addrBuilding = $data[addrBuilding];}
if(trim($data[addrFlat]) != ''){$applicants->addrFlat = $data[addrFlat];}
if($data[HostelCheck] == "on"){$applicants->needHostel = true;
}else{$applicants->needHostel = false;} //общежитие чекбокс


//6. Телефон и email
$applicants->phoneCode = $data[KodNumber];
$applicants->phoneHome = $data[HomeNumber];
$applicants->phoneMobile = $data[MobNumber];

//7. Трудовая деятельность
if($data[OccupType] != 0){
	$applicants->occupDocTypeId = $data[OccupType];
$applicants->occupJob = $data[WorkPlace];
if($data[WorkExp1] != 0){$applicants->occupExpFullY = $data[WorkExp1];}
if($data[WorkExp2] != 0){$applicants->occupExpFullM = $data[WorkExp2];}
if($data[WorkExpPro1] != 0){$applicants->occupExpProY = $data[WorkExpPro1];}
if($data[WorkExpPro2] != 0){$applicants->occupExpProM = $data[WorkExpPro2];}
}


//8. таблица с родителями
$parents = R::findAll('parents', 'applicants_id = ?', array($applicants_id)); //находим если существуют
R::trashAll($parents); // если нашли, удаляем все //я знаю про обновление, но в данном случаем оно не подходит
// отец
if($data[TypeF] != '0'){
$parent = R::dispense('parents'); // открываем для записи
$parent->sex = "m";
$parent->type = $data[TypeF]; 
$parent->lastname = $data[lastnameF];
$parent->name = $data[nameF];
$parent->middlename = $data[middlenameF];
if($data[AddressF] == "on"){$parent->address = "Same"; // возможно плохой вариант, т.к. занимает место.
}else{	$parent->address = $data[AddressAreaF];};
$parent->workPlace = $data[WorkPlaceF];
$applicants->ownParentsList[] = $parent; //связь данных об отеце с таблицей applicants 
}
//мать
if($data[TypeM] != '0'){
$parent2 = R::dispense('parents');
$parent2->sex = "f";
$parent2->type = $data[TypeM]; 
$parent2->lastname = $data[lastnameM];
$parent2->name = $data[nameM];
$parent2->middlename = $data[middlenameM];
if($data[AddressM] == "on"){$parent2->address = "Same"; // возможно плохой вариант, т.к. занимает место.
}else{	$parent2->address = $data[AddressAreaM];};
$parent2->workPlace = $data[WorkPlaceM];
$applicants->ownParentsList[] = $parent2; //связь данных об матери с таблицей applicants 
}//конец таблицы с родителями




//заявки пользователей на специальности
$contests = R::findAll('contest', 'applicants_id = ?', array($applicants_id)); //находим если существуют
R::trashAll($contests); // если нашли, удаляем все
//на бюджет
for ($i=1; $i < 1000; $i++) {
if(isset($data[specialty_free_.$i]) && $data[specialty_free_.$i] != '0'){
	$contest[$i] = R::dispense('contest');

	$contest[$i]->type = "f";
	$contest[$i]->prio = $data[prio_free_.$i];
	$contest[$i]->specialty_id = $data[specialty_free_.$i];
	$applicants->ownContestList[] = $contest[$i]; // связь с таблицей applicants
}else{
	break;
}}
//на платное
for ($i=1; $i < 1000; $i++) {
if(isset($data[specialty_paid_.$i]) && $data[specialty_paid_.$i] != '0'){
	$contest[$i] = R::dispense('contest');

	$contest[$i]->type = "p";
	$contest[$i]->prio = $data[prio_paid_.$i];
	$contest[$i]->specialty_id = $data[specialty_paid_.$i];

	$applicants->ownContestList[] = $contest[$i]; // связь с таблицей applicants
}else{
	break;
}}// end заявки пользователей на специальности


if($fill_all >= 6){
	$applicants->fillAll = true;
}else{
	$applicants->fillAll = false;
}


$users->ownApplicantsList[] = $applicants; //связь таблицы applicants с таблицей users

R::store($users);


function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

header('Location: ../pages/profile.php');


// Переводим массив в JSON
  //  echo json_encode($data); 


