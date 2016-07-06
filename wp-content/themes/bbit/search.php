<?php get_header(); ?>
<div class="widget">
<div class="labelfirstposts">
	<div class="phdr">Tìm kiếm
	<?php if (have_posts()) : ?>
	<span class="center-title">Kết quả tìm kiếm cho "<strong><?php echo get_query_var('s'); ?></strong>"</span></div>

	<?php while (have_posts()) : the_post(); get_template_part( 'includes/templates/loop', get_post_format() ); endwhile; ?>

    <div class="xem-them"><?php bbit_pagination($pages = '', $range = 1); ?></div>

	<?php else : ?>

	<span class="center-title">Không thể tìm thấy "<strong><?php echo get_query_var('s'); ?></strong>"</span>

	<?php endif; ?>

	<span class="title">Tìm kiếm chính xác hơn với</span>
	<script>
	(function() {
    var cx = '012532440225590168675:ctj5f0h8jkc';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
	})();
	</script>
	<gcse:search></gcse:search>
	<article>
		<p>Trên đây là kết quả tìm kiếm cho <strong><?php echo get_query_var('s'); ?></strong> bằng công cụ tìm kiếm của TaiChoi.com.</p>
		<p>Nếu chưa tìm được nội dung mong muốn, hãy sử dụng công cụ tìm kiếm dưới đây.</p>
	</article>
	</div></div>
<?php get_footer(); ?>