# Vortex Crypto Payment Gateway v4.1.0

**WooCommerce payment gateway for USDC cryptocurrency payments**

---

## Overview

Complete payment gateway allowing customers to pay with:
- **USDC** - Primary stablecoin for all purchases (1 USDC = $1 USD)
- **Backend TOLA** - Hidden incentive system (rewards)

Integrates with WooCommerce checkout and connects to Solana blockchain for real-time payment verification.

**Integrated with:** Vortex USDC Transaction Manager for seamless balance management.

---

## Features

### Primary Payment Method:
- ✅ **USDC** (Stablecoin - user-facing, 1:1 USD value)

### Backend Integration:
- ✅ Vortex USDC Transaction Manager
- ✅ TOLA incentive rewards (hidden)
- ✅ Automatic balance crediting

### Payment Flow:
1. Customer selects "USDC Cryptocurrency" at checkout
2. System generates QR code + Phantom deep link
3. Customer pays USDC with Phantom wallet
4. On-chain verification via Solana RPC
5. USDC credited to customer account
6. Order auto-completes after confirmation
7. **BONUS:** Customer earns hidden TOLA rewards for purchase!

### Security:
- Real-time blockchain verification
- On-chain transaction confirmation
- Treasury wallet validation
- HMAC webhook security

---

## Installation

### Requirements:
- WordPress 5.8+
- WooCommerce 5.0+
- PHP 7.4+
- Solana wallet (Phantom/Solflare)

### Setup:

1. **Rename Plugin Folder:**
   ```
   OLD: wp-content/plugins/vortex-tola-pay/
   NEW: wp-content/plugins/vortex-crypto-payment/
   ```

2. **Upload/Activate:**
   ```
   WordPress Admin → Plugins → Activate "Vortex Crypto Payment Gateway"
   ```

3. **Configure:**
   ```
   WooCommerce → Settings → Payments → USDC Cryptocurrency
   
   Required Settings:
   - Railway Backend URL: https://vortex-engine.railway.app
   - Treasury Wallet: Your Solana wallet address (receives USDC)
   - USDC Contract: EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v
   - Enable: Yes
   ```

4. **Integration:**
   ```
   This plugin works with:
   - Vortex USDC Transaction Manager (for balance management)
   - Vortex Railway Backend (for blockchain operations)
   - Vortex AI Engine main plugin (for incentive rewards)
   ```

---

## Configuration

### Required Settings:

**Railway Backend URL:**
- Production: `https://vortex-engine.railway.app`
- Development: `http://localhost:3000`

**Treasury Wallet:**
- Solana wallet address to receive USDC payments
- Must have USDC token account configured

**USDC Contract:**
- Mainnet: `EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v`
- This is the official Solana USDC SPL token address

**Payment Currency:**
- Primary: USDC (all customer payments)
- Backend: TOLA (incentive rewards - automatic)

---

## Integration with Vortex Engine

This plugin requires the [vortex-engine backend](https://github.com/MarianneNems/vortex-engine) to be deployed.

### API Endpoints Used:

```
POST /api/usdc/transfer - Transfer USDC tokens
GET /api/usdc/balance/:wallet - Check USDC balance
POST /wc/webhooks/order-created - Order webhook
POST /wc/webhooks/order-paid - Payment webhook
GET /tola/payments/status/:order_id - Check payment status

Backend Only (TOLA Incentives):
POST /api/tola/incentive - Distribute TOLA rewards (hidden)
GET /api/tola/balance/:wallet - Check TOLA incentive balance (hidden)
```

---

## Usage

### Customer Experience:

1. Add products to cart
2. Proceed to checkout
3. Select "TOLA/USDC Pay" payment method
4. Click "Place Order"
5. Scan QR code or click Phantom link
6. Approve transaction in wallet
7. Wait for blockchain confirmation
8. Order automatically completed

### Admin Experience:

- Orders show payment status
- Transaction signatures stored in order meta
- View on Solscan via order notes
- Automatic order completion

---

## Database

### Order Meta Keys:

```php
_usdc_payment_intent_id - Payment intent ID
_usdc_transaction_signature - Blockchain TX signature
_usdc_payment_status - Payment status
_usdc_wallet_address - Customer wallet
_usdc_amount - Amount paid in USDC
_payment_currency - USDC
_vortex_transaction_id - Link to vortex_transactions table
```

### Integration with Vortex System:

When payment completes:
1. ✅ Credits USDC to user via Vortex_USDC_Transaction_Manager
2. ✅ Records in wp_vortex_transactions table
3. ✅ Awards hidden TOLA incentive (optional)
4. ✅ Updates order status to 'completed'
5. ✅ Sends confirmation emails

---

## Webhooks

### WooCommerce Webhooks:

**Order Created:**
- URL: `{engine_url}/wc/webhooks/order-created`
- Topic: Order created
- Action: Creates payment intent

**Order Status Changed:**
- URL: `{engine_url}/wc/webhooks/order-paid`
- Topic: Order status changed
- Action: Verifies payment on-chain

---

## Troubleshooting

### Payment Not Completing:

1. Check transaction on Solscan
2. Verify engine URL is correct
3. Check treasury wallet has token accounts
4. Review WordPress debug.log
5. Check Railway logs for backend errors

### Balance Not Showing:

1. Verify wallet connected
2. Check Solana RPC connection
3. Verify token mint addresses correct
4. Check for JavaScript console errors

---

## Development

### Local Testing:

```bash
# Start vortex-engine locally
cd vortex-engine
npm run dev

# Configure plugin to use localhost
Engine URL: http://localhost:3000
```

### Debug Mode:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

// Check logs
tail -f wp-content/debug.log | grep "TOLA PAY"
```

---

## Changelog

### v4.1.0 (January 5, 2026)
- **MAJOR UPDATE:** Renamed from "TOLA Pay" to "Vortex Crypto Payment"
- **PRIMARY CURRENCY:** USDC (stablecoin, user-facing)
- **SECONDARY:** TOLA (hidden incentive system)
- Integrated with Vortex_USDC_Transaction_Manager
- Credits USDC directly to user accounts
- Compatible with vortex-ai-engine v4.0.0
- Updated UI/UX to show USDC
- Enhanced Railway backend integration
- Auto-rewards TOLA incentives (hidden)

### v4.0.0 (January 4, 2026)
- Added USDC support alongside TOLA
- Updated for v4.0.0 architecture
- Improved blockchain verification
- Enhanced security features
- Real-time balance checking

### v1.0.0 (Initial Release)
- Basic TOLA payment gateway
- QR code generation
- Phantom wallet integration

---

## Security

### Best Practices:

- ✅ Never store private keys in WordPress
- ✅ Use environment variables in vortex-engine
- ✅ Enable SSL/HTTPS
- ✅ Validate all webhooks with HMAC
- ✅ Rate limit API endpoints
- ✅ Monitor transaction logs

---

## Support

**Issues:** https://github.com/MarianneNems/vortex-tola-pay/issues  
**Engine:** https://github.com/MarianneNems/vortex-engine  
**Main Repo:** https://github.com/MarianneNems/Vortexartec-ai-art-gen-web3-engine

---

## License

Proprietary - Vortex AI Engine v4.0.0

---

## Related Projects

- **vortex-engine:** Backend API for blockchain operations
- **Vortexartec-ai-art-gen-web3-engine:** Main WordPress plugin

---

**Version:** 4.0.0  
**Status:** Production Ready  
**Blockchain:** Solana Mainnet  
**Last Updated:** January 4, 2026

