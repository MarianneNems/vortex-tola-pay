# Vortex TOLA Pay Gateway v4.0.0

**WooCommerce payment gateway for TOLA and USDC cryptocurrency payments**

---

## Overview

Complete payment gateway allowing customers to pay with:
- **TOLA** - Native incentive token on Solana
- **USDC** - Stablecoin for platform utility

Integrates with WooCommerce checkout and connects to Solana blockchain for real-time payment verification.

---

## Features

### Supported Cryptocurrencies:
- ✅ TOLA (Incentive token)
- ✅ USDC (Utility token)
- ✅ SOL (Native Solana)

### Payment Flow:
1. Customer selects "TOLA/USDC Pay" at checkout
2. System generates QR code + Phantom deep link
3. Customer pays with Phantom wallet
4. On-chain verification via Solana RPC
5. Order auto-completes after confirmation

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

1. **Upload Plugin:**
   ```
   Upload to: wp-content/plugins/vortex-tola-pay/
   ```

2. **Activate:**
   ```
   WordPress Admin → Plugins → Activate "Vortex TOLA Pay Gateway"
   ```

3. **Configure:**
   ```
   WooCommerce → Settings → Payments → TOLA/USDC Pay
   
   Settings:
   - Engine URL: https://vortex-engine-production.up.railway.app
   - Treasury Wallet: Your Solana wallet address
   - Enable: Yes
   ```

---

## Configuration

### Required Settings:

**Engine URL:**
- Production: `https://vortex-engine-production.up.railway.app`
- Development: `http://localhost:3000`

**Treasury Wallet:**
- Solana wallet address to receive payments
- Must have token accounts for TOLA and USDC

**Payment Options:**
- TOLA: Native incentive token
- USDC: Stablecoin payments

---

## Integration with Vortex Engine

This plugin requires the [vortex-engine backend](https://github.com/MarianneNems/vortex-engine) to be deployed.

### API Endpoints Used:

```
POST /api/tola/transfer - Transfer TOLA tokens
POST /api/usdc/transfer - Transfer USDC tokens
GET /api/tola/balance/:wallet - Check TOLA balance
GET /api/usdc/balance/:wallet - Check USDC balance
POST /wc/webhooks/order-created - Order webhook
POST /wc/webhooks/order-paid - Payment webhook
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
_tola_payment_intent_id - Payment intent ID
_tola_transaction_signature - Blockchain TX signature
_tola_payment_status - Payment status
_tola_wallet_address - Customer wallet
_tola_amount - Amount paid
_payment_currency - TOLA or USDC
```

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

