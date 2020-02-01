<?php /* Template Name: CustTemplate */ ?>
<?php
/**
 * Custom Landing Page Template Shows desired Landing Page
 *
 */

get_header(); 
?>

	<?php wp_enqueue_script('jquery');?>
	<?php wp_enqueue_script('landingpageJS');?>
	<!-- Plugin enabled  <div> template active</div> //-->
	<div class="col-md-2 reNavigationBlock" id='reNavigationBlock0'>
		<div style="<?php echo get_option("block0css"); ?>">
			<div class="navBlockImg">
				<img src="<?php echo plugins_url('blank.jpg',__FILE__); ?>" class="bottom" />
				<img src="<?php echo plugins_url('blank2.jpg',__FILE__); ?>" class="top" />
			</div>
			<div>
				<h3><?php echo get_option('block0Name'); ?></h3>
				<?php wp_nav_menu( array('theme_location' => 'block-menu0')); ?>
			</div>
		</div>
	</div>
	<div class="col-md-2 reNavigationBlock" id='reNavigationBlock1'>
		<div style="<?php echo get_option('block0css'); ?>">
			<div class="navBlockImg">
				<img src='<?php echo plugins_url('blank.jpg',__FILE__); ?>' class="bottom" />
				<img src='<?php echo plugins_url('blank2.jpg',__FILE__); ?>' class="top" />
			</div>
			<div>
				<h3><?php echo get_option('block1Name');?></h3>
				<?php wp_nav_menu( array('theme_location' => 'block-menu1')); ?>
			</div>
		</div>
	</div>
	<div class="col-md-2 reNavigationBlock" id='reNavigationBlock2'>
		<div style="<?php echo get_option('block2css');?>">
		<img src='<?php echo plugins_url('blank.jpg',__FILE__); ?>' class='reNavigationBlockImage' id='reNavigationBlock2Image' />
		</div>
		<div>
		<h3><?php echo get_option('block2Name');?></h3>
		<?php wp_nav_menu( array('theme_location' => 'block-menu2')); ?>
		</div>
	</div>
	<div class="col-md-2 reNavigationBlock" id='reNavigationBlock3'>
		<div style="<?php echo get_option('block3css');?>">
		<img src='<?php echo plugins_url('blank.jpg',__FILE__); ?>' class='reNavigationBlockImage' id='reNavigationBlock3Image' />
		</div>
		<div>
		<h3><?php echo get_option('block3Name');?></h3>
		<?php wp_nav_menu( array('theme_location' => 'block-menu3')); ?>
		</div>
	</div>

	<div id="primary" <?php wen_associate_content_class( 'content-area' ); ?> >
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
/**
 * wen_associate_action_sidebar hook
 *
 * @hooked: wen_associate_add_sidebar - 10
 *
 */
do_action( 'wen_associate_action_sidebar' );?>

<?php get_footer(); ?>
