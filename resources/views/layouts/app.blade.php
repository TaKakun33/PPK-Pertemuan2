<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'JARA')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; color: #333; }
        .container { max-width: 420px; margin: 80px auto; }
        .auth-card { background: #fff; border-radius: 12px; padding: 32px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .auth-header { text-align: center; margin-bottom: 24px; }
        .auth-header h1 { font-size: 28px; color: #4F46E5; }
        .auth-header p { color: #777; font-size: 14px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
        .form-control:focus { outline: none; border-color: #4F46E5; }
        .btn { display: block; width: 100%; padding: 11px; border: none; border-radius: 8px; background: #4F46E5; color: #fff; font-size: 15px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #4338CA; }
        .alert { padding: 10px 12px; border-radius: 8px; font-size: 14px; margin-top: 12px; }
        .alert-error { background: #FEE2E2; color: #B91C1C; }
        .alert-success { background: #D1FAE5; color: #065F46; }
        .text-muted { color: #777; font-size: 13px; }
        .nav { display: flex; justify-content: space-between; align-items: center; background: #4F46E5; color: #fff; padding: 14px 24px; }
        .nav .brand { font-weight: 700; font-size: 18px; }
        .nav .user { font-size: 14px; }
        .card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .role-badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .role-badge.admin { background: #DBEAFE; color: #1D4ED8; }
        .role-badge.user { background: #D1FAE5; color: #065F46; }
        .role-badge.collaborator { background: #FEF3C7; color: #92400E; }
        .btn-logout { background: #DC2626; border: none; color: #fff; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; }
        .btn-logout:hover { background: #B91C1C; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>