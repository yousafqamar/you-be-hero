<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://youbehero.com
 * @since      1.0.0
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/includes
 */

class YouBeHero_API_Handler {

    private $api_base_url;
    private $bearer_token;
    private $mock = 'yes';
    private $api_json;

    /**
     * Constructor - Initializes API URL and Token.
     */
    public function __construct() {

        $this->api_base_url = 'https://your-api-url.com/v1/'; // Change this to your actual API URL
        $this->bearer_token = get_option( 'youbehero_api_token' ); // Fetch stored token from DB

        $this->api_json = $this->mock == 'yes' ? $this->ybh_get_predefined_json() : $this->get( 'fix_price' );

    }

    /**
     * Send a GET request to the API.
     *
     * @param string $endpoint API endpoint (relative path).
     * @param array $query_params Optional query parameters.
     * @return array|WP_Error API response or error.
     */
    public function get( $endpoint, $query_params = [] ) {

        $url = add_query_arg( $query_params, trailingslashit( $this->api_base_url ) . $endpoint );

        $response = wp_remote_get( $url, [
            'headers' => $this->get_headers(),
            'timeout' => 15,
        ] );

        return $this->handle_response( $response );

    }

    /**
     * Send a POST request to the API.
     *
     * @param string $endpoint API endpoint (relative path).
     * @param array $body Request body.
     * @return array|WP_Error API response or error.
     */
    public function post( $endpoint, $body = [] ) {

        $url = trailingslashit( $this->api_base_url ) . $endpoint;

        $response = wp_remote_post( $url, [
            'headers' => $this->get_headers(),
            'body'    => json_encode( $body ),
            'timeout' => 15,
        ] );

        return $this->handle_response( $response );

    }

    /**
     * Generate API headers including the Authorization token.
     *
     * @return array Headers array.
     */
    private function get_headers() {

        return [
            'Authorization' => 'Bearer ' . $this->bearer_token,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];

    }

