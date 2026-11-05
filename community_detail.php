<?php
session_start();
?>
<?php

$contributions = [

    1 => [
        "title" => "My First Sarawak Laksa",
        "image" => "image/food1.jpg",
        "contributor" => "Jason",
        "related" => "Sarawak Laksa Cooking Experience",
        "description" => "Finally tried making Sarawak laksa at home! The flavour turned out better than expected."
    ],

    2 => [
        "title" => "My Homemade Kek Lapis",
        "image" => "image/food2.jpg",
        "contributor" => "Amelia",
        "related" => "Traditional Sarawak Food Workshop",
        "description" => "Tried making a traditional Sarawak layer cake for the first time."
    ],

    3 => [
        "title" => "My Favourite Kolo Mee",
        "image" => "image/food3.jpg",
        "contributor" => "Daniel",
        "related" => "Kuching Local Food Tour",
        "description" => "A simple bowl of kolo mee that has become one of my favourite local meals."
    ],

    4 => [
        "title" => "Exploring Satok Market",
        "image" => "image/food4.jpg",
        "contributor" => "Sarah",
        "related" => "Satok Food Market Experience",
        "description" => "A short walkthrough of the food stalls and local ingredients found at Satok Market."
    ],

    5 => [
        "title" => "Cooking Manok Pansuh",
        "image" => "image/food1.jpg",
        "contributor" => "Michael",
        "related" => "Traditional Sarawak Cooking Experience",
        "description" => "Sharing my experience trying one of Sarawak's traditional dishes."
    ],

    6 => [
        "title" => "Discovering Sarawak Coffee",
        "image" => "image/food2.jpg",
        "contributor" => "Kevin",
        "related" => "Sarawak Local Products",
        "description" => "Found a local coffee that quickly became part of my morning routine."
    ]

];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

if (!isset($contributions[$id])) {
    $id = 1;
}

$contribution = $contributions[$id];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css">

    <title><?php echo $contribution["title"]; ?> | Kuching Bites</title>

</head>

<body>

    <!-- =========================
         NAVIGATION BAR
         ========================= -->

    <header class="navbar">

        <h1 class="logo">
            Kuching Bites
        </h1>

        <nav>
            <a href="index.php">Home</a>
            <a href="main_menu.php">Explore</a>
            <a href="profile.php">Profile</a>
            <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true): ?>

                <span class="nav-user">
                    Logged in
                </span>

                <a href="logout.php">
                    Logout
                </a>

            <?php else: ?>

                <a href="login.php">
                    Login
                </a>

                <a href="registration.php">
                    Register
                </a>

            <?php endif; ?>
        </nav>

    </header>

    <main>

        <!-- Detail Page -->

        <section class="community-detail">

            <div class="community-detail-image">

                <img
                    src="<?php echo $contribution["image"]; ?>"
                    alt="<?php echo $contribution["title"]; ?>"
                >

            </div>

            <div class="community-detail-content">

                <p class="community-detail-label">
                    COMMUNITY CONTRIBUTION
                </p>

                <h2>
                    <?php echo $contribution["title"]; ?>
                </h2>

                <p class="community-detail-description">
                    <?php echo $contribution["description"]; ?>
                </p>

                <div class="community-detail-info">

                    <div>

                        <span>CONTRIBUTOR</span>

                        <strong>
                            <?php echo $contribution["contributor"]; ?>
                        </strong>

                    </div>

                    <div>

                        <span>RELATED ACTIVITY / SERVICE</span>

                        <strong>
                            <?php echo $contribution["related"]; ?>
                        </strong>

                    </div>

                </div>

                <a
                    href="community.php"
                    class="btn btn-primary"
                >
                    Back to Community
                </a>

            </div>

        </section>

    </main>

    <footer>

        <a href="about.php">
            About Kuching Bites
        </a>

    </footer>

</body>

</html>