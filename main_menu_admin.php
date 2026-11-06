<?php
// Allow access only to logged-in administrators
require_once "auth.php";
requireLogin("admin");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css?v=4">

    <title>Kuching Bites - Admin Dashboard</title>
</head>

<body>

    <!-- Admin Navigation Bar -->
    <header class="navbar admin-navbar">

        <h1 class="logo">
            Kuching Bites
        </h1>

        <nav>

            <a href="index.php">Home</a>

            <a href="main_menu_admin.php">Admin Dashboard</a>

            <span class="nav-user">Admin Logged In</span>

            <a href="logout.php">Logout</a>

        </nav>

    </header>

    <main>

        <!-- Admin Dashboard Introduction -->
        <section class="menu-header">

            <p class="menu-label">ADMINISTRATION PANEL</p>

            <h2>Welcome to the Admin Dashboard.</h2>

            <p>
                Manage user accounts, review community contributions
                and oversee activity registrations.
            </p>

        </section>

        <!-- Admin Management Options -->
        <section class="menu-cards">

            <!-- Manage User Accounts -->
            <div class="menu-card admin-menu-card">

                <div>
                    <div class="card-icon">👥</div>

                    <p class="card-number">01</p>

                    <h3>Manage User Accounts</h3>

                    <p>
                        View, add, update and delete registered user
                        accounts in Kuching Bites.
                    </p>
                </div>

                <div class="admin-links">

                    <a href="manage_accounts.php">
                        Manage Accounts →
                    </a>

                </div>

            </div>

            <!-- Manage Contributions and Activities -->
            <div class="menu-card admin-menu-card">

                <div>
                    <div class="card-icon">📋</div>

                    <p class="card-number">02</p>

                    <h3>Manage Contributions & Activities</h3>

                    <p>
                        Review community contributions and activity
                        registrations. Approve, reject, edit or delete
                        submitted records.
                    </p>
                </div>

                <div class="admin-links">

                    <a href="manage_community.php">
                        Manage Community →
                    </a>

                    <a href="manage_activities.php">
                        Manage Activities →
                    </a>

                </div>

            </div>

        </section>

        <!-- Admin Menu Actions -->
        <section class="menu-actions">

            <a href="index.php" class="btn btn-secondary">
                ← Home
            </a>

            <a href="logout.php" class="btn btn-primary">
                Logout
            </a>

        </section>

    </main>

    <footer>
        <a href="about.php">About Kuching Bites</a>
    </footer>

</body>
</html>