    /**
     * Handle API response and return data.
     *
     * @param WP_Error|array $response API response.
     * @return array|WP_Error Decoded JSON or error.
     */
    private function handle_response( $response ) {

        if ( is_wp_error( $response ) ) {

            return $response; // Return WP_Error if request failed.

        }

        $body = wp_remote_retrieve_body( $response );
        $decoded_response = json_decode( $body, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {

            return new WP_Error( 'api_json_error', __( 'Invalid JSON response from API.', 'youbehero' ) );

        }

        if ( wp_remote_retrieve_response_code( $response ) >= 400 ) {

            return new WP_Error( 'api_http_error', __( 'API Error: ', 'youbehero' ) . $decoded_response['message'], $decoded_response );

        }

        return $decoded_response;

    }

    /**
     * @return string
     */
    private function ybh_get_predefined_json() {

        // dummy response json here
        return '{
            "success": true,
            "data": {
                "shop_id": 2523,
                "eshop_logo": null,
                "company_name": "fern.com",
                "url": "https:\/\/fern.com",
                "status": "acitve",
                "total_credits": 50,
                "payment_count": 1,
                "selected_causes": [{
                    "id": 264,
                    "name": "50 και Ελλάς",
                    "url": "https:\/\/youbehero.com\/gr\/cause\/50kai-ellas",
                    "image": "https:\/\/youbehero.com\/images\/cause\/264\/l\/50kai_logo.png",
                    "website": "https:\/\/www.50plus.gr\/",
                    "social_links": {
                        "facebook": "https:\/\/www.facebook.com\/50plushellas",
                        "twitter": "https:\/\/twitter.com\/50plusHellas",
                        "youtube": "https:\/\/www.youtube.com\/channel\/UCv2Yj96ri3GLMcQVU3i5CTg",
                        "linkedin": null,
                        "google": null,
                        "instagram": "https:\/\/www.instagram.com\/50plushellas\/",
                        "tumblr": null,
                        "pinterest": null
                    }
                }, {
                    "id": 120,
                    "name": "APICCO -Community Based Art",
                    "url": "https:\/\/youbehero.com\/gr\/cause\/apicco-community-based-art",
                    "image": "https:\/\/youbehero.com\/images\/cause\/120\/l\/Apicco-logo.jpeg",
                    "website": "http:\/\/www.apicco.net\/",
                    "social_links": {
                        "facebook": "https:\/\/www.facebook.com\/ApiccoCommunity",
                        "twitter": null,
                        "youtube": "https:\/\/www.youtube.com\/channel\/UCHZRa09HqC8e1xqPmYwGlGg?view_as=subscriber",
                        "linkedin": "https:\/\/www.linkedin.com\/company\/apicco-community-based-art\/",
                        "google": null,
                        "instagram": "https:\/\/www.instagram.com\/apicco_prison_project\/",
                        "tumblr": null,
                        "pinterest": null
                    }
                }, {
                    "id": 330,
                    "name": "Σύλλογος Αγία Σοφία",
                    "url": "https:\/\/youbehero.com\/gr\/cause\/sillogos-goneon-kai-kidemonon-paidion-me-siggenis-kardiopathies-i-agia-sofia",
                    "image": "https:\/\/youbehero.com\/images\/cause\/330\/l\/agia-sofia-logo.png",
                    "website": null,
                    "social_links": {
                        "facebook": "https:\/\/www.facebook.com\/profile.php?id=61555629857310",
                        "twitter": "https:\/\/twitter.com\/pedocardio?lang=el",
                        "youtube": null,
                        "linkedin": null,
                        "google": null,
                        "instagram": null,
                        "tumblr": null,
                        "pinterest": null
                    }
                }, {
                    "id": 17,
                    "name": "Look to the Stars",
                    "url": "https:\/\/youbehero.com\/gr\/cause\/looktothestars",
                    "image": "https:\/\/youbehero.com\/images\/cause\/17\/l\/looktothestars_logo.jpeg",
                    "website": "https:\/\/looktothestars.gr\/",
                    "social_links": {
                        "facebook": "https:\/\/www.facebook.com\/looktothestars7\/",
                        "twitter": "https:\/\/twitter.com\/looktothestars9\/",
                        "youtube": "https:\/\/www.youtube.com\/channel\/UCgUrlps4qRt9U2bGv40-gaQ\/",
                        "linkedin": null,
                        "google": "https:\/\/plus.google.com\/u\/0\/112749431543258923190",
                        "instagram": "https:\/\/www.instagram.com\/looktothestars7\/",
                        "tumblr": null,
                        "pinterest": null
                    }
                }],
                "donation_schedule": "{\"start\":{\"date\":\"2025-01-17\",\"time\":\"12:00\"},\"end\":{\"endDate\":null,\"endTime\":null}}",
                "donation_settings": {
                    "donor": "customer", /*or eshop*/
                    "donationType": "fixed", /*or roundup, or percentage*/
                    "fixed_amounts": {
                        "A": "0,50",
                        "B": "1,00",
                        "C": "2,00"
                    },
                    "fixed_amount": null,
                    "fixedPercentage": "25.00"
                },
                "widget_configurations": {
                    "product_page": {
                        "product_page": {
                            "active": true,
                            "theme": "discreet",
                            "position": "below",
                            "background_color": "#f4f4f4",
                            "text_color": "#2d2d2d",
                            "display": "analytical",
                            "icon": "generic",
                            "border": false,
                            "border_color": "#040706",
                            "border_radius": "bigBorderRadius",
                            "margin": "smallMargin",
                            "padding": "smallPadding"
                        }
                    },
                    "checkout_page": {
                        "checkout_page": {
                            "theme": "discreet",
                            "position": "over",
                            "background_color": "#f7f7f7",
                            "text_color": "#1b2b23",
                            "btn_color": "#30942e",
                            "display": "short",
                            "border": false,
                            "border_color": "#b53a6a",
                            "border_radius": "bigBorderRadius",
                            "margin": "bigMargin",
                            "padding": "midPadding"
                        }
                    },
                    "confirmation_page": {
                        "confirmation_page": {
                            "active": true,
                            "theme": "light",
                            "background_color": "#ffffff",
                            "plaisio_color": "#f7f7f7",
                            "text_color": "#212121",
                            "display": "short",
                            "border": true,
                            "border_color": "#e0e0e0",
                            "border_radius": "bigBorderRadius",
                            "margin": "bigMargin",
                            "padding": "midPadding"
                        }
                    },
                    "confirmation_email": {
                        "confirmation_email": {
                            "active": true,
                            "theme": "fancy",
                            "background_color": "#fefefe",
                            "text_color": "#424242",
                            "display": "short",
                            "border": true,
                            "border_color": "#e1e1e1",
                            "border_radius": "midBorderRadius",
                            "margin": "bigMargin",
                            "padding": "bigPadding"
                        }
                    },
                    "generic_marketing": {
                        "generic_marketing": {
                            "theme": "discreet",
                            "background_color": "#fefcfc",
                            "text_color": "#474747",
                            "display": "short",
                            "icon": "generic",
                            "border": false,
                            "border_color": "#92f8fa",
                            "border_radius": "bigBorderRadius",
                            "margin": "smallMargin",
                            "padding": "smallPadding"
                        }
                    }
                },
                "eshop_details": null
            }
        }';

    }

}
