<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Funds - TuitionFinder</title>
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
        .container { max-width: 600px; margin: 40px auto; padding: 0 20px; }
        .page-header { margin-bottom: 30px; text-align: center; }
        .page-header h1 { font-size: 28px; color: #1f2937; margin-bottom: 8px; }
        .page-header p { color: #6b7280; }
        .form-container { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .form-input, .form-select { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; }
        .form-input:focus, .form-select:focus { outline: none; border-color: #2563eb; }
        .payment-options { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 12px; }
        .payment-option { position: relative; }
        .payment-option input[type="radio"] { position: absolute; opacity: 0; }
        .payment-option label { display: flex; flex-direction: column; align-items: center; padding: 16px 12px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-align: center; }
        .payment-option input[type="radio"]:checked + label { border-color: #2563eb; background: #eff6ff; }
        .payment-icon { font-size: 24px; margin-bottom: 8px; }
        .payment-icon.bkash { color: #e91e63; }
        .payment-icon.rocket { color: #8e24aa; }
        .payment-icon.banking { color: #1976d2; }
        .payment-icon.nagad { color: #ff9800; }
        .payment-name { font-size: 14px; font-weight: 500; color: #374151; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.3s; cursor: pointer; width: 100%; text-align: center; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: #6b7280; color: white; }
        .btn-secondary:hover { background: #4b5563; }
        .form-actions { display: flex; gap: 12px; margin-top: 30px; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
        .info-box h3 { color: #1e40af; font-size: 16px; margin-bottom: 8px; }
        .info-box p { color: #1e3a8a; font-size: 14px; line-height: 1.5; }
        @media (max-width: 768px) {
            .nav-menu { display: none; }
            .container { margin: 20px auto; }
            .form-container { padding: 20px; }
            .payment-options { grid-template-columns: repeat(2, 1fr); }
            .form-actions { flex-direction: column; }
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
        <div class="page-header">
            <h1>Add Funds to Wallet</h1>
            <p>Submit a request to add coins to your wallet</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> How it works</h3>
            <p>Submit your payment details and amount. Coins will be added to your wallet immediately after submission. Make sure to complete the actual payment using your selected payment method.</p>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('wallet.store') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Payment Method</label>
                    <div class="payment-options">
                        <div class="payment-option">
                            <input type="radio" id="bkash" name="payment_type" value="bkash" {{ old('payment_type') === 'bkash' ? 'checked' : '' }}>
                            <label for="bkash">
                                <div class="payment-icon bkash"><i class="fas fa-mobile-alt"></i></div>
                                <div class="payment-name">bKash</div>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="rocket" name="payment_type" value="rocket" {{ old('payment_type') === 'rocket' ? 'checked' : '' }}>
                            <label for="rocket">
                                <div class="payment-icon rocket"><i class="fas fa-rocket"></i></div>
                                <div class="payment-name">Rocket</div>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="nagad" name="payment_type" value="nagad" {{ old('payment_type') === 'nagad' ? 'checked' : '' }}>
                            <label for="nagad">
                                <div class="payment-icon nagad"><i class="fas fa-wallet"></i></div>
                                <div class="payment-name">Nagad</div>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="banking" name="payment_type" value="banking" {{ old('payment_type') === 'banking' ? 'checked' : '' }}>
                            <label for="banking">
                                <div class="payment-icon banking"><i class="fas fa-university"></i></div>
                                <div class="payment-name">Banking</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="account_number" class="form-label">Account Number</label>
                    <input type="text" id="account_number" name="account_number" class="form-input" 
                           placeholder="Enter your account number" value="{{ old('account_number') }}" required>
                </div>

                <div class="form-group">
                    <label for="amount" class="form-label">Amount (Coins)</label>
                    <input type="number" id="amount" name="amount" class="form-input" 
                           placeholder="Enter amount" min="1" max="10000" step="1" value="{{ old('amount') }}" required>
                    <small style="color: #6b7280; font-size: 14px; margin-top: 4px; display: block;">
                        Minimum: 1 coin, Maximum: 10,000 coins
                    </small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('wallet.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
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