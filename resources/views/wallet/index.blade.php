<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wallet - TuitionFinder</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .header { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 0 20px; }
        .header-container { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; height: 70px; }
        .logo { font-size: 24px; font-weight: 700; color: #2563eb; text-decoration: none; }
        .nav-menu { display: flex; list-style: none; gap: 30px; align-items: center; }
        .nav-menu a { text-decoration: none; color: #4b5563; font-weight: 500; transition: color 0.3s; }
        .nav-menu a:hover, .nav-menu a.active { color: #2563eb; }
        .user-menu { display: flex; align-items: center; gap: 15px; position: relative; }
        .user-profile { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 6px; background: #f1f5f9; cursor: pointer; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: #2563eb; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
        .dropdown { display: none; position: absolute; right: 0; top: 56px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 20px rgba(0,0,0,.08); min-width: 180px; z-index: 1000; }
        .dropdown a, .dropdown button { display: block; width: 100%; padding: 10px 12px; color: #111827; text-decoration: none; background: none; border: none; text-align: left; cursor: pointer; }
        .dropdown a:hover, .dropdown button:hover { background: #f8fafc; }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .page-header h1 { font-size: 28px; color: #1f2937; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.3s; cursor: pointer; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .wallet-overview { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .wallet-card { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .wallet-card h3 { color: #6b7280; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        .wallet-balance { font-size: 32px; font-weight: 700; color: #059669; }
        .transactions-section { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .section-title { font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 20px; }
        .transaction-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f3f4f6; }
        .transaction-item:last-child { border-bottom: none; }
        .transaction-info { display: flex; align-items: center; gap: 12px; }
        .transaction-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .transaction-icon.bkash { background: #e91e63; color: white; }
        .transaction-icon.rocket { background: #8e24aa; color: white; }
        .transaction-icon.banking { background: #1976d2; color: white; }
        .transaction-icon.nagad { background: #ff9800; color: white; }
        .transaction-details h4 { font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 2px; }
        .transaction-details p { font-size: 12px; color: #6b7280; }
        .transaction-amount { text-align: right; }
        .transaction-amount .amount { font-size: 16px; font-weight: 600; color: #059669; }
        .transaction-amount .status { font-size: 12px; padding: 4px 8px; border-radius: 12px; font-weight: 500; }
        .status-completed { background: #d1fae5; color: #059669; }
        .empty-state { text-align: center; padding: 60px 20px; color: #6b7280; }
        .pagination { display: flex; justify-content: center; margin-top: 30px; }
        .pagination a { padding: 8px 16px; margin: 0 4px; background: white; color: #2563eb; text-decoration: none; border-radius: 6px; border: 1px solid #e2e8f0; }
        .pagination a:hover { background: #2563eb; color: white; }
        .pagination .active { background: #2563eb; color: white; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        @media (max-width: 768px) {
            .nav-menu { display: none; }
            .page-header { flex-direction: column; gap: 15px; align-items: flex-start; }
            .wallet-overview { grid-template-columns: 1fr; }
            .transaction-item { flex-direction: column; align-items: flex-start; gap: 10px; }
            .transaction-amount { text-align: left; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">TuitionFinder</a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('teacher.jobs.my') }}">Jobs</a></li>
                    <li><a href="{{ route('wallet.index') }}" class="active">Wallet</a></li>
                    <li><a href="{{ route('teacher.profile.edit') }}">Edit Profile</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" id="userMenuTrigger">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->username,0,1)) }}</div>
                    <span>{{ Auth::user()->username }}</span>
                </div>
                <div class="dropdown" id="userDropdown">
                    <a href="{{ route('switch.to.student') }}">Switch to Student View</a>
                    <a href="{{ route('teacher.profile.edit') }}">Edit Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <div class="page-header">
            <h1>Wallet</h1>
            <a href="{{ route('wallet.add-funds') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Funds
            </a>
        </div>

        <div class="wallet-overview">
            <div class="wallet-card">
                <h3>Current Balance</h3>
                <div class="wallet-balance">{{ $teacher->coins }} coins</div>
            </div>
            <div class="wallet-card">
                <h3>Total Transactions</h3>
                <div class="wallet-balance">{{ $transactions->total() }}</div>
            </div>
        </div>

        <div class="transactions-section">
            <h2 class="section-title">Transaction History</h2>
            
            @forelse($transactions as $transaction)
                <div class="transaction-item">
                    <div class="transaction-info">
                        <div class="transaction-icon {{ $transaction->type }}">
                            @if($transaction->type === 'bkash')
                                <i class="fas fa-mobile-alt"></i>
                            @elseif($transaction->type === 'rocket')
                                <i class="fas fa-rocket"></i>
                            @elseif($transaction->type === 'banking')
                                <i class="fas fa-university"></i>
                            @else
                                <i class="fas fa-wallet"></i>
                            @endif
                        </div>
                        <div class="transaction-details">
                            <h4>{{ ucfirst($transaction->type) }} - {{ $transaction->account_number }}</h4>
                            <p>{{ $transaction->created_at->format('M d, Y \a\t h:i A') }}</p>
                            @if($transaction->description)
                                <p>{{ $transaction->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="transaction-amount">
                        <div class="amount">+{{ $transaction->amount }} coins</div>
                        <span class="status status-completed">Completed</span>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-wallet" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                    <h3>No transactions yet</h3>
                    <p>Your transaction history will appear here once you add funds to your wallet.</p>
                </div>
            @endforelse

            @if($transactions->hasPages())
                <div class="pagination">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // User menu dropdown
        const trigger = document.getElementById('userMenuTrigger');
        const dropdown = document.getElementById('userDropdown');
        if (trigger && dropdown) {
            trigger.addEventListener('click', function(e){
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            document.addEventListener('click', function(){
                dropdown.style.display = 'none';
            });
        }
    </script>
</body>
</html>