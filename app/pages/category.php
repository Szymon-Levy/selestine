<?php 
  $category_slug = $url[1] ?? null;
  $articles_query = 'SELECT articles.*, categories.category_name, categories.slug AS category_slug 
                    FROM articles INNER JOIN categories 
                    ON articles.category_id = categories.id 
                    WHERE categories.is_active = 1 AND articles.category_id IN (SELECT id FROM categories WHERE slug = :category_slug)
                    ORDER BY create_date DESC;';
  $found_articles = query($pdo, $articles_query, ['category_slug' => $category_slug]);

?>

<?php if ($category_slug) { ?>
  <?php 
    $page_title = $category_slug;
    include '../app/pages/includes/top.php'; 
  ?>

  <!-- === BLOG === -->
  <section class="blog">
    <div class="container">
      <?php if (!empty($found_articles)) { ?>
        <div class="blog__title title title--h1"><?= $found_articles[0]['category_name'] ?></div>
      <?php } ?>
      <div class="row blog__row">
        <?php 
        
        if ($found_articles) {
          foreach ($found_articles as $article) {
            include '../app/pages/includes/article-card.php';
          }
        } else {
          echo'Posts not found.';
        }
        ?>
      </div>
    </div>
  </section>

<?php } 
else { ?>

  <?php redirect('blog'); ?>

<?php } ?>

<?php include '../app/pages/includes/bottom.php'; ?>