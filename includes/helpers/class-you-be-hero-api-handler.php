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

    /**
     * Constructor - Initializes API URL and Token.
     */
    public function __construct() {

        $this->api_base_url = 'https://your-api-url.com/v1/'; // Change this to your actual API URL
        $this->bearer_token = get_option( 'youbehero_api_token' ); // Fetch stored token from DB

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
}
