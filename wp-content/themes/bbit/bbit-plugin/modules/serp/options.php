<?php
/**
 * module return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */

$__google_locations = array(
	'com' => 'Default - Google.com',
	'com.vn' => 'Viet Nam - Google.com.vn',
);
global $bbit;
echo json_encode(
	array(
		$tryed_module['db_alias'] => array(
			/* define the form_messages box */
			'serp' => array(
				'title' 	=> 'SERP',
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> array(
					'save' => array(
						'value' => __('Save settings'),
						'color' => 'green',
						'action'=> 'bbit-saveOptions'
					)
				), // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
				
					'nbreq_max_limit' 	=> array(
						'type' 		=> 'text',
						'std' 		=> 100,
						'size' 		=> 'small',
						'title' 	=> __('Số yêu cầu tối đa:'),
						'force_width'=> '100',
						'desc' 		=> __('Max number of requests to Google API (please verify <a href="https://developers.google.com/custom-search/json-api/v1/overview?csw=1" target="_blank">Google price list</a>)'),
					),
					
					'developer_key' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Google Developer Key:'),
						'desc' 		=> __('Google Developer Key')
					),
					
					'custom_search_id' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '350',
						'title' 	=> __('Custom Search Engine ID:'),
						'desc' 		=> __('Custom Search Engine ID')
					),
					
					'google_country' 	=> array(
						'type' 		=> 'select',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '170',
						'title' 	=> __('Google locations'),
						'desc' 		=> __('All possible locations.'),
						'options' 	=> $__google_locations
					),
					
					'last_status' 	=> array(
						'type' 		=> 'textarea-array',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Request Last Status:'),
						'desc' 		=> __('Last Status retrieved from Google, for the SERP request operation')
					),
					
					'top_type' 	=> array(
						'type' 		=> 'select',
						'size' 		=> 'large',
						'title' 	=> __('Top Size:'),
						'force_width'=> '100',
						'desc' 		=> __('Check if first X google results'),
						'options'	=> $bbit->doRange( range(10, 100, 10) )
					),
					
					'cron_email' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'title' 	=> __('Email:'),
						'desc' 		=> __('Your email where you will receive cron notifications')
					),
					
					array(
						'type' 		=> 'html',
						'html' 		=> '<div style="margin: 20px 0px 20px 20px;">
							<h2>How to setup the Cron Job</h2>
							<p>WordPress comes with its own cron job that allows you to schedule your posts and events. However, in many situations, the WP-Cron is not working well and leads to posts missed their publication schedule and/or scheduled events not executed.<br>
							<span id="more-74"></span><br>
							To understand why this happen, we need to know that the WP-Cron is not a real cron job. It is in fact a virtual cron that only works when a page is loaded. In short, when a page is requested on the frontend/backend, WordPress will first load WP-Cron, follow by the necessary page to display to your reader. The loaded WP-Cron will then check the database to see if there is any thing that needs to be done.</p>
							<p>Reasons for WP-Cron to fail could be due to:</p>
							<ul>
								<li>DNS issue in the server.</li>
								<li>Plugins conflict</li>
								<li>Heavy load in the server which results in WP-Cron not executed fully</li>
								<li>WordPress bug</li>
								<li>Using of cache plugins that prevent the WP-Cron from loading</li>
								<li>And many other reasons</li>
							</ul>
							<p>There are many ways to solve the WP-Cron issue, but the one that I am going to propose here is to disable the virtual WP-Cron and use a real cron job instead.</p>
							<h3>Why use a real cron job?</h3>
							<p>By using a real cron job, you can be sure that all your scheduled items are executed. For popular blogs with high traffic, using a real cron job can also reduce the server bandwidth and reduce the chances of your server crashing, especially when you are experiencing Digg/Slashdot effect.</p>
							<h3>Scheduling a real cron job</h3>
							<p>To configure a real cron job, you will need access to your cPanel or Admin panel (we will be using cPanel in this tutorial).</p>
							<p>1. Log into your cPanel.</p>
							<p>2. Scroll down the list of applications until you see the “<em>cron jobs</em>�? link. Click on it.</p>
							<p><img width="510" height="192" class="aligncenter size-full wp-image-81" alt="wpcron-cpanel" src="{plugin_folder_uri}assets/wpcron-cpanel.png"></p>
							<p>3. Under the <em>Add New Cron Job</em> section, choose the interval that you want it to run the cron job. I have set it to run every 15minutes, but you can change it according to your liking.</p>
							<p><img width="470" height="331" class="aligncenter size-full wp-image-82" alt="wpcron-add-new-cron-job" src="{plugin_folder_uri}/assets/wpcron-add-new-cron-job.png"></p>
							<p>4. In the Command field, enter the following:</p>
						
							<div class="wp_syntax"><div class="code"><pre style="font-family:monospace;" class="bash"><span style="color: #c20cb9; font-weight: bold;">wget</span> <span style="color: #660033;">-q</span> <span style="color: #660033;">-O</span> - </span>' . ( $bbit->cfg["paths"]["plugin_dir_url"] ) . '<span style="color: #000000; font-weight: bold;"></span>do-cron.php <span style="color: #000000; font-weight: bold;">&gt;/</span>dev<span style="color: #000000; font-weight: bold;">/</span>null <span style="color: #000000;">2</span><span style="color: #000000; font-weight: bold;">&gt;&amp;</span><span style="color: #000000;">1</span></pre></div></div>
						
							<p>5. Click the “Add New Cron Job�? button. You should now see a message like this:</p>
							<p><img width="577" height="139" class="aligncenter size-full wp-image-83" alt="wpcron-current-cron-job" src="{plugin_folder_uri}/assets/wpcron-current-cron-job.png"></p>
							<p>6. Next, using a FTP program, connect to your server and download the <code>wp-config.php</code> file.</p>
							<p>7. Open the <code>wp-config.php</code> file with a text editor and paste the following line:</p>
						
							<div class="wp_syntax"><div class="code"><pre style="font-family:monospace;" class="php"><span style="color: #990000;">define</span><span style="color: #009900;">(</span><span style="color: #0000ff;">\'DISABLE_WP_CRON\'</span><span style="color: #339933;">,</span> <span style="color: #009900; font-weight: bold;">true</span><span style="color: #009900;">)</span><span style="color: #339933;">;</span></pre></div></div>
						
							<p>8. Save and upload (and replace) this file back to the server. This will disable WordPress internal cron job.</p>
							<p>That’s it.</p>
						
						
							</div>'
					)

				)
			)
		)
	)
);