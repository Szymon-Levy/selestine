<?php

/**
 * Queries the database with a given query and returns found rows. Returns false if not found.
 * @param string $query Query to search in the database.
 * @param array $data Values to use in the query.
 * @return array|boolean Associative array of found row/rows or false if not found.
 */
function query (string $query, array $data = []) {
  $dsn = 'mysql:hostname=' . DB_HOST . ';dbname=' . DB_NAME;
  $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);

  $stmt = $pdo->prepare($query);
  $stmt->execute($data);

  $restult = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if(is_array($restult) && !empty($restult)) {
    return $restult;
  }

  return false;
}

/**
 * Redirects to a given page and stops further code execution.
 * @param string $page Page to redirect to.
 */
function redirect (string $page) {
  header('Location: ' . $page);
  die();
}

/**
 * Login user into page.
 * @param array $db_user_row Row from the database of user to login.
 */
function authenticate_user (array $db_user_row) {
  $_SESSION['USER'] = $db_user_row;
}

/**
 * Converts password into hashed version.
 * @param string $password Password to hash.
 * @return string Hashed password.
 */
function hash_password (string $password) {
  $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
  return $hashed_password;
}

/* === HTML FUNCTIONS === */

/**
 * Get images from a certain directory and creates slider items to bottom slider gallery.
 */
function generate_bottom_gallery () {
  $directory = 'assets/images/bottom-gallery';
  $images = array_diff(scandir($directory), array('..', '.'));

  if (!empty($images)) {
    foreach ($images as $i => $src) {
      echo '<div class="swiper-slide">';
      echo '<img src="' . ROOT . '/assets/images/bottom-gallery/' . $src . '" alt="gallery image ' . $i . '">';
      echo '</div>';
    }
  }
}

/**
 * Returns class of active menu item if its page is currently opened.
 * @param string $href Href to the page in the menu item link.
 * @return string Class of menu item. If page doesn't match returns empty string.
 */
function is_menu_item_active(string $href) {
  if (PAGE_NAME == $href) {
    return 'active';
  }
  return '';
}