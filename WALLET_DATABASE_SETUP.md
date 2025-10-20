# Wallet Database Setup Guide

## Option 1: Using Raw SQL (Recommended for quick setup)

1. **Backup your existing data** (if you have important transaction data):
   ```sql
   CREATE TABLE transactions_backup AS SELECT * FROM transactions;
   ```

2. **Run the SQL script** in phpMyAdmin or your MySQL client:
   ```sql
   -- Copy and paste the content from UPDATED_TRANSACTIONS_TABLE.sql
   ```

3. **Verify the table structure**:
   ```sql
   DESCRIBE transactions;
   ```

## Option 2: Using Laravel Migration

1. **Run the migration**:
   ```bash
   php artisan migrate
   ```

   This will drop the existing transactions table and create a new one with the correct structure.

## New Table Structure

The updated `transactions` table includes these fields:

| Field | Type | Description |
|-------|------|-------------|
| `transaction_id` | INT AUTO_INCREMENT | Primary key |
| `user_id` | INT | Foreign key to users table |
| `account_number` | VARCHAR(50) NULL | Payment account number (NULL for non-wallet transactions) |
| `amount` | DECIMAL(10,2) | Transaction amount |
| `type` | ENUM | Payment type: bkash, rocket, banking, nagad |
| `description` | TEXT NULL | Transaction description |
| `transaction_date` | DATE | Date of transaction |
| `created_at` | TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | Last update timestamp |

## Key Features

### Wallet Transactions vs Regular Transactions
- **Wallet transactions**: Have `account_number` filled (coins added immediately)
- **Regular transactions**: Have `account_number` as NULL (for other purposes)

### Indexes for Performance
- `idx_user_id` - For filtering user transactions
- `idx_account_number` - For wallet transaction queries
- `idx_created_at` - For date-based queries

## Testing the Setup

After creating the table, test with these queries:

```sql
-- Check wallet transactions only
SELECT * FROM transactions WHERE account_number IS NOT NULL;

-- Check all transactions for a user
SELECT * FROM transactions WHERE user_id = 2 ORDER BY created_at DESC;

-- Check teacher coins
SELECT name, coins FROM teachers WHERE user_id = 2;
```

## Sample Data

The SQL script includes sample data:
- Two wallet recharge transactions
- One regular transaction (non-wallet)

This helps you test the wallet feature immediately after setup.

## Important Notes

⚠️ **Warning**: Both options will **drop the existing transactions table**. Make sure to backup any important data first.

✅ **Recommendation**: Use Option 1 (Raw SQL) for immediate setup, especially if you're working with phpMyAdmin.

🔧 **After Setup**: The wallet feature will add coins immediately when teachers submit funding requests - no admin approval needed.