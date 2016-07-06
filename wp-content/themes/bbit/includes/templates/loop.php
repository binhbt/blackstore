
<?php if(in_category('Thủ Thuật')||in_category('Tin Tức')): ?>
<article class="format-thuthuat">
    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a><i> - <?php echo get_bbit_views(get_the_ID()); ?> lượt xem</i></h2>
</article> 
<?php elseif(in_category(813) or cat_is_ancestor_of(813, $cat) or is_category(813)): ?>
	<?php if(is_category('Truyện Cười')): ?>
<article class="fun-story">
	<?php if ( is_search() || is_tag() ): ?><span class="prefix"><?php global $post; $category = get_the_category($post->ID); echo $category[0]->name; ?></span><?php endif; ?>
	<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
	<?php the_content(); ?>
</article>
	<?php else : ?>
<article class="format-story">
    <h2>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php the_title(); ?><?php if(get_post_meta($post->ID, 'bbit_author2', true)): ?> - <?php echo get_post_meta($post->ID, 'bbit_author2', true); ?><?php endif; ?>
		</a>
	</h2>
</article>
	<?php endif; ?>
<?php else: ?>
 
	<?php if ( is_search() || is_tag() ): global $post; $category = get_the_category($post->ID); ?>
	<?php endif; ?>
	
	<div class="vapkbox">
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
	
	<?php if(get_post_meta($post->ID, 'bbit_thumb', true)) : ?>
        <img width="70" height="70" src="<?php echo get_post_meta($post->ID, 'bbit_thumb', true); ?>" class="thumb_duoi wp-post-image" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
	<?php else: ?>
	<?php the_post_thumbnail('thumbnail', array('class'=>'photo' , 'alt' => get_the_title() )); ?>	
	<?php endif; ?>
	
	<div class="vpreview">
	<div class="tenbaiviet">
    <h2><?php the_title(); ?></h2>
	</a>
	</div>
	
	<div class="benduoi">
	<span class="icon_view"><?php echo get_bbit_views(get_the_ID()); ?></span>
	<?php if ( comments_open() ) : ?><?php endif; ?>
	<?php if (in_category('Game Online')) : ?><span class="icon_phone"><?php echo get_post_meta($post->ID, 'bbit_support', true); ?></span><?php endif; ?>
	<?php if(get_post_meta($post->ID, 'bbit_link_download', true) && ! is_search()): ?><?php if(!is_tag()): ?>
	<a href="<?php echo get_post_meta($post->ID, 'bbit_link_download', true); ?>" rel="nofollow" class="re_download">&nbsp;</a>
    <?php endif; ?><?php endif; ?>   
	</div>
	<div class="clear"></div>
	</div>
	

<?php endif; ?>
</div>
