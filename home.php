<?php
/**
 * home.php
 * WordPress automatically uses this template for the Posts Page
 * set in Settings → Reading. This renders the Mission Feed —
 * all posts across all categories, newest first.
 *
 * Note: get_queried_object() returns the Page object here (not a category),
 * so category detection is done per-post inside the loop instead.
 */
?>
<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>

<?php get_template_part('template-parts/ticker'); ?>

<?php
  $cat_map = [
    'spacex-ipo'  => ['cat-spacex', '#00c8ff', 'tag-spacex'],
    'tesla-news'  => ['cat-tesla',  '#f5a623', 'tag-tesla'],
    'xai-optimus' => ['cat-xai',    '#cc88ff', 'tag-xai'],
  ];
?>

<div class="archive-outer">

  <header class="archive-header">
    <div class="archive-breadcrumb">
      <a href="<?php echo esc_url(home_url('/')); ?>">MuskPulse</a>
      <span class="sep">›</span>
      <span>Mission Feed</span>
    </div>
    <div class="archive-category-badge">
      <div class="cat-badge cat-invest">
        <span class="dot"></span>
        All Channels
      </div>
    </div>
    <h1 class="archive-title">Mission Feed</h1>
    <p class="archive-desc">All intel across Tesla, SpaceX, xAI, Optimus and Neuralink — newest first.</p>
    <div class="archive-meta">
      <span><?php echo $wp_query->found_posts; ?> Articles</span>
      <span>Live Intel Feed</span>
    </div>
  </header>

  <div class="archive-grid">
    <?php
      $post_num = 1;
      if (have_posts()) :
        while (have_posts()) : the_post();
          $cats      = get_the_category();
          $p_slug    = $cats ? $cats[0]->slug : 'tesla-news';
          $p_name    = $cats ? $cats[0]->name : 'Tesla News';
          $p_map     = isset($cat_map[$p_slug]) ? $cat_map[$p_slug] : ['cat-invest','#00ff88','tag-invest'];
          $excerpt   = wp_trim_words(get_the_excerpt(), 22, '...');
          $ago       = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
          $read      = mp_reading_time();
          $permalink = get_permalink();
    ?>
      <article class="archive-card lp-sf-card" data-url="<?php echo esc_url($permalink); ?>" data-id="<?php the_ID(); ?>">
        <div class="archive-card-inner">
          <div class="archive-card-num"><?php echo str_pad($post_num, 2, '0', STR_PAD_LEFT); ?></div>
          <div class="archive-card-tag <?php echo esc_attr($p_map[2]); ?>">
            <span class="dot"></span>
            <?php echo esc_html($p_name); ?>
          </div>
          <a class="archive-card-title" href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a>
          <p class="archive-card-excerpt"><?php echo esc_html($excerpt); ?></p>
          <div class="archive-card-meta">
            <span><?php echo esc_html($ago); ?></span>
            <span><?php echo esc_html($read); ?> min read</span>
            <button class="lp-sf-action lp-sf-share" type="button">
              <span class="lp-sf-ico">↗</span> Share
            </button>
            <button class="lp-sf-action lp-sf-save" type="button">
              <span class="lp-sf-ico">◇</span> <span class="lp-sf-save-label">Save</span>
            </button>
            <a class="archive-card-read" href="<?php echo esc_url($permalink); ?>">Read →</a>
          </div>
        </div>
        <div class="archive-card-accent" style="--card-accent:<?php echo esc_attr($p_map[1]); ?>"></div>
      </article>
    <?php
          $post_num++;
        endwhile;
      else :
    ?>
      <div class="archive-empty">
        <div class="archive-empty-label">// NO INTEL YET</div>
        <p>Articles are being processed. Check back shortly.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- PAGINATION -->

    <?php
      $paged     = max(1, get_query_var('paged'), get_query_var('page'));
      $has_next  = $paged < $wp_query->max_num_pages;
      $has_prev  = $paged > 1;
    ?>
    <?php if ($has_next || $has_prev) : ?>
    <nav class="archive-pagination">
      <div class="pagination-inner">
        <?php if ($has_prev) : ?>
          <a class="page-btn" href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>">← Newer</a>
        <?php endif; ?>
        <?php if ($has_next) : ?>
          <a class="page-btn" href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>">Older →</a>
        <?php endif; ?>
      </div>
    </nav>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
