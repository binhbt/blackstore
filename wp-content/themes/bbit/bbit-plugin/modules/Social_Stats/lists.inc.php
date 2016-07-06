<?php

global $bbit;

$bbit_socialsharing_position = array();
$bbit_socialsharing_position['horizontal'] = array(
	'left'			=> __('Left', $bbit->localizationName),
	'right'			=> __('Right', $bbit->localizationName),
	'center'		=> __('Center', $bbit->localizationName)
);
$bbit_socialsharing_position['vertical'] = array(
	'top'			=> __('Top', $bbit->localizationName),
	'bottom'		=> __('Bottom', $bbit->localizationName),
	'center'		=> __('Center', $bbit->localizationName)
);

$bbit_socialsharing_margin = array(
	'horizontal'	=> __('Horizontal', $bbit->localizationName),
	'vertical'		=> __('Vertical', $bbit->localizationName)
);

$bbit_socialsharing_opt = array();
$bbit_socialsharing_opt['btnsize'] = array(
	'normal'		=> __('Normal', $bbit->localizationName),
	'large'			=> __('Large', $bbit->localizationName)
);
$bbit_socialsharing_opt['viewcount'] = array(
	'no'			=> __('No', $bbit->localizationName),
	'yes'			=> __('Yes', $bbit->localizationName)
);
$bbit_socialsharing_opt['withtext'] = array(
	'no'			=> __('No', $bbit->localizationName),
	'yes'			=> __('Yes', $bbit->localizationName)
);
$bbit_socialsharing_opt['withmore'] = array(
	'no'			=> __('No', $bbit->localizationName),
	'yes'			=> __('Yes', $bbit->localizationName)
);

$bbit_socialsharing_opt['contact'] = array(
	'text_print'	=> array( 'title' => __('Print text', $bbit->localizationName), 'std' => __('Print', $bbit->localizationName) ),
	'text_email'	=> array( 'title' => __('Email text', $bbit->localizationName), 'std' => __('Email', $bbit->localizationName) ),
	'email'			=> array( 'title' => __('Email address', $bbit->localizationName), 'std' => __('', $bbit->localizationName) )
);

$bbit_socialsharing_exclude = array(
	'include'		=> array( 'title' => __('Include only', $bbit->localizationName), 'std' => __('', $bbit->localizationName), 'desc' => __('Include only: the exclusive post, pages IDs list where you want the social share toolbar to appear (separate IDs by ,)', $bbit->localizationName) ),
	'exclude'		=> array( 'title' => __('Exclude', $bbit->localizationName), 'std' => __('', $bbit->localizationName), 'desc' => __('Exclude: the post, pages IDs list where you don\'t want the social share toolbar to appear (separate IDs by ,)', $bbit->localizationName) )
);

$bbit_socialsharing_design['background_color'] = array( 'title' => __('Background color', $bbit->localizationName), 'std' => __('', $bbit->localizationName) );

$bbit_socialsharing_design['make_floating'] = array(
	'no'			=> __('No', $bbit->localizationName),
	'yes'			=> __('Yes', $bbit->localizationName)
);

$bbit_socialsharing_design['floating_beyond_content'] = array(
	'no'			=> __('No', $bbit->localizationName),
	'yes'			=> __('Yes', $bbit->localizationName)
);