<?php
require('../libs/db.php');
require('../libs/tfpdf/tfpdf.php');

$applicants = R::getRow('SELECT * FROM applicants WHERE users_id=?', array($_SESSION['logged_user']->id));
$parents = R::getAll('SELECT * FROM parents WHERE applicants_id=?', array($applicants[id]));//2 массива на выходе
$contest = R::getAll('SELECT * FROM contest WHERE applicants_id=?', array($applicants[id]));//несколько массивов на выходе

$pdf = new tFPDF();
$pdf->AddPage();

// Add a Unicode font (uses UTF-8)
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',12);

$pdf->MultiAlignCell( 95, 5, '
Допустить к вступительным испытаниям и участию в конкурсе

Допустить к участию в конкурсе
(нужное подчеркнуть)
Ректор (начальник) __________________
__ __________________ г.
', 0, 0 );

$pdf->MultiAlignCell( 95, 5, '
Зачислить на __________ курс
на факультет ____________________________
_______________________________________
специальность (направление специальности, специализацию) _________________________
_______________________________________
_______________________________________
Приказ ___ __________________ г. № ______
Ректор (начальник) ______________________
', 0, 0 );

$pdf->Ln(60); //добавить счетчик специальностей, это число зависит от количества специальностей, чтобы все влезли
$pdf->Write(7,'Ректору (начальнику)'); $pdf->SetFont('DejaVu','U',12); $pdf->Write(7, '              Полесского Государственного университета                         ');
$pdf->SetFont('DejaVu','',12); $pdf->Write(7,'
от '); $pdf->SetFont('DejaVu','U',12); $pdf->Write(7, '          '.$_SESSION['logged_user']->lastname.' '.$_SESSION['logged_user']->name.' '.$_SESSION['logged_user']->middlename.'       ');
$pdf->SetFont('DejaVu','',12); $pdf->Write(7,'
который(ая) проживает по адресу: '); $pdf->SetFont('DejaVu','U',12); $pdf->Write(7, '       '.$applicants[addr_postal_code].', '.$applicants[addr_country].', '.R::getCell( 'SELECT name FROM ref_settlement_types WHERE settlement_type_id = ? LIMIT 1', array($applicants[settlement_type_id])).' '.$applicants[addr_settlement].', '.$applicants[addr_oblast].' область, '.R::getCell( 'SELECT name FROM ref_street_types WHERE street_type_id = ? LIMIT 1', array($applicants[street_type_id])).' '.$applicants[addr_street].', '.$applicants[addr_street_nr].', моб.'.$applicants[phone_mobile].', тел.'.$applicants[phone_home].'          '); //дописать квартиру и корпус

//в связи с ограниченностю врмени и неуверенностью что эта функция вообще нужна, остальная часть заявления вставляется в незаполненном виде из текстового файла.

$pdf->SetFont('DejaVu','',12);
$pdf->Ln(6);

// Load a UTF-8 string from a file and print it
$txt = file_get_contents('../libs/tfpdf/application.txt'); 
$pdf->Write(6,$txt);

/*
// Select a standard font (uses windows-1252)
$pdf->SetFont('Arial','',14);
$pdf->Ln(100);
$pdf->Write(5,'The file size of this PDF is only 13 KB.');*/

$pdf->Output();
?>

