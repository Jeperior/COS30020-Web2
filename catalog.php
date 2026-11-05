<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css">

    <title>Kuching Bites - Catalogue</title>
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

        <!-- =========================
             CATALOGUE INTRODUCTION
             ========================= -->

        <section class="catalog-intro">

            <p class="catalog-label">
                KUCHING BITES MARKETPLACE
            </p>

            <h1>
                Explore Local Flavours
            </h1>

            <p class="catalog-description">
                Discover traditional dishes, local treats and Sarawakian products from Kuching and beyond.
            </p>

        </section>

        <!-- =========================
             CATEGORY 1
             LOCAL DISHES
             ========================= -->

        <section class="catalog-section">

            <div class="category-heading">

                <p>01</p>

                <h2>Local Dishes</h2>

            </div>

            <div class="product-grid">

                <!-- =========================
                     1. SARAWAK LAKSA
                     ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/food1.jpg"
                                alt="Sarawak Laksa"
                            >

                            <div class="product-front-info">

                                <p>LOCAL DISH</p>

                                <h3>Sarawak Laksa</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL DISH
                            </p>

                            <h3>Sarawak Laksa</h3>

                            <p>
                                A rich and aromatic Sarawakian
                                noodle dish with a distinctive
                                local flavour.
                            </p>

                            <p class="product-price">
                                RM 12.00
                            </p>

                            <a href="order.php?product=sarawak-laksa" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                     2. KOLO MEE
                     ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/food2.jpg"
                                alt="Kolo Mee"
                            >

                            <div class="product-front-info">

                                <p>LOCAL DISH</p>

                                <h3>Kolo Mee</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL DISH
                            </p>

                            <h3>Kolo Mee</h3>

                            <p>
                                Springy noodles tossed with
                                savoury sauce and topped with
                                slices of meat and vegetables.
                            </p>

                            <p class="product-price">
                                RM 8.00
                            </p>

                            <a href="order.php?product=kolo-mee" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                     3. KUEH CHAP
                     ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/food3.jpg"
                                alt="Kueh Chap"
                            >

                            <div class="product-front-info">

                                <p>LOCAL DISH</p>

                                <h3>Kueh Chap</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL DISH
                            </p>

                            <h3>Kueh Chap</h3>

                            <p>
                                A comforting Sarawakian soup
                                featuring flat rice noodles,
                                meat and a rich herbal broth.
                            </p>

                            <p class="product-price">
                                RM 10.00
                            </p>

                            <a href="order.php?product=kueh-chap" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                     4. MANOK PANSUH
                     ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/food4.jpg"
                                alt="Manok Pansuh"
                            >

                            <div class="product-front-info">

                                <p>LOCAL DISH</p>

                                <h3>Manok Pansuh</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL DISH
                            </p>

                            <h3>Manok Pansuh</h3>

                            <p>
                                Traditional chicken cooked
                                inside bamboo with aromatic
                                herbs and local ingredients.
                            </p>

                            <p class="product-price">
                                RM 18.00
                            </p>

                            <a href="order.php?product=manok-pansuh" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                     5. NASI ARUK
                     ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <!-- Temporary image -->
                            <img
                                src="image/food5.jpg"
                                alt="Nasi Aruk"
                            >

                            <div class="product-front-info">

                                <p>LOCAL DISH</p>

                                <h3>Nasi Aruk</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL DISH
                            </p>

                            <h3>Nasi Aruk</h3>

                            <p>
                                Sarawak-style fried rice cooked
                                with garlic, onions, pepper and
                                local ingredients.
                            </p>

                            <p class="product-price">
                                RM 9.00
                            </p>

                            <a href="order.php?product=nasi-aruk" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                     6. MEE KACANG
                     ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <!-- Temporary image -->
                            <img
                                src="image/food6.jpg"
                                alt="Mee Kacang"
                            >

                            <div class="product-front-info">

                                <p>LOCAL DISH</p>

                                <h3>Mee Kacang</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL DISH
                            </p>

                            <h3>Mee Kacang</h3>

                            <p>
                                A local noodle dish served
                                with a savoury peanut-based
                                sauce and toppings.
                            </p>

                            <p class="product-price">
                                RM 9.00
                            </p>

                            <a href="order.php?product=mee-kacang" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        </section>
            <!-- =========================
            CATEGORY 2
            LOCAL SNACKS & TREATS
            ========================= -->

        <section class="catalog-section">

            <div class="category-heading">

                <p>02</p>

                <h2>Local Snacks & Treats</h2>

            </div>

            <div class="product-grid">

                <!-- =========================
                    1. KEK LAPIS SARAWAK
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <div class="product-card-front">

                            <img
                                src="image/snack1.jpg"
                                alt="Kek Lapis Sarawak"
                            >

                            <div class="product-front-info">

                                <p>LOCAL TREAT</p>

                                <h3>Kek Lapis Sarawak</h3>

                            </div>

                        </div>

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL TREAT
                            </p>

                            <h3>Kek Lapis Sarawak</h3>

                            <p>
                                A colourful layered cake known for
                                its intricate patterns and rich,
                                sweet flavour.
                            </p>

                            <p class="product-price">
                                RM 25.00
                            </p>

                            <a href="order.php?product=kek-lapis-sarawak" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    2. KOMPIA
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <div class="product-card-front">

                            <img
                                src="image/snack2.jpg"
                                alt="Kompia"
                            >

                            <div class="product-front-info">

                                <p>LOCAL TREAT</p>

                                <h3>Kompia</h3>

                            </div>

                        </div>

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL TREAT
                            </p>

                            <h3>Kompia</h3>

                            <p>
                                A traditional baked bread snack with
                                a slightly crisp texture and savoury
                                flavour.
                            </p>

                            <p class="product-price">
                                RM 8.00
                            </p>

                            <a href="order.php?product=kompia" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    3. GONG PIA
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <div class="product-card-front">

                            <img
                                src="image/snack3.jpg"
                                alt="Kuih Bangkit"
                            >

                            <div class="product-front-info">

                                <p>LOCAL TREAT</p>

                                <h3>Kuih Bangkit</h3>

                            </div>

                        </div>

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL TREAT
                            </p>

                            <h3>Kuih Bangkit</h3>

                            <p>
                                A traditional tapioca flour cookies that melt in your mouth.
                            </p>

                            <p class="product-price">
                                RM 8.00
                            </p>

                            <a href="order.php?product=kuih-bangkit" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    4. KUIH CINCIN
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <div class="product-card-front">

                            <img
                                src="image/snack4.jpg"
                                alt="Kuih Cincin"
                            >

                            <div class="product-front-info">

                                <p>LOCAL TREAT</p>

                                <h3>Kuih Cincin</h3>

                            </div>

                        </div>

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL TREAT
                            </p>

                            <h3>Kuih Cincin</h3>

                            <p>
                                A traditional ring-shaped snack with
                                a sweet, crispy texture.
                            </p>

                            <p class="product-price">
                                RM 7.00
                            </p>

                            <a href="order.php?product=kuih-cincin" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    5. TEBALOI
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <div class="product-card-front">

                            <img
                                src="image/snack5.jpg"
                                alt="Tebaloi"
                            >

                            <div class="product-front-info">

                                <p>LOCAL TREAT</p>

                                <h3>Tebaloi</h3>

                            </div>

                        </div>

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL TREAT
                            </p>

                            <h3>Tebaloi</h3>

                            <p>
                                A traditional Sarawakian sago biscuit
                                with a light, crisp texture.
                            </p>

                            <p class="product-price">
                                RM 12.00
                            </p>

                            <a href="order.php?product=tebaloi" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    6. SARAWAK PEPPER CRACKERS
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <div class="product-card-front">

                            <img
                                src="image/snack6.jpg"
                                alt="Sarawak Pepper Crackers"
                            >

                            <div class="product-front-info">

                                <p>LOCAL TREAT</p>

                                <h3>Pepper Crackers</h3>

                            </div>

                        </div>

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL TREAT
                            </p>

                            <h3>Sarawak Pepper Crackers</h3>

                            <p>
                                A crunchy local snack featuring the
                                distinctive flavour of Sarawak pepper.
                            </p>

                            <p class="product-price">
                                RM 9.00
                            </p>

                            <a href="order.php?product=sarawak-pepper-crackers" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        </section>
        
            <!-- =========================
            CATEGORY 3
            LOCAL PRODUCTS
            ========================= -->

        <section class="catalog-section">

            <div class="category-heading">

                <p>03</p>

                <h2>Local Products</h2>

            </div>

            <div class="product-grid">

                <!-- =========================
                    1. SARAWAK WHITE PEPPER
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/local1.jpg"
                                alt="Sarawak White Pepper"
                            >

                            <div class="product-front-info">

                                <p>LOCAL PRODUCT</p>

                                <h3>White Pepper</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL PRODUCT
                            </p>

                            <h3>Sarawak White Pepper</h3>

                            <p>
                                Premium Sarawak white pepper known
                                for its strong aroma and distinctive
                                spicy flavour.
                            </p>

                            <p class="product-price">
                                RM 15.00
                            </p>

                            <a href="order.php?product=sarawak-white-pepper" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    2. SARAWAK BLACK PEPPER
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/local2.jpg"
                                alt="Sarawak Black Pepper"
                            >

                            <div class="product-front-info">

                                <p>LOCAL PRODUCT</p>

                                <h3>Black Pepper</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL PRODUCT
                            </p>

                            <h3>Sarawak Black Pepper</h3>

                            <p>
                                Aromatic black pepper grown in Sarawak,
                                suitable for cooking and seasoning.
                            </p>

                            <p class="product-price">
                                RM 14.00
                            </p>

                            <a href="order.php?product=sarawak-black-pepper" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    3. DABAI
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/local3.jpg"
                                alt="Dabai"
                            >

                            <div class="product-front-info">

                                <p>LOCAL PRODUCT</p>

                                <h3>Dabai</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL PRODUCT
                            </p>

                            <h3>Dabai</h3>

                            <p>
                                A unique Sarawakian fruit with a rich,
                                creamy texture and distinctive flavour.
                            </p>

                            <p class="product-price">
                                RM 18.00
                            </p>

                            <a href="order.php?product=dabai" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    4. SARAWAK HONEY
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/local4.jpg"
                                alt="Sarawak Honey"
                            >

                            <div class="product-front-info">

                                <p>LOCAL PRODUCT</p>

                                <h3>Sarawak Honey</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL PRODUCT
                            </p>

                            <h3>Sarawak Honey</h3>

                            <p>
                                Naturally produced local honey with
                                a sweet flavour, suitable for drinks,
                                desserts and everyday use.
                            </p>

                            <p class="product-price">
                                RM 18.00
                            </p>

                            <a href="order.php?product=sarawak-honey" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    5. GULA APONG
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/local5.jpg"
                                alt="Gula Apong"
                            >

                            <div class="product-front-info">

                                <p>LOCAL PRODUCT</p>

                                <h3>Gula Apong</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL PRODUCT
                            </p>

                            <h3>Gula Apong</h3>

                            <p>
                                Natural palm sugar made from nipa palm
                                sap, with a deep caramel-like flavour.
                            </p>

                            <p class="product-price">
                                RM 12.00
                            </p>

                            <a href="order.php?product=gula-apong" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

                <!-- =========================
                    6. SARAWAK COFFEE
                    ========================= -->

                <article class="product-card">

                    <div class="product-card-inner">

                        <!-- FRONT -->

                        <div class="product-card-front">

                            <img
                                src="image/local6.jpg"
                                alt="Sarawak Coffee"
                            >

                            <div class="product-front-info">

                                <p>LOCAL PRODUCT</p>

                                <h3>Sarawak Coffee</h3>

                            </div>

                        </div>

                        <!-- BACK -->

                        <div class="product-card-back">

                            <p class="product-category">
                                LOCAL PRODUCT
                            </p>

                            <h3>Sarawak Coffee</h3>

                            <p>
                                Locally produced coffee with a rich
                                aroma and smooth roasted flavour.
                            </p>

                            <p class="product-price">
                                RM 16.00
                            </p>

                            <a href="order.php?product=sarawak-coffee" class="btn btn-primary">
                                Order
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        </section>
    </main>

    <!-- =========================
         FOOTER
         ========================= -->

    <footer>

        <a href="about.php">
            About Kuching Bites
        </a>

    </footer>

</body>

</html>