<?php

/*
	Template Name: Contacts
*/

get_header(); ?>


<?php
	// To loop trough the contacts custom post type
	$args = array(
		'post_type'  => 'contacts'
		);
	$query = new WP_Query( $args );
?>



<section class="pagecontent pagecontent-contacts">
	<?php if ( $query ->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>

	<div class="contacts-item">	
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div class="contact-image"><?php the_post_thumbnail(); ?></div>
		<div class="contacts-content"><?php the_content(); ?></div> 

		<div><?php echo get_post_meta($post->ID, "contactrole", true); ?></div>

		 <?php $emailadress = get_post_meta($post->ID, "contactemail", true); ?>
			<div class="contacts-page-email">
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
	<?php endwhile; endif; wp_reset_postdata(); ?>

</section>

<?php get_footer(); ?>