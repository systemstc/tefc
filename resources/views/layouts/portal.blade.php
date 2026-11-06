<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TEFC Portal')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --portal-bg: #f4f7fa;
        }
        body { background: var(--portal-bg); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .portal-header { 
            height: var(--header-height); 
            background: #fff; 
            border-bottom: 1px solid var(--border); 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 0 2rem; 
            position: fixed; 
            top: 0; 
            left: 0; 
            right: 0; 
            z-index: 1000;
        }
        .portal-logo { height: 40px; }
        
        .portal-main { display: flex; margin-top: var(--header-height); min-height: calc(100vh - var(--header-height)); }
        
        .portal-sidebar { 
            width: var(--sidebar-width); 
            background: #fff; 
            border-right: 1px solid var(--border); 
            position: fixed; 
            top: var(--header-height); 
            bottom: 0; 
            left: 0; 
            padding: 1.5rem; 
            overflow-y: auto;
        }
        
        .portal-content { 
            flex: 1; 
            margin-left: var(--sidebar-width); 
            padding: 2.5rem; 
            width: calc(100% - var(--sidebar-width)); 
        }
        
        .sidebar-nav { list-style: none; padding: 0; margin: 0; }
        .sidebar-nav li { margin-bottom: 0.4rem; }
        .sidebar-nav a { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 0.75rem 1rem; 
            color: var(--text); 
            text-decoration: none; 
            border-radius: 10px; 
            font-weight: 600; 
            transition: all 0.2s; 
            font-size: 0.95rem;
        }
        .sidebar-nav a:hover { background: var(--sky); color: var(--primary); }
        .sidebar-nav a.active { background: var(--primary); color: #fff; }
        .sidebar-nav i { width: 20px; font-size: 1.1rem; opacity: 0.8; }
        
        /* Table Enhancements */
        .portal-card { background: #fff; border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden; }
        .portal-card-header { padding: 1.5rem 2rem; border-bottom: 1px solid var(--sky); display: flex; justify-content: space-between; align-items: center; }
        .portal-card-header h3 { margin: 0; font-size: 1.25rem; font-weight: 800; color: var(--primary); }
        
        .custom-table { width: 100%; margin-bottom: 0; }
        .custom-table th { background: var(--sky2); padding: 1rem 1.5rem; color: var(--primary); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--sky); }
        .custom-table td { padding: 1.2rem 1.5rem; vertical-align: middle; border-bottom: 1px solid var(--sky); font-size: 0.9rem; }
        .custom-table tr:hover { background: #fdf8fb; }
        
        .status-badge { padding: 0.4rem 1rem; border-radius: 30px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
        .status-active { background: #e6fffa; color: #234e52; border: 1px solid #b2f5ea; }
        .status-pending { background: #fffaf0; color: #744210; border: 1px solid #fbd38d; }
        .status-rejected { background: #fff5f5; color: #822727; border: 1px solid #feb2b2; }
        
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--sky); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; }
        
        .action-btn { width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--border); background: #fff; color: var(--muted); display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer; text-decoration: none; }
        .action-btn:hover { background: var(--primary); color: #fff; border-color: var(--primary); }
        
        .wide-view { max-width: 1400px; margin: 0 auto; }
    </style>
</head>
<body>
    <header class="portal-header">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/Textiles_Committee_Logo_2.png') }}" class="portal-logo" alt="Logo">
            <div style="font-weight: 800; font-size: 1.25rem; color: var(--primary);">TEFC <span style="font-weight: 400; color: var(--muted);">Management</span></div>
        </div>
        <div class="d-flex align-items-center gap-4">
            <div class="d-none d-md-block text-end">
                <div style="font-weight: 700; font-size: 0.95rem; color: var(--navy);">{{ Auth::user()->name }}</div>
                <div style="font-size: 0.75rem; color: var(--muted);">{{ Auth::user()->roles->first()->name ?? 'Member' }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="action-btn" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </header>

    <div class="portal-main">
        <aside class="portal-sidebar">
            <ul class="sidebar-nav">
                @yield('sidebar')
            </ul>
        </aside>
        
        <main class="portal-content">
            <div class="wide-view">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
