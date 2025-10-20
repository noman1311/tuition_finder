# Wallet Feature Testing Guide

## Testing the Updated Wallet Feature

The wallet feature now uses the existing `transactions` table instead of creating a new one. Here's how to test it:

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Test Teacher Wallet Access
1. Login as a teacher
2. Navigate to `/wallet`
3. Should see current coin balance and empty transaction history

### 3. Test Add Funds
1. Click "Add Funds" button
2. Fill out the form:
   - Select payment method (bKash, Rocket, Nagad, Banking)
   - Enter account number
   - Enter amount (1-10,000)
3. Submit the form
4. Should redirect to wallet page with success message
5. Transaction should appear as "Pending"

### 4. Test Admin Approval
1. Navigate to `/admin/wallet-transactions`
2. Should see the submitted transaction
3. Click "Approve" - coins should be added to teacher account
4. Or click "Reject" with optional notes

### 5. Verify Database
Check the `transactions` table - it should contain:
- Wallet transactions with `account_number` filled
- Regular transactions with `account_number` as NULL
- Status field for wallet transactions
- Notes field for rejection reasons

### Key Differences from Previous Version
- Uses existing `transactions` table instead of new `wallet_transactions` table
- Field mapping:
  - `payment_type` → `type`
  - `id` → `transaction_id`
- Added `walletTransactions()` scope to filter only wallet-related transactions
- Maintains backward compatibility with existing transaction data

### Database Query Examples
```sql
-- View all wallet transactions
SELECT * FROM transactions WHERE account_number IS NOT NULL;

-- View pending wallet transactions
SELECT * FROM transactions WHERE account_number IS NOT NULL AND status = 'pending';

-- View teacher coins
SELECT name, coins FROM teachers WHERE user_id = [USER_ID];
```