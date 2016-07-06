<?php get_header(); ?>
<div class="post" id="post-977">
    <?php if (have_posts()) : while (have_posts()) : the_post(); bbit_views(get_the_ID()); ?>
	<div class="widget-content2 popular-posts">
    <?php bbit_breadcrumbs(); ?>

    <div class="bai-viet-box">
    <br><?php the_content(); ?><br>	
    </div>

	</div>
    <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>