<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- ----- favicon -----  -->
  <link rel="icon" href="<?= get_image_path('logo/favicon.png'); ?>" type="image/png">

  <!-- ----- remix icon -----  -->
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/remixicon.css">

  <!-- ----- custom css -----  -->
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/style.css">

  <!-- ----- swiper js -----  -->
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/swiper-bundle.min.css">

  <title>Home - <?= APP_NAME ?> </title>
</head>
<body>

  <!-- === HEADER === -->
  <header class="header">
    <div class="container">
      <div class="row">
        <ul class="header__socials">
          <li>
            <a href="https://twitter.com/" aria-label="twitter link" target="_blank">
              <i class="ri-twitter-fill" aria-hidden="true"></i>
            </a>
          </li>

          <li>
            <a href="https://instagram.com/" aria-label="instagram link" target="_blank">
              <i class="ri-instagram-fill" aria-hidden="true"></i>
            </a>
          </li>

          <li>
            <a href="https://pinterest.com/" aria-label="pinterest link" target="_blank">
              <i class="ri-pinterest-fill" aria-hidden="true"></i>
            </a>
          </li>

          <li>
            <a href="https://facebook.com/" aria-label="facebook link" target="_blank">
              <i class="ri-facebook-fill" aria-hidden="true"></i>
            </a>
          </li>

          <li>
            <a href="https://www.behance.net/" aria-label="behance link" target="_blank">
              <i class="ri-behance-fill" aria-hidden="true"></i>
            </a>
          </li>
        </ul>

        <a href="<?=ROOT?>" class="header__logo">
          <img src="<?= get_image_path('logo/logo.png'); ?>" alt="Selestine logo">
        </a>

        <form action="<?= ROOT ?>/blog-search" class="header__search form">
          <div class="form__field">
            <input type="text" name="keyword" value="<?= $_GET['keyword'] ?? '' ?>" class="header__search__input" placeholder="search on blog">
            <button type="submit" class="form__submit">
              <span class="visually-hidden">Submit blog search form</span>
              <i class="ri-search-line" aria-hidden="true"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </header>

  <!-- === NAVIGATION BAR === -->
  <div class="nav js-nav" role="navigation">
    <div class="container">
      <div class="row">
        <button class="nav__toggle js-nav-toggler" aria-hidden="true">
          <i class="js-nav-toggler-icon ri-menu-3-line"></i>
        </button>

        <ul class="nav__list js-nav-list">
          <li><a href="<?= ROOT ?>" class="nav__list__link <?= is_menu_item_active('home'); ?>">Home</a></li>
          <li><a href="<?= ROOT ?>/blog" class="nav__list__link">Blog</a></li>
          <li><a href="#" class="nav__list__link">Works</a></li>
          <li><a href="#" class="nav__list__link">FAQ</a></li>
          <li><a href="#" class="nav__list__link">Contact</a></li>

          <?php if (!is_user_logged_in()) { ?>
            <li>
              <a href="login" class="nav__list__link <?= is_menu_item_active('login'); ?>">
                <i class="ri-user-fill" aria-hidden="true"></i>
                Login
              </a></li>
            <li>
              <a href="signup" class="nav__list__link <?= is_menu_item_active('signup'); ?>">
                <i class="ri-user-add-fill" aria-hidden="true"></i>
                signup
              </a>
            </li>
          <?php } ?>
        </ul>

        <?php if (is_user_logged_in()) { ?>
          <?php generate_nav_profile(); ?>
        <?php } ?>

        </div>
      </div>
    </div>
  </div>

  <main>