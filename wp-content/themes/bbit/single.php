<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); bbit_views(get_the_ID()); ?>
	<div class="widget">

	<?php bbit_breadcrumbs(); ?>

    <?php get_template_part( 'includes/templates/top-content', get_post_format() ); ?>

    <div id="content" class="pad">
	    <div class="bai-viet-box">
		<div style="text-align: center;"><h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php if(get_post_meta($post->ID, 'bbit_heading', true)): ?><?php echo get_post_meta($post->ID, 'bbit_heading', true); ?><?php else: ?><?php the_title(); ?><?php endif; ?></a></h1></div>
		<br><?php the_content(); ?>
        </div>

		<div id="wp_page" class="center-title">
        <?php bbit_post_page(); ?>
				</div>
	   
	</div>
	
	<div class="tags">
		<div class="rating"><?php echo do_shortcode('[taq_review]'); ?></div>
		<div class="clearfix"></div>
		<?php //if(has_tag()||get_post_meta($post->ID, 'bbit_search_keyword', true)): ?>
		<div>Từ khóa: <?php the_tags('',', ',', '); ?></div>
		<?php //endif; ?>
	</div>
	
	<div class="content-bottom"> 
		 
			<?php previous_post_link('%link', __('&larr; %title'), $in_same_cat = true); ?> 
			<div style="float: right;"><?php next_post_link('%link', __('%title &rarr;'), $in_same_cat = true); ?></div> 
		 
	</div>
	
	</div>

<?php endwhile; endif; ?>



<div class="widget">
    <div id="sidebar-wrapper2">
	<div class="phdr">Cùng Chuyên Mục</div>
	<?php
	$categories = get_the_category($post->ID);
	if ($categories) {
		$category_ids = array();
		foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
		$args=array(
		'category__in' => $category_ids,
		'post__not_in' => array($post->ID),
		'showposts'=>5,
		'orderby' => 'rand',
		'ignore_sticky_posts'=>1
		);
		$my_query = new wp_query($args);
		if( $my_query->have_posts() ) {
			while ($my_query->have_posts()) {
			$my_query->the_post();
			get_template_part( 'includes/templates/loop', get_post_format() );
			}
		}
	}
	wp_reset_query(); ?>
	</div>
</div>	
<?php if ( $data['bbit_show_comments'] == 1 ) : comments_template( '', true ); endif; ?>

<?php 
wp_register_script( 'rate_script', get_template_directory_uri().'/assets/js/bbit.js', array( 'jquery' ) , false , false );  
wp_enqueue_script( 'rate_script' );
get_footer(); ?>