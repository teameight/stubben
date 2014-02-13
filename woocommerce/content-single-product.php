<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php 
	global $product;
	extract(etheme_get_single_product_sidebar());
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class('single-product-page'); ?>>
	
	<div class="row product-info sidebar-position-<?php echo $position; ?> responsive-sidebar-<?php echo $responsive; ?>">

		<?php if($single_product_sidebar && ($position == 'left' || ($responsive == 'top' && $position == 'right'))): ?>
			<div class="span3 sidebar sidebar-left single-product-sidebar">
				<?php et_product_brand_image(); ?>
				<?php if(etheme_get_option('upsell_location') == 'sidebar') woocommerce_upsell_display(); ?>
				<?php dynamic_sidebar('single-sidebar'); ?>
			</div>
		<?php endif; ?>

		<div class="span<?php echo $images_span; ?>">
			<?php woocommerce_show_product_images(); ?>
		</div>
		<div class="span<?php echo $meta_span; ?> product_meta">
			<?php if (etheme_get_option('show_name_on_single')): ?>
				<h2 class="product-name"><?php the_title(); ?></h2>
			<?php endif ?>
			
			<h4><?php _e('Product Information', ETHEME_DOMAIN) ?></h4>
			
			<?php woocommerce_template_loop_rating(); ?>


			<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option( 'woocommerce_enable_sku' ) == 'yes' && $product->get_sku() ) : ?>
				<span itemprop="productID" class="sku_wrapper"><?php _e( 'Product code', ETHEME_DOMAIN ); ?>: <span class="sku"><?php echo $product->get_sku(); ?></span></span>
			<?php endif; ?>
			
			<?php
				$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
				echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $size, ETHEME_DOMAIN ) . ' ', '.</span>' );
			?>
			
			<?php woocommerce_template_single_price(); ?>

			
			<?php woocommerce_template_single_excerpt(); ?>

            <div class="short-description">
            <?php
                $attributes = get_post_meta($post->ID, '_product_attributes', true);
                foreach($attributes as $attribute) {
                    echo '<p>'.$attribute['name'].'s : '.$attribute['value'];
                }
            ?>
            </div>

		    <?php if ( etheme_get_custom_field('size_guide_img') ) : ?>
		    	<?php $lightbox_rel = (get_option('woocommerce_enable_lightbox') == 'yes') ? 'prettyPhoto' : 'lightbox'; ?>
		        <div class="size_guide">
		    	 <a rel="<?php echo $lightbox_rel; ?>" href="<?php etheme_custom_field('size_guide_img'); ?>"><?php _e('SIZING GUIDE', ETHEME_DOMAIN); ?></a>
		        </div>
		    <?php endif; ?>	

			<?php woocommerce_template_single_add_to_cart(); ?>

			<?php woocommerce_template_single_meta(); ?>
            
            <?php if(etheme_get_option('share_icons')) echo do_shortcode('[share text="'.get_the_title().'"]'); ?>
            
			<?php woocommerce_template_single_sharing(); ?>
				
		</div>

		<?php if($single_product_sidebar && ($position == 'right' || ($responsive == 'bottom' && $position == 'left'))): ?>
			<div class="span3 sidebar sidebar-right single-product-sidebar">
				<?php et_product_brand_image(); ?>
				<?php if(etheme_get_option('upsell_location') == 'sidebar') woocommerce_upsell_display(); ?>
				<?php dynamic_sidebar('single-sidebar'); ?>
			</div>
		<?php endif; ?>

	</div>
	
	<?php

        $args = array(
            'post_type' => 'etheme_portfolio',
            'meta_query' => array(
                array(
                'key' => 'products_used',
                'value' => $post->ID,
                'compare' => 'LIKE'
                )
            )
        );
		$riders = get_posts($args);

        if(!empty($riders)) {

        echo '<h4>Riders who like '.get_the_title().'</h4>';

        foreach($riders as $rider) {
//            var_dump($rider);
            $link = get_post_permalink($rider->ID);
            if(has_post_thumbnail($rider->ID)) echo '<a href="'.$link.'">'.get_the_post_thumbnail($rider->ID, "thumbnail").'</a>';
        }

        }

		if(etheme_get_custom_field('additional_block') != '') {
			echo '<div class="sidebar-position-without">';
			et_show_block(etheme_get_custom_field('additional_block'));
			echo '</div>';
		} 

	  	if(etheme_get_option('upsell_location') == 'after_content') woocommerce_upsell_display();
	  	
		woocommerce_output_related_products();
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>