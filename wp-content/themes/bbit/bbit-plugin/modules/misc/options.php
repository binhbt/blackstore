<?php
/**
 * module return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */
global $bbit;
echo json_encode(
	array(
		$tryed_module['db_alias'] => array(
			/* define the form_messages box */
			'misc' => array(
				'title' 	=> __('Miscellaneous', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
					/* Slug Optimizer */
					array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<p><strong>Slug Optimizer</strong> removes common words from the slug of a post or page</p>
							<ul>
								<li><u>Info</u></li>
								<li>- <i>post or page slug definition:</i> the part of post or page URL that is based on its title; in WordPress edit page, the slug is the yellow highlighted part of the permalink just under the title textbox.</li>
								<li>- <i>why use Slug Optimizer:</i> because it increases keyword potency because there are less words in your URLs so their relevance is greater.</li>
								<li>- <i>keep slug unchanged:</i> if every word in your post or page title is in the list of words to be removed or doesn\'n have the necessary limit of minimum characters (but this is a rare case), PSP Slug Optimizer will not remove the words, because you would end up with a blank slug.</li>
								<li>- <i>manually edit slug:</i> PSP Slug Optimizer will not remove words from a manually edited slug.</li>
								<li>- <i>revert to optimized slug:</i> if after editing your slug, you want to come back to the optimized slug (made from post title), you must erase the content of the slug textbox and click save.</li>
							</ul>
						', $bbit->localizationName),
					),
					'slug_isactive' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Activate Slug Optimizer:', $bbit->localizationName),
						'desc' 		=> '&nbsp;',
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					'slug_stop_words' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Stop Words List:', $bbit->localizationName),
						'desc' 		=> __('The list of stop words (comma separated)', $bbit->localizationName),
						'height'	=> '200px'
					),
					'slug_min_chars'=> array(
						'type' 		=> 'text',
						'std' 		=> '3',
						'size' 		=> 'large',
						'force_width'=> '50',
						'title' 	=> __('Slug part min chars:', $bbit->localizationName),
						'desc' 		=> __('The minimum number of characters for every slug part!', $bbit->localizationName)
					),

					/* Insert Code Content */
					array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<p id="insert-code"><strong>Insert Code</strong> in the WP Header of Footer of your page.</a></p>
						', $bbit->localizationName),
					),
					'insert_code_isactive' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Activate Insert Code:', $bbit->localizationName),
						'desc' 		=> '&nbsp;',
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					'insert_code_head' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Insert code in &lt;head&gt;:', $bbit->localizationName),
						'desc' 		=> __('Insert code in &lt;head&gt;', $bbit->localizationName),
						'height'	=> '200px'
					),
					'insert_code_footer' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Insert code in wp footer:', $bbit->localizationName),
						'desc' 		=> __('Insert code in wp footer', $bbit->localizationName),
						'height'	=> '200px'
					)
				)
			)
			
		)
	)
);