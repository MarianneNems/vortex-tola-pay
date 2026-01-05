<?php
/**
 * Plugin Name: Vortex Crypto Payment Gateway
 * Plugin URI: https://github.com/MarianneNems/Vortexartec-ai-art-gen-web3-engine
 * Description: WooCommerce payment gateway for USDC cryptocurrency payments on Solana blockchain. TOLA incentive system for rewards.
 * Version: 4.1.0
 * Author: Vortex AI Team
 * Author URI: https://vortexartec.com
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * Text Domain: vortex-crypto-payment
 * Domain Path: /languages
 * 
 * @package VortexAIEngine
 * 
 * Changelog v4.1.0:
 * - PRIMARY: USDC payment gateway (user-facing)
 * - SECONDARY: TOLA incentive system (hidden rewards)
 * - Integrated with Vortex USDC Transaction Manager
 * - Compatible with vortex-ai-engine main plugin
 * - Real-time blockchain verification
 * - Non-custodial architecture
 */

if (!defined('ABSPATH')) {
    exit;
}

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}

/**
 * Add Vortex Crypto Payment gateway to WooCommerce
 */
add_filter('woocommerce_payment_gateways', 'vortex_add_crypto_payment_gateway');

function vortex_add_crypto_payment_gateway($gateways) {
    $gateways[] = 'WC_Gateway_Vortex_Crypto';
    return $gateways;
}

/**
 * Initialize Vortex Crypto Payment gateway
 */
add_action('plugins_loaded', 'vortex_init_crypto_payment_gateway');

