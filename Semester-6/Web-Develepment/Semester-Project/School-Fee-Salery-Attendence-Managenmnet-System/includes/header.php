<?php
if (!isset($conn)) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
}
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Eaglets Nursery School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f0f4f0; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1b4332 0%, #2d6a4f 100%);
            padding-top: 0;
            width: 250px;
            min-width: 250px;
        }
        .sidebar .brand {
            background: rgba(0,0,0,0.2);
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .brand h6 {
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            margin: 0;
            line-height: 1.4;
        }
        .sidebar .brand small {
            color: rgba(255,255,255,0.6);
            font-size: 11px;
        }
        .sidebar .nav-section {
            color: rgba(255,255,255,0.4);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 15px 20px 5px;
            text-transform: uppercase;
        }
        .sidebar a {
            color: rgba(255,255,255,0.8);
            display: block;
            padding: 9px 20px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 6px;
            margin: 1px 8px;
            transition: all 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .topbar {
            background: #fff;
            padding: 13px 25px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .main-content { padding: 25px; }

        /* ── Dark Mode ── */
        body.dark-mode {
            background-color: #0f1117 !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .main-content {
            background-color: #0f1117 !important;
        }
        body.dark-mode .topbar {
            background-color: #1a1d2e !important;
            border-bottom: 1px solid #2a2d3e !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .topbar strong {
            color: #e0e0e0 !important;
        }
        body.dark-mode .card {
            background-color: #1a1d2e !important;
            border-color: #2a2d3e !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .card-header {
            background-color: #1a1d2e !important;
            border-bottom: 1px solid #2a2d3e !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .card-footer {
            background-color: #1a1d2e !important;
            border-top: 1px solid #2a2d3e !important;
        }
        body.dark-mode .bg-light {
            background-color: #2a2d3e !important;
        }
        body.dark-mode .bg-white {
            background-color: #1a1d2e !important;
        }

        /* Tables */
        body.dark-mode .table {
            color: #e0e0e0 !important;
        }
        body.dark-mode .table > :not(caption) > * > * {
            background-color: #1a1d2e !important;
            color: #e0e0e0 !important;
            border-color: #2a2d3e !important;
        }
        body.dark-mode tbody tr {
            background-color: #1a1d2e !important;
        }
        body.dark-mode tbody td {
            background-color: #1a1d2e !important;
            color: #e0e0e0 !important;
            border-color: #2a2d3e !important;
        }
        body.dark-mode tbody th {
            background-color: #1a1d2e !important;
            color: #e0e0e0 !important;
            border-color: #2a2d3e !important;
        }
        body.dark-mode .table-hover tbody tr:hover td {
            background-color: #2a2d3e !important;
            color: #fff !important;
        }
        body.dark-mode .table-light,
        body.dark-mode .table-light th,
        body.dark-mode .table-light td {
            background-color: #222537 !important;
            color: #e0e0e0 !important;
            border-color: #2a2d3e !important;
        }
        body.dark-mode .table-dark,
        body.dark-mode .table-dark th,
        body.dark-mode .table-dark td {
            background-color: #111827 !important;
            color: #e0e0e0 !important;
            border-color: #2a2d3e !important;
        }

        /* Forms */
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #2a2d3e !important;
            border-color: #3a3d4e !important;
            color: #e0e0e0 !important;
        }
        body.dark-mode .form-control::placeholder {
            color: #888 !important;
        }
        body.dark-mode .input-group-text {
            background-color: #2a2d3e !important;
            border-color: #3a3d4e !important;
            color: #e0e0e0 !important;
        }

        /* Buttons */
        body.dark-mode .btn-outline-secondary {
            color: #aaa !important;
            border-color: #aaa !important;
        }
        body.dark-mode .btn-outline-secondary:hover {
            background-color: #2a2d3e !important;
            color: #fff !important;
        }
        body.dark-mode .btn-outline-primary {
            color: #7ec8e3 !important;
            border-color: #7ec8e3 !important;
        }
        body.dark-mode .btn-outline-primary:hover {
            background-color: #1a3a4a !important;
            color: #fff !important;
        }
        body.dark-mode .btn-outline-danger {
            color: #e37e7e !important;
            border-color: #e37e7e !important;
        }
        body.dark-mode .btn-outline-danger:hover {
            background-color: #3a1a1a !important;
            color: #fff !important;
        }
        body.dark-mode .btn-outline-success {
            color: #7ec87e !important;
            border-color: #7ec87e !important;
        }
        body.dark-mode .btn-outline-success:hover {
            background-color: #1a3a1a !important;
            color: #fff !important;
        }

        /* Text */
        body.dark-mode .text-muted {
            color: #aaa !important;
        }
        body.dark-mode strong {
            color: #e0e0e0 !important;
        }
        body.dark-mode label {
            color: #ccc !important;
        }
        body.dark-mode h1, body.dark-mode h2,
        body.dark-mode h3, body.dark-mode h4,
        body.dark-mode h5, body.dark-mode h6 {
            color: #e0e0e0 !important;
        }
        body.dark-mode small {
            color: #aaa !important;
        }
        body.dark-mode hr {
            border-color: #2a2d3e !important;
        }

        /* Alerts */
        body.dark-mode .alert-info {
            background-color: #1a2a3a !important;
            border-color: #1a4a6a !important;
            color: #7ec8e3 !important;
        }
        body.dark-mode .alert-success {
            background-color: #1a2e1a !important;
            border-color: #1a4a1a !important;
            color: #7ec87e !important;
        }
        body.dark-mode .alert-danger {
            background-color: #2e1a1a !important;
            border-color: #4a1a1a !important;
            color: #e37e7e !important;
        }
        body.dark-mode .alert-warning {
            background-color: #2e2a1a !important;
            border-color: #4a3a1a !important;
            color: #e3c87e !important;
        }

        /* Misc */
        body.dark-mode .progress {
            background-color: #2a2d3e !important;
        }
        body.dark-mode .border-bottom {
            border-color: #2a2d3e !important;
        }
        body.dark-mode .shadow-sm {
            box-shadow: 0 1px 6px rgba(0,0,0,0.5) !important;
        }
        body.dark-mode .border-warning {
            border-color: #5a4a1a !important;
        }
        body.dark-mode .table-warning,
        body.dark-mode .table-warning th,
        body.dark-mode .table-warning td {
            background-color: #2e2a1a !important;
            color: #e3c87e !important;
            border-color: #4a3a1a !important;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar">
        <div class="brand d-flex align-items-center gap-2">
            <div style="background:rgba(255,255,255,0.2); border-radius:50%; width:38px; height:38px;
                        display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-mortarboard-fill text-white"></i>
            </div>
            <div>
                <h6>The Eaglets</h6>
                <small>Nursery School System</small>
            </div>
        </div>

        <?php
        $current = $_SERVER['PHP_SELF'];
        function isActive($path) {
            global $current;
            return strpos($current, $path) !== false ? 'active' : '';
        }
        ?>

        <div style="padding: 10px 0;">

            <div class="nav-section">Main</div>
            <a href="/04. eaglets_school/dashboard.php"
               class="<?= isActive('dashboard') ?>">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>

            <div class="nav-section">Academic</div>
            <a href="/04. eaglets_school/classes/index.php"
               class="<?= isActive('classes') ?>">
                <i class="bi bi-journal-bookmark me-2"></i>Classes
            </a>
            <a href="/04. eaglets_school/students/index.php"
               class="<?= isActive('students') ?>">
                <i class="bi bi-people me-2"></i>Students
            </a>
            <a href="/04. eaglets_school/attendance/index.php"
               class="<?= isActive('/attendance/index') ?>">
                <i class="bi bi-calendar-check me-2"></i>Attendance
            </a>
            <a href="/04. eaglets_school/attendance/report.php"
               class="<?= isActive('/attendance/report') ?>">
                <i class="bi bi-calendar2-week me-2"></i>Att. Report
            </a>

            <div class="nav-section">Finance</div>
            <a href="/04. eaglets_school/fees/index.php"
               class="<?= isActive('fees/index') ?>">
                <i class="bi bi-receipt me-2"></i>Fee Vouchers
            </a>
            <a href="/04. eaglets_school/fees/bulk_generate.php"
               class="<?= isActive('bulk_generate') ?>">
                <i class="bi bi-lightning-charge me-2"></i>Bulk Generate Fee
            </a>
            <a href="/04. eaglets_school/fees/collect.php"
               class="<?= isActive('collect') ?>">
                <i class="bi bi-cash-coin me-2"></i>Collect Fee
            </a>
            <a href="/04. eaglets_school/receipts/index.php"
               class="<?= isActive('receipts') ?>">
                <i class="bi bi-printer me-2"></i>Receipts
            </a>

            <div class="nav-section">Staff</div>
            <a href="/04. eaglets_school/teachers/index.php"
               class="<?= isActive('teachers') ?>">
                <i class="bi bi-person-badge me-2"></i>Teachers
            </a>
            <a href="/04. eaglets_school/salary/index.php"
               class="<?= isActive('salary') ?>">
                <i class="bi bi-wallet2 me-2"></i>Salaries
            </a>

            <div class="nav-section">Reports</div>
            <a href="/04. eaglets_school/reports/index.php"
               class="<?= isActive('reports') ?>">
                <i class="bi bi-bar-chart me-2"></i>Reports
            </a>

            <hr style="border-color:rgba(255,255,255,0.1); margin: 10px 15px;">
            <a href="/04. eaglets_school/auth/change_password.php"
               class="<?= isActive('change_password') ?>">
                <i class="bi bi-lock me-2"></i>Change Password
            </a>
            <a href="/04. eaglets_school/auth/logout.php">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
        </div>
    </div>

    <div class="flex-grow-1">
        <div class="topbar">
            <strong><?= $pageTitle ?? 'Dashboard' ?></strong>
            <div class="d-flex align-items-center gap-3">
                <button onclick="toggleTheme()" id="theme-btn"
                        class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-moon-fill" id="theme-icon"></i>
                    <span id="theme-text"> Dark</span>
                </button>
                <span class="text-muted small">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin') ?>
                </span>
            </div>
        </div>
        <div class="main-content">