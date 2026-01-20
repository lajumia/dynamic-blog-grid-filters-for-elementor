<button class="mobile-filter-btn" onclick="toggleFilter()">☰ <span class="badge" id="filterCount">0</span></button>

<div class="overlay" id="overlay" onclick="closeFilter()"></div>

<div class="blog-wrapper">

    <?php 
        if ( 'yes' === $settings['enable_sidebar'] ) {
    ?>
    <aside class="sidebar" id="sidebar">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <h3 style="margin:0">Filter</h3>
            <button id="clearFilters" style="background:none;border:none;font-size:13px;cursor:pointer;padding: 16px 8px;" data-postPerPage = <?php echo esc_attr( $settings['posts_per_page'] ); ?>>Clear all</button>
        </div>

        <?php
        $categories = get_categories( [
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ] );
        ?>

        <?php 
        if ( 'yes' === $settings['enable_category_filter'] ) {
        ?>

        <div class="filter-group">
            <h4><?php esc_html_e( 'Category', 'dbgfe' ); ?></h4>

            <div class="filter-search">
                <input 
                    style="width:100%;padding:15px;"
                    type="text"
                    class="search-category"
                    placeholder="<?php esc_attr_e( 'Search category', 'dbgfe' ); ?>"
                    onkeyup="filterList(this)"
                    data-postPerPage = <?php echo esc_attr( $settings['posts_per_page'] ); ?>
                >
            </div>

            <div class="filter-list">
                <?php foreach ( $categories as $category ) : ?>
                    <label>
                        <input 
                            type="checkbox"
                            class="dbgfe-category-filter filter-checkbox"
                            value="<?php echo esc_attr( $category->term_id ); ?>"
                            data-postperpage = <?php echo esc_attr( $settings['posts_per_page'] ); ?>
                        >
                        <span class="filter-name">
                            <?php echo esc_html( $category->name ); ?>
                        </span>
                        <span class="filter-count" style="margin-left:auto;color:#999;font-size:12px">
                            <?php echo esc_html( $category->count ); ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <?php 
        }
        ?>
        

        <?php
        $tags = get_tags( [
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ] );
        ?>

        <?php 
        if ( 'yes' === $settings['enable_tags_filter'] ) {
        ?>

        <div class="filter-group">
            <h4><?php esc_html_e( 'Tags', 'dbgfe' ); ?></h4>

            <div class="filter-search">
                <input 
                    style="width:100%;padding:15px;"
                    class="search-tag"
                    type="text"
                    placeholder="<?php esc_attr_e( 'Search tag', 'dbgfe' ); ?>"
                    onkeyup="filterList(this)"
                >
            </div>

            <div class="filter-list">
                <?php foreach ( $tags as $tag ) : ?>
                    <label>
                        <input 
                            type="checkbox"
                            class="dbgfe-tag-filter filter-checkbox"
                            value="<?php echo esc_attr( $tag->term_id ); ?>"
                           data-postperpage="<?php echo esc_attr( absint( $settings['posts_per_page'] ) ); ?>"


                        >
                        <span class="filter-name">
                            <?php echo esc_html( $tag->name ); ?>
                        </span>
                        <span class="filter-count" style="margin-left:auto;color:#999;font-size:12px">
                            <?php echo esc_html( $tag->count ); ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <?php 
        }
        ?>

    </aside>

    <?php 
        }
    ?>

    <section class="blog-section">

        <div class="blog-grid" style="grid-template-columns: repeat(<?php echo esc_attr( $settings['columns'] );?>, 1fr);" id="blogGrid">

            <?php

            $post_per_page = $settings['posts_per_page'] ;
       
            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

           $args = [
                'post_type'      => 'post',
                'posts_per_page' => $post_per_page,
                'post_status'    => 'publish',
                'paged'          => $paged,
            ];


            $query = new WP_Query( $args );

            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
            ?>
            <div class="blog-card">
            
                <a href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', [
                                'alt'   => esc_attr( get_the_title() ),
                                'style' => 'width:100%; height:100px; object-fit:cover;',
                            ] ); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url( DBGFE_URL . 'assets/img/image-not-found.jpg' ); ?>" alt="<?php esc_attr_e( 'Image not found', 'dbgfe' ); ?>">
                    <?php endif; ?>
                </a>

                <div class="blog-content">
                    <h3><?php the_title(); ?></h3>

                    <p>
                        <?php echo wp_trim_words( get_the_excerpt(), 3 ); ?>
                    </p>

                    <p>
                    <a href="<?php the_permalink(); ?>" class="read-more">
                        <?php esc_html_e( 'Read More →', 'dbgfe' ); ?>
                    </a>
                </div>

            </div>

        <?php
            endwhile;
            wp_reset_postdata();
        else :
        ?>
            <p><?php esc_html_e( 'No posts found.', 'dbgfe' ); ?></p>
        <?php endif; ?>

        </div>


<?php if ( $query->max_num_pages > 1 ) : ?>
<div class="pagination" id="dbgfe-pagination">

    <!-- Previous button -->
    <a 
        href="#"
        class="page-prev <?php echo ( $paged == 1 ) ? 'disabled' : ''; ?>"
        style="display: <?php echo ( $paged == 1 ) ? 'none' : 'block'; ?>"
        data-page="<?php echo esc_attr(max(1, $paged - 1)); ?>"
        data-posts-per-page="<?php echo esc_attr($post_per_page); ?>"
        
    >
        «
    </a>

    <!-- Page numbers -->
    <?php for ( $i = 1; $i <= $query->max_num_pages; $i++ ) : ?>
        <a 
            href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>"
            class="page-number <?php echo ( $i == $paged ) ? 'active' : ''; ?>"
            data-page="<?php echo esc_attr( $i ); ?>"
            data-posts-per-page="<?php echo esc_attr($post_per_page); ?>"
            
        >
            <?php echo esc_html( $i ); ?>
        </a>
    <?php endfor; ?>

    <!-- Next button -->
    <a 
        href="<?php echo esc_url( get_pagenum_link( min($query->max_num_pages, $paged + 1) ) ); ?>"
        class="page-next <?php echo ( $paged == $query->max_num_pages ) ? 'disabled' : ''; ?>"
        data-page="<?php echo esc_attr(min($query->max_num_pages, $paged + 1)); ?>"
        data-postsPerPage="<?php echo esc_attr($post_per_page); ?>"
    >
        »
    </a>

</div>
<?php endif; ?>




    </section>
</div>
