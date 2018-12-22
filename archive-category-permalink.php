<?php
// 
add_filter( 'rewrite_rules_array', function( $rules ) {
    $new_rules = array();
    $terms = get_terms( array(
        'taxonomy'   => 'product_cat',
        'post_type'  => 'product',
        'hide_empty' => false,
    ));
    if ( $terms && ! is_wp_error( $terms ) ) {
        $siteurl = esc_url( home_url( '/' ) );
        foreach ( $terms as $term ) {
            $term_slug = $term->slug;
            $baseterm = str_replace( $siteurl, '', get_term_link( $term->term_id, 'product_cat' ) );
            // rules for a specific category
            $new_rules[$baseterm .'?$'] = 'index.php?product_cat=' . $term_slug;
            // rules for a category pagination
            $new_rules[$baseterm . '/page/([0-9]{1,})/?$' ] = 'index.php?product_cat=' . $term_slug . '&paged=$matches[1]';
            $new_rules[$baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?product_cat=' . $term_slug . '&feed=$matches[1]';
        }
    }

    return $new_rules + $rules;
} );

/**
 * Flush rewrite rules when create new term
 * need for a new product category rewrite rules
 */
function imp_create_term() {
    flush_rewrite_rules(false);;
}
add_action( 'create_term', 'imp_create_term' );