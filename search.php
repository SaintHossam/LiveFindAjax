<?php get_header(); ?>

<section class="content">
    <div class="container">
        <h1 class="sec-tit text-center">
            <?php printf(__('نتيجة البحث عن : %s ', 'saint'), '<i>' . get_search_query() . '</i>'); ?>
        </h1>

        <?php

        $query = new WP_Query(array(
            'post_type' => 'product',
            's' => get_search_query()
        ));
        ?>

        <?php if ( $query->have_posts() ) : ?>
            <div class="row">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php
                    global $product;
                    $product = wc_get_product(get_the_ID());
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-item">
                            <div class="product-thumb position-relative">
                                <?php if ( $product->is_on_sale() ) : ?>
                                    <div class="discount-badge position-absolute top-0 start-0">
                                        <?php
                                        $regular_price = $product->get_regular_price();
                                        $sale_price = $product->get_sale_price();
                                        if ( $regular_price > 0 ) {
                                            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                                            echo "-{$discount}%";
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <div class="wishlist-button position-absolute top-0 end-0">
                                    <?php echo do_shortcode('[ti_wishlists_addtowishlist]'); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>">
                                    <?php echo $product->get_image(); ?>
                                </a>
                            </div>

                            <div class="product-body text-center mt-3">
                                <h3 class="product-title mb-2">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <div class="price mb-2">
                                    <?php if ( $product->is_on_sale() ) : ?>
                                        <span class="price-new"><?php echo wc_price( $product->get_sale_price() ); ?></span>
                                        <span class="price-old"><del><?php echo wc_price( $product->get_regular_price() ); ?></del></span>
                                    <?php else : ?>
                                        <span class="price-regular"><?php echo wc_price( $product->get_price() ); ?></span>
                                    <?php endif; ?>
                                </div>

                                <form class="cart" method="post" enctype="multipart/form-data">
                                    <button type="submit" name="add-to-cart"
                                            value="<?php echo esc_attr($product->get_id()); ?>"
                                            class="btn btn-primary add-to-cart-button">
                                        <span class="text"><?php _e('أضف إلى السلة', 'saint') ?></span>
                                        <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php wp_pagenavi(); ?>

        <?php else : ?>
            <p><?php _e('عذراً، لا توجد منتجات مطابقة لبحثك.', 'saint'); ?></p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</section>

<?php get_footer(); ?>
