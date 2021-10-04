<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>
<?php if (!is_front_page()): ?>
<div class="content">
	<h1><?php the_title(); ?></h1>
	<p class="date"><?php the_date() ?></p>
	<p><?php the_content() ?></p>
</div>
<?php endif;?>
