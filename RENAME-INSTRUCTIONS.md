# 🔄 RENAME INSTRUCTIONS

**Action Required:** Rename this plugin folder

---

## 📝 WHAT TO DO

### Via FileZilla:

**OLD NAME:**
```
wp-content/plugins/vortex-tola-pay/
```

**NEW NAME:**
```
wp-content/plugins/vortex-crypto-payment/
```

---

## 🎯 STEPS

### Method 1: On Server (Via FileZilla)

1. **Connect to server** via FileZilla
2. **Navigate to:** `wp-content/plugins/`
3. **Right-click** on `vortex-tola-pay/` folder
4. **Select:** "Rename"
5. **New name:** `vortex-crypto-payment`
6. **Press Enter**
7. **Done!** ✅

---

### Method 2: Locally Then Upload

1. **On your computer:**
   - Navigate to: `wp-content/plugins/`
   - Rename folder: `vortex-tola-pay` → `vortex-crypto-payment`

2. **Upload to server:**
   - Delete old `vortex-tola-pay/` folder on server
   - Upload new `vortex-crypto-payment/` folder

---

## ✅ AFTER RENAMING

### Reactivate Plugin:

1. Go to: **WordPress Admin → Plugins**
2. Find: **"Vortex Crypto Payment Gateway"**
3. Click: **"Activate"**
4. Configure: **WooCommerce → Settings → Payments → USDC Cryptocurrency**

---

## 🎯 UPDATED PLUGIN INFO

**New Details:**
- **Name:** Vortex Crypto Payment Gateway
- **Folder:** `vortex-crypto-payment/`
- **Main File:** `vortex-tola-pay.php` (filename stays same, content updated)
- **Version:** 4.1.0
- **Primary Currency:** USDC
- **Gateway ID:** `vortex_crypto_pay`
- **Display Name:** "USDC Cryptocurrency"

---

## 📊 WHAT CHANGED

### Plugin Header:
```
OLD: Plugin Name: Vortex TOLA Pay Gateway
NEW: Plugin Name: Vortex Crypto Payment Gateway
```

### Gateway Title:
```
OLD: TOLA/USDC Pay
NEW: USDC Cryptocurrency
```

### Method Description:
```
OLD: Accept payments in TOLA or USDC
NEW: Accept USDC stablecoin payments
```

### Payment Display:
```
OLD: Shows "X TOLA" + USD equivalent
NEW: Shows "X USDC" ≈ USD value
```

### Integration:
```
NEW: Integrates with Vortex_USDC_Transaction_Manager
NEW: Credits USDC to user accounts automatically
NEW: Awards hidden TOLA incentives
```

---

## 🔧 CONFIGURATION

**After rename and reactivate, configure:**

```
WooCommerce → Settings → Payments → USDC Cryptocurrency

Settings to configure:
✅ Enable: Yes
✅ Title: "USDC Cryptocurrency" (or "Crypto Payment")
✅ Description: "Pay with USDC stablecoin - Fast & secure"
✅ Railway Backend URL: https://vortex-engine.railway.app
✅ Treasury Wallet: (Your Solana wallet)
✅ USDC Contract: EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v
```

---

## 🎊 BENEFITS OF RENAME

**Before (vortex-tola-pay):**
- Confusing name (TOLA is hidden now)
- Doesn't reflect USDC-first architecture
- Not clear what it does

**After (vortex-crypto-payment):**
- Clear purpose: Crypto payments
- USDC-first design
- Professional naming
- Matches new architecture

---

## ✅ VERIFICATION

**After renaming, verify:**

1. **Plugin Active:**
   - WordPress Admin → Plugins
   - See "Vortex Crypto Payment Gateway" (active)

2. **Gateway Available:**
   - WooCommerce → Settings → Payments
   - See "USDC Cryptocurrency" option

3. **Configuration Loads:**
   - Click "Set up" on USDC Cryptocurrency
   - Settings page opens correctly

4. **Checkout Works:**
   - Add item to cart
   - Go to checkout
   - See "USDC Cryptocurrency" payment option

---

## 🚀 QUICK RENAME

**Fastest method:**

```
1. FileZilla → Connect
2. Go to wp-content/plugins/
3. Right-click vortex-tola-pay → Rename → vortex-crypto-payment
4. Done!
```

**Takes:** 30 seconds ⚡

---

**Rename now to complete the migration!** 🎯

