<?php
/**
 * Blockchain Sync Integration for Vortex Crypto Payment
 * Version: 4.0.0
 * 
 * Integrates vortex-tola-pay with blockchain wallet sync system
 * Triggers balance refresh after USDC purchases
 * Ensures real-time balance updates
 */

if (!defined('ABSPATH')) {
    exit;
}

class Vortex_Payment_Blockchain_Sync {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Hook into WooCommerce order completion
        add_action('woocommerce_payment_complete', array($this, 'sync_after_payment'), 10, 1);
        add_action('woocommerce_order_status_completed', array($this, 'sync_after_completion'), 10, 1);
        
        // Hook into Vortex USDC transfers
        add_action('vortex_usdc_transfer_complete', array($this, 'sync_after_usdc_transfer'), 10, 3);
        
        // Hook into TOLA incentive minting
        add_action('vortex_tola_minted', array($this, 'sync_after_tola_mint'), 10, 3);
    }
    
    /**
     * Sync balance after WooCommerce payment
     */
    public function sync_after_payment($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        
        // Only for crypto payment gateway
        if ($order->get_payment_method() !== 'vortex_crypto_pay') {
            return;
        }
        
        $user_id = $order->get_customer_id();
        if (!$user_id) {
            return;
        }
        
        error_log('[PAYMENT BLOCKCHAIN SYNC v4.0.0] Payment complete, triggering balance sync for user: ' . $user_id);
        
        // Trigger blockchain sync
        $this->trigger_balance_sync($user_id, 'payment_complete');
    }
    
    /**
     * Sync balance after order completion
     */
    public function sync_after_completion($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        
        $user_id = $order->get_customer_id();
        if (!$user_id) {
            return;
        }
        
        error_log('[PAYMENT BLOCKCHAIN SYNC v4.0.0] Order completed, syncing balance for user: ' . $user_id);
        
        // Trigger blockchain sync
        $this->trigger_balance_sync($user_id, 'order_complete');
    }
    
    /**
     * Sync after USDC transfer from vortex-engine
     */
    public function sync_after_usdc_transfer($user_id, $amount, $tx_signature) {
        error_log(sprintf(
            '[PAYMENT BLOCKCHAIN SYNC v4.0.0] USDC transfer complete | User: %d | Amount: %.2f | TX: %s',
            $user_id,
            $amount,
            $tx_signature
        ));
        
        // Wait 3 seconds for blockchain confirmation
        wp_schedule_single_event(time() + 3, 'vortex_delayed_balance_sync', array($user_id, 'usdc_transfer'));
    }
    
    /**
     * Sync after TOLA incentive minting
     */
    public function sync_after_tola_mint($user_id, $amount, $tx_signature) {
        error_log(sprintf(
            '[PAYMENT BLOCKCHAIN SYNC v4.0.0] TOLA minted | User: %d | Amount: %.2f | TX: %s',
            $user_id,
            $amount,
            $tx_signature
        ));
        
        // Wait 3 seconds for blockchain confirmation
        wp_schedule_single_event(time() + 3, 'vortex_delayed_balance_sync', array($user_id, 'tola_mint'));
    }
    
    /**
     * Trigger balance sync via JavaScript
     */
    private function trigger_balance_sync($user_id, $trigger_reason) {
        // Store sync flag in user meta
        update_user_meta($user_id, '_vortex_needs_balance_sync', true);
        update_user_meta($user_id, '_vortex_sync_trigger', $trigger_reason);
        update_user_meta($user_id, '_vortex_sync_timestamp', current_time('mysql'));
        
        // If user is currently online, trigger immediate sync
        // This would be picked up by the JavaScript on page refresh
        error_log('[PAYMENT BLOCKCHAIN SYNC v4.0.0] Balance sync triggered for user: ' . $user_id);
    }
}

// Initialize
Vortex_Payment_Blockchain_Sync::get_instance();

// Handle delayed balance sync
add_action('vortex_delayed_balance_sync', function($user_id, $trigger_reason) {
    error_log('[PAYMENT BLOCKCHAIN SYNC v4.0.0] Delayed sync executing for user: ' . $user_id);
    update_user_meta($user_id, '_vortex_needs_balance_sync', true);
    update_user_meta($user_id, '_vortex_sync_trigger', $trigger_reason);
}, 10, 2);

