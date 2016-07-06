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
        'html_validation' => ($bbit->get_plugin_status() != 'valid_hash' ? array(
        	'validation' => array(
            'title' => 'Unlock - Bbit Plugin',
            'icon' => '{plugin_folder_uri}assets/validation_icon.png',
            'size' => 'grid_4', // grid_1|grid_2|grid_3|grid_4
            'header' => true, // true|false
            'toggler' => false, // true|false
            'buttons' => false, // true|false
            'style' => 'panel', // panel|panel-widget
            // create the box elements array
            'elements' => array(
                array(
                    'type' => 'message',
                    'status' => 'info',
                    'html' => 'Bạn cần kích hoạt them bằng API key Mobigate. Truy cập <a href="http://mobigate.vn/tai-khoan">http://mobigate.vn/tai-khoan</a> để lấy API Key, theme chỉ hoạt động khi có API'
                ),
                'productKey' => array(
                    'type' => 'text',
                    'std' => '',
                    'size' => 'big',
                    'title' => 'API Key',
                    'desc' => 'Bạn cần kích hoạt them bằng API key Mobigate. Truy cập <a href="http://mobigate.vn/tai-khoan">http://mobigate.vn/tai-khoan</a> để lấy API Key, theme chỉ hoạt động khi có API'
                ),
                'yourEmail' => array(
                    'type' => 'text',
                    'std' => get_option('admin_email'),
                    'size' => 'big',
                    'title' => 'Email của bạn',
                    'desc' => 'Nhận thông báo cập nhật qua email này.'
                ),
                'sendActions' => array(
                    'type' => 'buttons',
                    'options' => array(
                        array(
                            'action' => 'bbit_activate_product',
                            'width' => '100px',
                            'type' => 'submit',
                            'color' => 'green',
                            'pos' => 'left',
                            'value' => 'Kích Hoạt'
                        )
                    )
                )
            )
        ))
        // else
        : $bbitDashboard->getBoxes()
    )
));