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
			'seo_friendly_images' => array(
				'title' 	=> __('SEO Friendly Images', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
					array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<h2>Thiết Lập Cơ Bản</h2>
							<p>Tự động thêm thuộc tính alt và title vào tất cả ảnh của bạn nếu nó chưa có 2 thuộc tính này</p>
							<h3>Các thẻ Alt Rewriter</h3>
							<ul>
								<li><code>{focus_keyword}</code> - replaces with your Focus Keywords (if you have)</li>
								<li><code>{title}</code> - thay thế với tiêu đề trang/bài viết</li>
								<li><code>{image_name}</code> - thay thế với phần tên ảnh (không có đuôi file) (without extension)</li>
								<li><code>{nice_image_name}</code> - thay thế với phần tên ảnh (không có đuôi file) định dạng đẹp. Tự động loại bỏ các kí tự đặc biệt</li>
							</ul><br />', $bbit->localizationName),
					),
						
					'image_alt' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Image Alternate text:', $bbit->localizationName),
						'desc' 		=> __('Hình ảnh của bạn sẽ có dạng. &lt;img src=&quot;images/&quot; width=&quot;&quot; height=&quot;&quot; <strong>alt=&quot;your_alt&quot;</strong>&gt;', $bbit->localizationName)
					),
					
					'image_title' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Image  Title text:', $bbit->localizationName),
						'desc' 		=> __('Your images title text attribute. &lt;img src=&quot;images/&quot; width=&quot;&quot; height=&quot;&quot; <strong>title=&quot;your_alt&quot;</strong>&gt;', $bbit->localizationName)
					),
					
					'keep_default_alt' => array(
						'type' 		=> 'select',
						'std' 		=> 'yes',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Giữ các thẻ alt', $bbit->localizationName),
						'desc' 		=> __('Chọn YES để bỏ qua những hình ảnh đã có alt.', $bbit->localizationName),
						'options'	=> array(
							'yes' => __('YES', $bbit->localizationName),
							'no' => __('NO', $bbit->localizationName)
						)
						
					),
					
					'keep_default_title' => array(
						'type' 		=> 'select',
						'std' 		=> 'yes',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Giữ các thẻ title', $bbit->localizationName),
						'desc' 		=> __('Chọn YES để bỏ qua những hình ảnh đã có title', $bbit->localizationName),
						'options'	=> array(
							'yes' => __('YES', $bbit->localizationName),
							'no' => __('NO', $bbit->localizationName)
						)
					),
				)
			)
		)
	)
);