<?php
/**
 * header.php
 * Loaded by get_header() in all templates.
 * This correctly triggers wp_head() and wp_enqueue_scripts hooks.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  /* Orbitron, Share Tech Mono, and Exo 2 are self-hosted via @font-face in
     css/global.css — no Google Fonts CDN request needed (or wanted; it
     would silently fight the self-hosted @font-face for the same
     font-family names, and adds a third-party request this theme is
     otherwise built to avoid). */
  wp_head();
  ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
