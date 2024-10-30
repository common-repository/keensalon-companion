<?php

defined( 'ABSPATH' ) || exit();

/*** Instagram Feeds */
function keensalon_instagram_feeds($username, $limit) {
    $username = $username ? $username : '';
    $limit = $limit ? $limit : 12;
    $size = 'large';
    if ( '' !== $username ) :
        $media_array = keensalon_scrape_instagram($username);
        if ( is_wp_error( $media_array ) ) :
            echo '<div class="error col-md-12 col-sm-12 col-xs-12 text-center"><h4>'.wp_kses_post( $media_array->get_error_message() ).'</h4></>';
        else :
            // filter for images only?
            if ( $images_only = apply_filters( 'wpiw_images_only', false ) ) {
                $media_array = array_filter( $media_array, array( $this, 'images_only' ) );
            }
            // slice list down to required limit.
            $media_array = array_slice( $media_array, 0, $limit );
            foreach( $media_array as $item ):
                echo '<a href="' . esc_url( $item['link'] ) . '" target="_blank" class="instagram-item col-md-3 col-sm-6 col-xs-6" target="_blank">
                        <div class="media">
                            <img class="img-responsive" src="'.esc_url( $item[$size] ).'" alt="'.esc_attr( $item['description'] ).'">
                        </div>
                        <div class="content">
                            <div class="border">
                                <h5 class="title">'.keensalon_limit_text($item['description'], 30).'</h5>
                            </div>
                        </div>
                    </a>';
            endforeach;
        endif;
    endif;
}


/**
 * Instagram Scrape
 */
function keensalon_scrape_instagram( $username ) {
    $username = trim( strtolower( $username ) );
    switch ( substr( $username, 0, 1 ) ) {
        case '#':
            $url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
            $transient_prefix = 'h';
            break;
        default:
            $url              = 'https://instagram.com/' . str_replace( '@', '', $username );
            $transient_prefix = 'u';
            break;
    }
    if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {
        $remote = wp_remote_get( $url );
        if ( is_wp_error( $remote ) ) {
            return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'keensalon-companion' ) );
        }
        if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
            return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'keensalon-companion' ) );
        }
        $shards      = explode( 'window._sharedData = ', $remote['body'] );
        $insta_json  = explode( ';</script>', $shards[1] );
        $insta_array = json_decode( $insta_json[0], true );
        if ( ! $insta_array ) {
            return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'keensalon-companion' ) );
        }
        if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
            $images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
        } elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
            $images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
        } else {
            return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'keensalon-companion' ) );
        }
        if ( ! is_array( $images ) ) {
            return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'keensalon-companion' ) );
        }
        $instagram = array();
        foreach ( $images as $image ) {
            if ( true === $image['node']['is_video'] ) {
                $type = 'video';
            } else {
                $type = 'image';
            }
            $caption = __( 'Instagram Image', 'keensalon-companion' );
            if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
                $caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
            }
            $instagram[] = array(
                'description' => $caption,
                'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
                'time'        => $image['node']['taken_at_timestamp'],
                'comments'    => $image['node']['edge_media_to_comment']['count'],
                'likes'       => $image['node']['edge_liked_by']['count'],
                'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
                'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
                'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
                'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
                'type'        => $type,
            );
        } // End foreach().
        // do not set an empty transient - should help catch private or empty accounts.
        if ( ! empty( $instagram ) ) {
            $instagram = base64_encode( serialize( $instagram ) );
            set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
        }
    }
    if ( ! empty( $instagram ) ) {
        return unserialize( base64_decode( $instagram ) );
    } else {
        return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'keensalon-companion' ) );
    }
}
