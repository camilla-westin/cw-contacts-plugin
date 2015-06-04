<?php 

/*
	Template for showing a single Contact
*/
?>


<?php get_header(); ?>

	<section class="pagecontent pagecontent-contacts">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<h1 style="red"><?php the_title(); ?></h1>
		<div class="contacts-single-content"><?php the_content(); ?></p>
		 <?php $emailadress = get_post_meta($post->ID, "contactemail", true); ?>
		<div class="contacts-single-email">
			<a href="mailto:<?php echo $emailadress; ?>"><?php echo $emailadress; ?></a>
		</div>
		<div><?php echo get_post_meta($post->ID, "contactrole", true); ?></div>

	<?php endwhile; else : ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'c-framework' ); ?></p>
	<?php endif; ?>

</section>

<?php get_footer(); ?>