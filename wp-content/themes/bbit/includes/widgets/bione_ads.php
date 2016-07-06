<?php
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

add_action('widgets_init', 'bione_ads_load_widgets');
function bione_ads_load_widgets(){ register_widget('bione_ads_Widget'); }
class bione_ads_Widget extends WP_Widget {

	function bione_ads_Widget()
	{
		$widget_ops = array('classname' => 'bione_ads', 'description' => 'Kéo thả để tạo mục bài mới với khả năng tùy chỉnh đa dạng');
		$control_ops = array('id_base' => 'bione_ads-widget');
		$this->WP_Widget('bione_ads-widget', 'Bione Box Ads', $widget_ops, $control_ops);
	}
	
function widget($args, $instance)
{
global $post;
extract($args);
$ads = $instance['ads'];
$ads2 = $instance['ads2'];
$ads3 = $instance['ads3'];
$ads4 = $instance['ads4'];
$ads5 = $instance['ads5'];
$categories = $instance['categories'];
$postnum = $instance['postnum'];
echo $before_widget; ?>
    <span class="title">Cập nhật mới</span>
    <?php if ($ads): ?><article class="format-thuthuat"><h2><?php echo $ads; ?></h2></article><?php endif; ?>
    <?php if ($ads2): ?><article class="format-thuthuat"><h2><?php echo $ads2; ?></h2></article><?php endif; ?>
    <?php if ($ads3): ?><article class="format-thuthuat"><h2><?php echo $ads3; ?></h2></article><?php endif; ?>
    <?php if ($ads4): ?><article class="format-thuthuat"><h2><?php echo $ads4; ?></h2></article><?php endif; ?>
    <?php if ($ads5): ?><article class="format-thuthuat"><h2><?php echo $ads5; ?></h2></article><?php endif; ?>
	<?php $recent_posts = new WP_Query(array( 'showposts' => $postnum, 'cat' => $categories, ));
	while($recent_posts->have_posts()): $recent_posts->the_post();
	get_template_part( 'includes/templates/loop');
	endwhile; ?>
    <span class="center-title readmore"><a href="<?php echo get_category_link($categories); ?>">Xem thêm</a></span>
<?php
echo $after_widget;
}
	
function update($new_instance, $old_instance)
{
$instance = $old_instance;
$instance['ads'] = $new_instance['ads'];
$instance['ads2'] = $new_instance['ads2'];
$instance['ads3'] = $new_instance['ads3'];
$instance['ads4'] = $new_instance['ads4'];
$instance['ads5'] = $new_instance['ads5'];
$instance['categories'] = $new_instance['categories'];
$instance['postnum'] = $new_instance['postnum'];
return $instance;
}

function form($instance)
{
$defaults = array('ads' => 'Tiêu đề mục','ads2' => 'Tiêu đề mục','ads3' => 'Tiêu đề mục','ads4' => 'Tiêu đề mục','ads5' => 'Tiêu đề mục','categories' => 'all', 'postnum' => 5);
$instance = wp_parse_args((array) $instance, $defaults); ?>
	<p>
		<label for="<?php echo $this->get_field_id('ads'); ?>">Tiêu đề:</label>
		<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ads'); ?>" name="<?php echo $this->get_field_name('ads'); ?>" value="<?php echo $instance['ads']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('ads2'); ?>">Tiêu đề:</label>
		<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ads2'); ?>" name="<?php echo $this->get_field_name('ads2'); ?>" value="<?php echo $instance['ads2']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('ads3'); ?>">Tiêu đề:</label>
		<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ads3'); ?>" name="<?php echo $this->get_field_name('ads3'); ?>" value="<?php echo $instance['ads3']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('ads4'); ?>">Tiêu đề:</label>
		<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ads4'); ?>" name="<?php echo $this->get_field_name('ads4'); ?>" value="<?php echo $instance['ads4']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('ads5'); ?>">Tiêu đề:</label>
		<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('ads5'); ?>" name="<?php echo $this->get_field_name('ads5'); ?>" value="<?php echo $instance['ads5']; ?>" />
	</p>	
	<p>
		<label for="<?php echo $this->get_field_id('categories'); ?>">Hiển thị bài viết trong:</label> 
		<select id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
			<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>tất cả các mục</option>
			<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
			<?php foreach($categories as $category) { ?>
			<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
			<?php } ?>
		</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('postnum'); ?>">Số bài hiển thị:</label>
		<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('postnum'); ?>" name="<?php echo $this->get_field_name('postnum'); ?>" value="<?php echo $instance['postnum']; ?>" />
	</p>
<?php }} ?>