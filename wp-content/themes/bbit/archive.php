<?php get_header(); ?>
  <div id="show_post-2" class="widget widget_show_post">
	<?php bbit_breadcrumbs(); ?>

	<?php if( is_tag() && get_query_var('paged') == 0 ): ?>
	<article class="details">
		<?php if( get_tag_meta('wpseo_timeline') ) : ?>
		<img src="<?php echo get_tag_meta('wpseo_timeline'); ?>" class="timeline" alt="Ảnh bìa <?php single_tag_title(); ?>">
		<?php endif; ?>

		<?php if( tag_description() ) : ?>
		<div class="pad"><?php echo tag_description(); ?></div>	
		<?php endif; ?>
	</article>
	<span class="center-title green-title margin5">Danh sách bài viết</span>
	<?php endif; ?>

    <?php while (have_posts()) : the_post(); get_template_part( 'includes/templates/loop', get_post_format() ); endwhile; ?>
	<div class="xem-them">
    <?php bbit_pagination($pages = '', $range = 1); ?>
	</div>
	<?php if(is_category()) : if(category_has_children() || category_has_parent(get_query_var('cat'))) : ?>
	<div class="nav_menu margin5">
		<div class="phdr">DANH MỤC CON</div>
	 <?php if(category_has_children()) {//Nếu danh mục có con
			$subcategories = get_categories('hide_empty=0&child_of='.get_query_var('cat'));
			foreach ($subcategories as $subcategory) { ?>
			<div class="vvip">
				<a href="<?php echo get_category_link($subcategory->term_id) ?>"><?php echo apply_filters('get_term', $subcategory->name) ?><span class="right count"><?php echo apply_filters('get_term', $subcategory->count) ?></span></a>
			</div>
			<?php }
		}
		elseif(category_has_parent(get_query_var('cat'))) {//Nếu danh mục có mẹ
			$subcategories = get_categories('hide_empty=0&child_of='.get_parent_category_id());
			foreach ($subcategories as $subcategory) { ?>
			<div class="vvip">
				<a href="<?php echo get_category_link($subcategory->term_id) ?>"><?php echo apply_filters('get_term', $subcategory->name) ?><span class="right count"><?php echo apply_filters('get_term', $subcategory->count) ?></span></a>
			</div>
			<?php } 
		} 
	 ?>
		
	</div>	
	<?php endif; endif; ?>
</div>
<?php get_footer(); ?>