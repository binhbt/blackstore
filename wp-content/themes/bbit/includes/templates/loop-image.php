<article class="format-image">
	<?php if ( is_search() || is_tag() ): global $post; $category = get_the_category($post->ID); ?>
	<span class="prefix">
	<?php 
	if($category[0]->category_parent) : echo get_cat_name($category[0]->category_parent);
	else : echo $category[0]->name; 
	endif;
	?>
	</span>
	<?php endif; ?>
    <a href="<?php the_permalink(); ?>" title="<?php get_the_title(); ?>">
    <img src="<?php echo first_image(); ?>" alt="<?php the_title(); ?>"></a>
    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	<span class="icon_view"><?php echo get_bbit_views(get_the_ID()); ?></span>
	<?php if ( comments_open() ) : ?><span class="icon_comment"><?php comments_number(__('0'), __('1'), __('%'));?></span><?php endif; ?>
</article>