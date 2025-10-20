-- Drop the existing transactions table and create a new one that matches the wallet feature code

-- Drop the existing table (be careful - this will delete all existing transaction data)
DROP TABLE IF EXISTS transactions;

-- Create the new transactions table with simplified structure (no status/approval system)
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    account_number VARCHAR(50) NULL COMMENT 'Payment account number - NULL for non-wallet transactions',
    amount DECIMAL(10,2) NOT NULL,
    type ENUM('bkash', 'rocket', 'banking', 'nagad') NOT NULL,
    description TEXT NULL,
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign key constraint
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes for better performance
    INDEX idx_user_id (user_id),
    INDEX idx_account_number (account_number),
    INDEX idx_created_at (created_at)
);

-- Insert some sample data to test the wallet feature
INSERT INTO transactions (user_id, account_number, amount, type, description, transaction_date) VALUES
-- Sample wallet transactions
(2, '01712345678', 100.00, 'bkash', 'Wallet recharge', CURDATE()),
(2, '01798765432', 50.00, 'rocket', 'Wallet recharge', DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
-- Sample regular transaction (no account_number)
(2, NULL, 25.00, 'bkash', 'Job application fee', DATE_SUB(CURDATE(), INTERVAL 2 DAY));

-- Verify the table structure
DESCRIBE transactions;