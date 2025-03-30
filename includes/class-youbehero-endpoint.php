<?php
if (!defined('ABSPATH')) {
    exit;
}

class YouBeHero_Endpoint_Schema extends WooCommerce\Blocks\StoreApi\Schemas\AbstractSchema {
    /**
     * The schema item name.
     *
     * @var string
     */
    protected $title = 'youbehero';

    /**
     * The schema item identifier.
     *
     * @var string
     */
    const IDENTIFIER = 'youbehero';

    /**
     * Endpoint schema properties.
     *
     * @return array
     */
    public function get_properties() {
        return [
            'message' => [
                'description' => __('Custom message from YouBeHero endpoint', 'youbehero'),
                'type' => 'string',
                'context' => ['view', 'edit'],
                'readonly' => true,
            ],
            'timestamp' => [
                'description' => __('Server timestamp', 'youbehero'),
                'type' => 'string',
                'context' => ['view', 'edit'],
                'readonly' => true,
            ],
        ];
    }

    /**
     * Get the response for the endpoint.
     *
     * @param array $data Prepared data.
     * @return array Response data.
     */
    public function get_item_response($data) {
        return [
            'message' => $data['message'] ?? '',
            'timestamp' => $data['timestamp'] ?? '',
        ];
    }
}

class YouBeHero_Endpoint_Route extends WooCommerce\Blocks\StoreApi\Routes\AbstractRoute {
    /**
     * The route's namespace.
     *
     * @var string
     */
    protected $namespace = 'wc/store';

    /**
     * The route's path.
     *
     * @var string
     */
    protected $path = '/youbehero';

    /**
     * Get arguments for the endpoint.
     *
     * @return array
     */
    public function get_args() {
        return [
            'param' => [
                'description' => __('Example parameter', 'youbehero'),
                'type' => 'string',
                'required' => false,
                'validate_callback' => function($param) {
                    return is_string($param);
                },
            ],
        ];
    }

    /**
     * Handle the request and return a valid response.
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response
     */
    public function get_response(WP_REST_Request $request) {
        try {
            // Process request parameters
            $param = $request->get_param('param');

            // Your custom logic here
            $data = [
                'message' => $param ? "You sent: $param" : 'You be hero!',
                'timestamp' => current_time('mysql'),
            ];

            return rest_ensure_response($data);
        } catch (Exception $e) {
            return new WP_Error(
                'youbehero_error',
                $e->getMessage(),
                ['status' => 400]
            );
        }
    }
}