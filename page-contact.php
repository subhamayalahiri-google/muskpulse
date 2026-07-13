<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 *
 * Contact form — POSTs to admin-post.php (handler + wp_mail() call in
 * functions.php, see mp_handle_contact_form) rather than a form plugin,
 * matching this theme's hand-rolled conventions. Sends to info@muskpulse.com,
 * and — only if the checkbox is opted into — subscribes the sender to the
 * same Kit (ConvertKit) Mission Briefing form used sitewide, via
 * mp_convertkit_subscribe().
 *
 * Assign in WordPress: Pages → Contact → Page Attributes → Template → Contact
 */
$mp_sent  = isset($_GET['mp_contact']) && $_GET['mp_contact'] === 'sent';
$mp_error = isset($_GET['mp_contact']) && $_GET['mp_contact'] === 'error';
?>
<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>

<?php get_template_part('template-parts/ticker'); ?>

<div class="contact-outer">

  <header class="contact-header">
    <div class="contact-status">
      <span class="live-dot"></span>
      <span>OPEN CHANNEL</span>
    </div>
    <h1 class="contact-title">Contact <span class="contact-accent">MuskPulse</span></h1>
    <p class="contact-sub">Questions, tips, or feedback — send a message and we'll get back to you.</p>
  </header>

  <?php if ($mp_sent) : ?>
    <div class="contact-notice contact-notice-success">
      <span class="contact-notice-icon">✓</span>
      Message sent. Thanks for reaching out — we'll reply as soon as we can.
    </div>
  <?php elseif ($mp_error) : ?>
    <div class="contact-notice contact-notice-error">
      <span class="contact-notice-icon">!</span>
      Something went wrong. Please fill in all fields with a valid email and try again.
    </div>
  <?php endif; ?>

  <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <input type="hidden" name="action" value="mp_contact_submit">
    <?php wp_nonce_field('mp_contact_submit', 'mp_contact_nonce'); ?>

    <!-- Honeypot — hidden from real visitors via CSS, bots tend to fill every field -->
    <div class="contact-hp">
      <label for="mp_contact_website">Website</label>
      <input type="text" id="mp_contact_website" name="mp_contact_website" tabindex="-1" autocomplete="off">
    </div>

    <div class="contact-field">
      <label for="mp_contact_name">Name</label>
      <input type="text" id="mp_contact_name" name="mp_contact_name" required>
    </div>

    <div class="contact-field">
      <label for="mp_contact_email">Email</label>
      <input type="email" id="mp_contact_email" name="mp_contact_email" required>
    </div>

    <div class="contact-field">
      <label for="mp_contact_subject">Subject</label>
      <input type="text" id="mp_contact_subject" name="mp_contact_subject" required>
    </div>

    <div class="contact-field">
      <label for="mp_contact_message">Message</label>
      <textarea id="mp_contact_message" name="mp_contact_message" rows="6" required></textarea>
    </div>

    <label class="contact-checkbox">
      <input type="checkbox" name="mp_contact_subscribe" value="1">
      <span>I agree to receive marketing emails and communications from the MuskPulse team. I understand I can unsubscribe at any time.</span>
    </label>

    <button type="submit" class="contact-submit" id="mpContactSubmit" disabled><span>Send Message →</span></button>
  </form>

</div>

<script>
(function () {
  var form   = document.querySelector('.contact-form');
  var submit = document.getElementById('mpContactSubmit');
  if (!form || !submit) return;

  var required = form.querySelectorAll('#mp_contact_name, #mp_contact_email, #mp_contact_subject, #mp_contact_message');

  function updateSubmitState() {
    var allFilled = Array.prototype.every.call(required, function (field) {
      return field.value.trim() !== '' && field.checkValidity();
    });
    submit.disabled = !allFilled;
  }

  required.forEach(function (field) {
    field.addEventListener('input', updateSubmitState);
  });
  updateSubmitState();
})();
</script>

<?php get_footer(); ?>
