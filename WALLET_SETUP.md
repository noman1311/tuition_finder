# Wallet Feature Setup Instructions

## Database Setup

⚠️ **Important**: The wallet feature requires a specific transactions table structure. 

Please follow the **WALLET_DATABASE_SETUP.md** guide to set up the correct database schema.

**Quick Setup**: Use the SQL script in `UPDATED_TRANSACTIONS_TABLE.sql` to create the correct transactions table structure.

The transactions table includes these key fields for wallet functionality:
- `transaction_id` - Primary key
- `user_id` - Foreign key to users table
- `account_number` - Payment account number (NULL for non-wallet transactions)
- `amount` - Amount in coins
- `type` - Type of payment (bkash, rocket, banking, nagad)
- `description` - Transaction description
- `transaction_date` - Date of transaction
- `created_at` and `updated_at` - Timestamps

## Features Implemented

### For Teachers:
1. **Wallet Dashboard** (`/wallet`) - View current coin balance and transaction history
2. **Add Funds** (`/wallet/add-funds`) - Add coins instantly with:
   - Payment method selection (bKash, Rocket, Nagad, Banking)
   - Account number
   - Amount (1-10,000 coins)

## How It Works

1. **Teacher submits request**: Teacher fills out the add funds form with payment details
2. **Instant addition**: Coins are immediately added to the teacher's account
3. **Transaction record**: A record is created in the transactions table for reference
4. **Payment responsibility**: Teacher is responsible for completing the actual payment using their selected method

## Access URLs

- Teacher Wallet: `/wallet`
- Add Funds: `/wallet/add-funds`

## Security Notes

- Only teachers can access wallet features
- All routes are protected with authentication middleware
- Coins are added immediately without admin intervention

## Database Schema Update

The wallet feature uses the existing `coins` column in the `teachers` table and extends the existing `transactions` table with additional fields for tracking wallet recharges. Coins are added immediately when teachers submit funding requests, making the process instant and seamless.