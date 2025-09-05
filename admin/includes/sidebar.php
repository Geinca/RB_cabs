<?php
// Start session if not already started
if (!isset($_SESSION)) session_start();
?>
<!-- Sidebar -->
<div class="flex flex-col w-64 h-screen bg-yellow-200 text-gray-800 flex-shrink-0">
    <div class="flex items-center justify-center h-16 border-b border-yellow-300 text-2xl font-bold">
        Cab Admin
    </div>
    <ul class="mt-6 flex-1">
        <li>
            <a href="dashboard.php" class="flex items-center px-6 py-3 hover:bg-yellow-300 <?= basename($_SERVER['PHP_SELF'])=='dashboard.php' ? 'bg-yellow-300 font-semibold' : ''; ?>">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="bookings.php" class="flex items-center px-6 py-3 hover:bg-yellow-300 <?= basename($_SERVER['PHP_SELF'])=='bookings.php' ? 'bg-yellow-300 font-semibold' : ''; ?>">
                <i class="bi bi-ticket-perforated-fill me-2"></i>
                Bookings
            </a>
        </li>
        <li>
            <a href="cars.php" class="flex items-center px-6 py-3 hover:bg-yellow-300 <?= basename($_SERVER['PHP_SELF'])=='cars.php' ? 'bg-yellow-300 font-semibold' : ''; ?>">
                <i class="bi bi-car-front-fill me-2"></i>
                Cars
            </a>
        </li>
        <li>
            <a href="users.php" class="flex items-center px-6 py-3 hover:bg-yellow-300 <?= basename($_SERVER['PHP_SELF'])=='users.php' ? 'bg-yellow-300 font-semibold' : ''; ?>">
                <i class="bi bi-people-fill me-2"></i>
                Users
            </a>
        </li>
        <li>
            <a href="logout.php" class="flex items-center px-6 py-3 hover:bg-yellow-300">
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </a>
        </li>
    </ul>
</div>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