function vortex_init_crypto_payment_gateway() {
    
    class WC_Gateway_Vortex_Crypto extends WC_Payment_Gateway {
        
        public function __construct() {
            $this->id = 'vortex_crypto_pay';
            $this->icon = '';
            $this->has_fields = true;
            $this->method_title = 'USDC Cryptocurrency';
            $this->method_description = 'Accept USDC stablecoin payments via Solana blockchain. Fast, secure, and transparent.';
            
            // Load settings
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->engine_url = $this->get_option('engine_url');
            $this->enabled = $this->get_option('enabled');
            
            // Save settings
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            
            // Custom payment page
            add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));
        }
        
        /**
         * Admin settings fields
         */
        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => 'Enable/Disable',
                    'type' => 'checkbox',
                    'label' => 'Enable USDC Crypto Payments',
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => 'Title',
                    'type' => 'text',
                    'description' => 'Payment method title shown to customers',
                    'default' => 'USDC Cryptocurrency',
                    'desc_tip' => true
                ),
                'description' => array(
                    'title' => 'Description',
                    'type' => 'textarea',
                    'description' => 'Payment method description shown to customers',
                    'default' => 'Pay securely with USDC stablecoin on Solana blockchain. Fast, secure, 1:1 USD value.',
                    'desc_tip' => true
                ),
                'engine_url' => array(
                    'title' => 'Railway Backend URL',
                    'type' => 'text',
                    'description' => 'Vortex Engine Railway URL (e.g., https://vortex-engine.railway.app)',
                    'default' => get_option('vortex_railway_backend_url', 'https://vortex-engine.railway.app'),
                    'desc_tip' => true
                ),
                'treasury_wallet' => array(
                    'title' => 'Treasury Wallet Address',
                    'type' => 'text',
                    'description' => 'Solana wallet address to receive USDC payments',
                    'default' => '',
                    'desc_tip' => true
                ),
                'usdc_contract' => array(
                    'title' => 'USDC Contract Address',
                    'type' => 'text',
                    'description' => 'Solana USDC SPL token address',
                    'default' => 'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v',
                    'desc_tip' => true
                )
            );
        }
        
        /**
         * Process payment
         */
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            
            // Mark as pending payment
            $order->update_status('pending', 'Awaiting USDC cryptocurrency payment');
            
            // Add order note
            $order->add_order_note('USDC payment initiated via Solana blockchain');
            
            // Reduce stock
            wc_reduce_stock_levels($order_id);
            
            // Remove cart
            WC()->cart->empty_cart();
            
            // Return to receipt page
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true)
            );
        }
        
        /**
         * Receipt page - show QR code and payment instructions
         */
        public function receipt_page($order_id) {
            $order = wc_get_order($order_id);
            
            if (!$order) {
                return;
            }
            
            // Get payment intent from engine
            $intent = $this->get_payment_intent($order_id);
            
            if (!$intent) {
                echo '<p>Error creating payment intent. Please contact support.</p>';
                return;
            }
            
            // Display payment interface
            ?>
            <div class="vortex-crypto-payment" style="max-width: 600px; margin: 40px auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 40px; text-align: center; color: white; margin-bottom: 30px; box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);">
                    <h2 style="margin: 0 0 20px 0; font-size: 32px; font-weight: 800;">Complete Your Payment</h2>
                    <div style="font-size: 48px; font-weight: 900; margin: 20px 0;">
                        <?php echo number_format($intent['amountUSDC'] ?? $intent['amountTOLA'] ?? $order->get_total(), 2); ?> USDC
                    </div>
                    <div style="font-size: 18px; opacity: 0.9;">
                        ≈ $<?php echo number_format($order->get_total(), 2); ?> USD
                    </div>
                </div>
                
                <div style="background: white; border: 2px solid #e2e8f0; border-radius: 16px; padding: 30px; margin-bottom: 30px;">
                    <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">Scan with Phantom Wallet</h3>
                    
                    <!-- QR Code -->
                    <div id="vortex-qr-code" style="text-align: center; margin-bottom: 20px;">
                        <canvas id="qr-canvas"></canvas>
                    </div>
                    
                    <!-- Or Open in Phantom -->
                    <div style="text-align: center;">
                        <a href="<?php echo esc_url($intent['deepLink']); ?>" 
                           style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #AB9FF2 0%, #5B4FDD 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 16px;">
                            Open in Phantom Wallet
                        </a>
                    </div>
                </div>
                
                <div style="background: #f0fdf4; border: 2px solid #86efac; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                    <div style="font-weight: 700; color: #166534; margin-bottom: 10px;">Payment Instructions:</div>
                    <ol style="margin: 0; padding-left: 20px; color: #166534;">
                        <li style="margin-bottom: 8px;">Scan QR code with Phantom wallet app</li>
                        <li style="margin-bottom: 8px;">Or click "Open in Phantom Wallet" button</li>
                        <li style="margin-bottom: 8px;">Approve the transaction in Phantom</li>
                        <li style="margin-bottom: 8px;">Wait for confirmation (usually 1-2 seconds)</li>
                        <li>Order will be processed automatically</li>
                    </ol>
                </div>
                
                <div id="payment-status" style="text-align: center; padding: 20px; background: #fef3c7; border: 2px solid #fbbf24; border-radius: 12px;">
                    <div style="font-weight: 600; color: #92400e;">Waiting for payment...</div>
                    <div style="font-size: 14px; color: #78350f; margin-top: 8px;">This page will update automatically when payment is received</div>
                </div>
                
            </div>
            
            <!-- QR Code Library -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
            
            <script>
            (function() {
                // Generate QR Code
                new QRCode(document.getElementById('qr-canvas'), {
                    text: '<?php echo esc_js($intent['qrData']); ?>',
                    width: 256,
                    height: 256,
                    colorDark: '#1e293b',
                    colorLight: '#ffffff'
                });
                
                // Poll for payment status
                const orderId = <?php echo intval($order_id); ?>;
                const engineUrl = '<?php echo esc_js($this->engine_url); ?>';
                
                const pollInterval = setInterval(function() {
                    fetch(engineUrl + '/tola/payments/status/' + orderId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data && data.data.status === 'paid') {
                                clearInterval(pollInterval);
                                
                                document.getElementById('payment-status').innerHTML = `
                                    <div style="font-weight: 700; color: #166534; font-size: 20px;">Payment Received!</div>
                                    <div style="font-size: 14px; color: #15803d; margin-top: 8px;">Redirecting to confirmation page...</div>
                                `;
                                
                                setTimeout(function() {
                                    window.location.href = '<?php echo esc_url($order->get_checkout_order_received_url()); ?>';
                                }, 2000);
                            }
                        })
                        .catch(error => {
                            console.error('[Vortex Crypto Payment] Poll error:', error);
                        });
                }, 3000); // Poll every 3 seconds
                
                // Stop polling after 10 minutes
                setTimeout(function() {
                    clearInterval(pollInterval);
                }, 600000);
                
            })();
            </script>
            <?php
        }
        
        /**
         * Get payment intent from engine
         */
        private function get_payment_intent($order_id) {
            $order = wc_get_order($order_id);
            
            if (!$order) {
                return false;
            }
            
            // Call engine to create payment intent
            $response = wp_remote_post($this->engine_url . '/wc/webhooks/order-created', array(
                'body' => json_encode(array(
                    'id' => $order_id,
                    'total' => $order->get_total(),
                    'payment_method' => 'tola_pay',
                    'billing' => array(
                        'email' => $order->get_billing_email()
                    ),
                    'line_items' => array_map(function($item) {
                        return array(
                            'product_id' => $item->get_product_id(),
                            'name' => $item->get_name(),
                            'quantity' => $item->get_quantity(),
                            'total' => $item->get_total()
                        );
                    }, $order->get_items())
                )),
                'headers' => array(
                    'Content-Type' => 'application/json'
                ),
                'timeout' => 30
            ));
            
            if (is_wp_error($response)) {
                error_log('[Vortex Crypto Payment] Engine request error: ' . $response->get_error_message());
                return false;
            }
            
            $body = json_decode(wp_remote_retrieve_body($response), true);
            
            if (!$body || !$body['success']) {
                error_log('[Vortex Crypto Payment] Engine response error');
                return false;
            }
            
            return $body['data'];
        }
        
        /**
         * Integration with Vortex USDC Transaction Manager
         */
        public function credit_usdc_to_account($user_id, $amount, $order_id) {
            // Check if USDC Transaction Manager is available
            if (class_exists('Vortex_USDC_Transaction_Manager')) {
                $transaction_manager = Vortex_USDC_Transaction_Manager::get_instance();
                $result = $transaction_manager->add_usdc(
                    $user_id,
                    $amount,
                    "WooCommerce Order #{$order_id}",
                    ['order_id' => $order_id, 'source' => 'woocommerce_payment']
                );
                
                if (!is_wp_error($result)) {
                    error_log("[Vortex Crypto Payment] Credited {$amount} USDC to user {$user_id}");
                    return true;
                }
            }
            
            return false;
        }
    }
}

error_log('[Vortex Crypto Payment] Payment gateway loaded successfully');

