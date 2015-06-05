<?php 

/*
	Template for showing a single Contact
*/
?>


<?php get_header(); ?>

	<section class="pagecontent pagecontent-contacts">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<h1 style="red"><?php the_title(); ?></h1>

		<div class="contact-image"><?php the_post_thumbnail(); ?></div>

		<div class="contacts-single-content"><?php the_content(); ?></p>

		<div><?php echo get_post_meta($post->ID, "contactrole", true); ?></div>

		<?php $emailadress = get_post_meta($post->ID, "contactemail", true); ?>
			<div class="contacts-single-email">
				<a href="mailto:<?php echo $emailadress; ?>"><?php echo $emailadress; ?></a>
			</div>

		<div><?php echo get_post_meta($post->ID, "contactphone", true); ?></div>

		<?php 
		$contact_twitterlink = get_post_meta($post->ID, "contacttwitter", true);
		if(!empty($contact_twitterlink)) {  
			echo '<div class="contact-twitter"><a href="';
			echo $contact_twitterlink; 
			echo'">Twitter</a></div>';
		}


		$contact_linkedinlink = get_post_meta($post->ID, "contactlinkedin", true);
		if(!empty($contact_linkedinlink)) { 
			echo '<div class="contact-linkedin"><a href="';
			echo $contact_linkedinlink; 
			echo '">Linkedin</a></div>';
		} else {
			echo '';
		}

		$contact_facebooklink = get_post_meta($post->ID, "contactfacebook", true);

		if(!empty($contact_facebooklink)) {
			echo '<div class="contact-facebook"><a href="';
			echo $contact_facebooklink;
			echo '">Facebook</a>';
		} else {
			echo '';
		}



		?>
		
		</div>

	<?php endwhile; else : ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'c-framework' ); ?></p>
	<?php endif; ?>

</section>

<?php get_footer(); ?>