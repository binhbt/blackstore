<?php
/**
* Return as json_encode
* http://bbit.vn
* ======================
*
* @author		Pham Quang Bao
* @version		1.0
*/
global $bbit;
$bbitDashboard = bbitDashboard::getInstance();
echo json_encode(array(
    $tryed_module['db_alias'] =
        'html_validation' => ( $bbitDashboard->getBoxes() )
));