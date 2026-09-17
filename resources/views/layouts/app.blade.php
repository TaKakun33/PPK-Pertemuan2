<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'JARA')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            background: #f8fafc; 
            color: #1e293b; 
            line-height: 1.5;
        }

        /* Top Navigation */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #4f46e5;
            color: #fff;
            padding: 12px 28px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .navbar .nav-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .navbar .brand {
            font-weight: 800;
            font-size: 20px;
            letter-spacing: 0.5px;
            text-decoration: none;
            color: #fff;
        }
        .navbar .nav-links {
            display: flex;
            gap: 16px;
            list-style: none;
        }
        .navbar .nav-links a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .navbar .nav-links a:hover, .navbar .nav-links a.active {
            color: #fff;
            background: rgba(255,255,255,0.15);
        }
        .navbar .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .navbar .user-info {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Container Layouts */
        .container { max-width: 420px; margin: 60px auto; padding: 0 16px; }
        .page-container { max-width: 960px; margin: 36px auto; padding: 0 20px; }
        
        /* Card Styles */
        .card { 
            background: #fff; 
            border-radius: 12px; 
            padding: 24px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 10px 24px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }
        .auth-card { 
            background: #fff; 
            border-radius: 14px; 
            padding: 36px; 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.05); 
            border: 1px solid #e2e8f0;
        }
        .auth-header { text-align: center; margin-bottom: 24px; }
        .auth-header h1 { font-size: 30px; color: #4F46E5; font-weight: 800; }
        .auth-header p { color: #64748b; font-size: 14px; margin-top: 4px; }

        /* Page Headers */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }
        .page-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Forms & Inputs */
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #334155; }
        .form-control { 
            width: 100%; 
            padding: 10px 14px; 
            border: 1px solid #cbd5e1; 
            border-radius: 8px; 
            font-size: 14px; 
            color: #0f172a;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { 
            outline: none; 
            border-color: #4F46E5; 
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15); 
        }

        /* Buttons */
        .btn { 
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px; 
            border: 1px solid transparent; 
            border-radius: 8px; 
            font-size: 14px; 
            font-weight: 600; 
            cursor: pointer; 
            text-decoration: none;
            transition: all 0.2s;
            line-height: 1.25;
        }
        .btn-block { display: flex; width: 100%; }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-primary:hover { background: #4338ca; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; }
        .btn-logout { 
            background: #ef4444; 
            border: none; 
            color: #fff; 
            padding: 6px 14px; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 13px; 
            font-weight: 600; 
            transition: background 0.2s;
        }
        .btn-logout:hover { background: #dc2626; }

        /* Alerts */
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }

        /* Role & Status Badges */
        .badge { 
            display: inline-flex; 
            align-items: center;
            padding: 3px 10px; 
            border-radius: 9999px; 
            font-size: 12px; 
            font-weight: 600; 
        }
        .role-badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .role-badge.admin { background: #dbeafe; color: #1d4ed8; }
        .role-badge.user { background: #d1fae5; color: #065f46; }
        .role-badge.collaborator { background: #fef3c7; color: #92400e; }

        .badge-status { font-size: 12px; padding: 4px 10px; border-radius: 9999px; font-weight: 600; }
        .status-belum { background: #fef3c7; color: #92400e; }
        .status-sedang { background: #dbeafe; color: #1e40af; }
        .status-selesai { background: #dcfce7; color: #166534; }

        /* Tables */
        .table-responsive { width: 100%; overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0; }
        .table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .table th { background: #f8fafc; padding: 12px 16px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; color: #1e293b; vertical-align: middle; }
        .table tr:last-child td { border-bottom: none; }
        .table tr:hover td { background: #f1f5f9; }

        /* Utilities */
        .text-muted { color: #64748b; font-size: 13px; }
        .d-flex { display: flex; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .gap-4 { gap: 16px; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .mt-6 { margin-top: 24px; }
        .empty-state { text-align: center; padding: 48px 16px; color: #64748b; }
        .empty-state p { margin-bottom: 16px; font-size: 15px; }
    </style>
</head>
<body>
    @auth
        <header class="navbar">
            <div class="nav-left">
                <a href="{{ route('home') }}" class="brand">JARA</a>
                <nav class="nav-links">
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('tasks.index') }}">Daftar Tugas</a>
                    <a href="{{ route('tasks.create') }}">+ Tambah Tugas</a>
                </nav>
            </div>
            <div class="nav-right">
                <div class="user-info">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span class="role-badge {{ Auth::user()->role }}">
                        {{ Auth::user()->role }}
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </header>
    @endauth

    @yield('content')
</body>
</html>