<?php 

/*
	Template for showing a single Contact
*/
?>


<?php get_header(); ?>

	<section class="pagecontent pagecontent-contacts">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<h1><?php the_title(); ?></h1>
		<div class="contacts-single-content"><?php the_content(); ?></p>

	<?php endwhile; else : ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'c-framework' ); ?></p>
	<?php endif; ?>

</section>

<?php get_footer(); ?>