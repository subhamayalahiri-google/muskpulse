<?php
/**
 * Template Name: Saved Posts
 * Template Post Type: page
 *
 * Renders posts bookmarked via the Save button (stored client-side in
 * localStorage under 'mp_saved_posts') — see js/saved-posts.js.
 * Assign in WordPress: Pages → Saved Posts → Page Attributes → Template → Saved Posts
 */
?>
<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>

<?php get_template_part('template-parts/ticker'); ?>

<div class="sp-outer">

  <div class="sp-header">// SAVED POSTS</div>

  <div class="sp-loading" id="spLoading">Loading saved intel…</div>

  <div class="sp-feed archive-grid" id="spFeed"></div>

  <div class="sp-empty" id="spEmpty" style="display:none">
    <div class="sp-empty-icon">◇</div>
    <p>No saved posts yet.</p>
    <p class="sp-empty-sub">Tap the bookmark icon on any post to save it for later.</p>
  </div>

</div>

<?php get_footer(); ?>
