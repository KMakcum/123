<?php
/**
 * Created by PhpStorm.
 * User: ispiritum
 * Date: 11.07.16
 * Time: 11:33
 */
?>
<div class="content-grid">
   <h2><a href="<?php the_permalink() ?>"> <?php the_title(); ?></a></h2>
	<p class="date"><?php the_date() ?></p>
	<p><?php the_excerpt() ?></p>
</div>