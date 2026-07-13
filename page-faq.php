<?php
/**
 * Template Name: FAQ
 * Template Post Type: page
 *
 * Site-wide FAQ, organized by topic (About, SpaceX IPO, Tesla, xAI/Optimus/
 * Neuralink, Using the Site). Built for both regular readers and AI answer
 * engines (ChatGPT, Perplexity, Google AI Overviews, etc.) — every question
 * below is duplicated into matching FAQPage JSON-LD so the same Q&A pairs
 * are machine-readable, not just visually collapsible.
 *
 * IMPORTANT: if you edit the visible questions/answers below, update the
 * $faq array at the top to match — Google's structured data guidelines
 * require the schema content to match what's actually visible on the page.
 *
 * Assign in WordPress: Pages → FAQ → Page Attributes → Template → FAQ
 */

$faq = [
  'About MuskPulse' => [
    [
      'q' => 'What is MuskPulse?',
      'a' => 'MuskPulse.com is an investor intelligence site covering Tesla (TSLA), SpaceX ($SPCX), xAI, Optimus, and Neuralink — the full Musk-affiliated portfolio. It tracks stock moves, product milestones, and company announcements in one place, aimed at retail investors who want signal without noise.',
    ],
    [
      'q' => 'Is MuskPulse affiliated with Tesla, SpaceX, xAI, or Elon Musk?',
      'a' => 'No. MuskPulse is an independent news and commentary site. It is not operated by, endorsed by, or affiliated with Tesla, Inc., Space Exploration Technologies Corp. (SpaceX), xAI, Neuralink, or Elon Musk.',
    ],
    [
      'q' => 'Is the information on MuskPulse financial advice?',
      'a' => 'No. Everything on MuskPulse is for educational and informational purposes only and is not financial, investment, or trading advice. Always do your own research and consult a licensed financial advisor before making investment decisions.',
    ],
    [
      'q' => 'How often is MuskPulse updated?',
      'a' => 'The Mission Feed is updated as material news breaks across Tesla, SpaceX, xAI, and related companies. Live TSLA and $SPCX price data on the site refreshes automatically throughout market hours.',
    ],
    [
      'q' => 'Can I get MuskPulse updates by email?',
      'a' => 'Yes. The Mission Briefing is a free newsletter covering weekly intel on Tesla, SpaceX, xAI, Optimus, and Neuralink — you can sign up from the Mission Briefing page, the signup form on any article, or by checking the newsletter opt-in box on the Contact page.',
    ],
    [
      'q' => 'How can I contact MuskPulse?',
      'a' => 'Use the Contact page to send a message directly to the MuskPulse team. The form also lets you opt in to receive a reply and/or subscribe to the Mission Briefing newsletter.',
    ],
    [
      'q' => 'Does MuskPulse use cookies?',
      'a' => 'Yes, for site functionality and analytics. See the cookie notice shown on your first visit for details on managing your preferences.',
    ],
  ],

  'SpaceX IPO ($SPCX)' => [
    [
      'q' => 'Did SpaceX have an IPO?',
      'a' => 'Per MuskPulse\'s coverage, SpaceX priced its IPO on June 11 and began trading on Nasdaq under the ticker $SPCX on June 12, 2026, at a $1.75 trillion valuation — the largest IPO in market history.',
    ],
    [
      'q' => 'What is SpaceX\'s stock ticker symbol?',
      'a' => 'SpaceX trades on Nasdaq under the ticker $SPCX.',
    ],
    [
      'q' => 'What was SpaceX\'s IPO valuation?',
      'a' => 'SpaceX priced its IPO at a $1.75 trillion valuation, raising approximately $75 billion in the primary offering.',
    ],
    [
      'q' => 'When did $SPCX start trading?',
      'a' => 'First trade was June 12, 2026 at 9:30am ET, following pricing on June 11.',
    ],
    [
      'q' => 'Did SpaceX do a stock split before its IPO?',
      'a' => 'Yes — SpaceX completed a 5-for-1 stock split on May 22, 2026, ahead of the IPO, bringing the per-share price down to a more retail-accessible range.',
    ],
    [
      'q' => 'How is xAI connected to SpaceX\'s IPO?',
      'a' => 'xAI merged into SpaceX in February 2026, ahead of the IPO. That means $SPCX gives investors exposure to Musk\'s AI ambitions in addition to SpaceX\'s launch and satellite (Starlink) operations — a combination no other single public company offers.',
    ],
    [
      'q' => 'Where can I buy $SPCX shares?',
      'a' => '$SPCX is available on Nasdaq from major retail brokerages, including Robinhood, Webull, Public.com, and eToro. As with any newly listed stock, expect elevated volatility in the early trading sessions.',
    ],
  ],

  'Tesla (TSLA)' => [
    [
      'q' => 'What does MuskPulse cover about Tesla?',
      'a' => 'MuskPulse tracks TSLA stock moves, earnings, production and delivery numbers, FSD (Full Self-Driving) rollout progress, Cybercab, Tesla Energy, and other product and business milestones.',
    ],
    [
      'q' => 'Does MuskPulse show live TSLA stock prices?',
      'a' => 'Yes. Live TSLA pricing is shown in the site ticker and market strip on every page, updating automatically during market hours.',
    ],
    [
      'q' => 'What is Cybercab?',
      'a' => 'Cybercab is Tesla\'s purpose-built robotaxi — a two-seat autonomous vehicle without a steering wheel or pedals, designed from the ground up for driverless ride-hailing rather than personal ownership.',
    ],
    [
      'q' => 'What is Tesla FSD Unsupervised?',
      'a' => 'FSD (Full Self-Driving) Unsupervised is Tesla\'s driverless software tier that operates without requiring a human driver ready to take over — as opposed to earlier "supervised" FSD, which requires an attentive driver at all times.',
    ],
  ],

  'xAI, Optimus & Neuralink' => [
    [
      'q' => 'What is xAI?',
      'a' => 'xAI is Elon Musk\'s artificial intelligence company, developer of the Grok models. As of February 2026, xAI merged into SpaceX, meaning $SPCX shareholders now have direct exposure to xAI\'s business alongside SpaceX\'s core launch and satellite operations.',
    ],
    [
      'q' => 'What is Optimus?',
      'a' => 'Optimus is Tesla\'s humanoid robot project, aimed at general-purpose labor and, eventually, mass production for both industrial and consumer use.',
    ],
    [
      'q' => 'What is Neuralink, and why does MuskPulse cover it?',
      'a' => 'Neuralink is Elon Musk\'s brain-computer interface company. MuskPulse covers it alongside Optimus and xAI because all three represent the AI/robotics side of Musk\'s companies that increasingly intersect with Tesla and SpaceX\'s core businesses.',
    ],
  ],

  'Using MuskPulse' => [
    [
      'q' => 'What is Saved Posts and how do I use it?',
      'a' => 'Saved Posts lets you bookmark any article by tapping the Save button on its card. Saved articles are stored in your browser and are viewable anytime from the Saved Posts link in the nav menu, on that same device and browser.',
    ],
    [
      'q' => 'Can I share MuskPulse articles to social media?',
      'a' => 'Yes. Every article card has a Share button that lets you post directly to Facebook, X, or LinkedIn, copy the link, or use your device\'s native share menu.',
    ],
    [
      'q' => 'Does MuskPulse have an RSS feed or way to follow updates automatically?',
      'a' => 'The best way to stay updated automatically is the free Mission Briefing email newsletter, covering weekly intel across Tesla, SpaceX, xAI, Optimus, and Neuralink.',
    ],
  ],
];
?>
<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>

<?php get_template_part('template-parts/ticker'); ?>

<div class="faq-outer">

  <header class="faq-header">
    <div class="faq-status">
      <span class="live-dot"></span>
      <span>KNOWLEDGE BASE</span>
    </div>
    <h1 class="faq-title">Frequently Asked <span class="faq-accent">Questions</span></h1>
    <p class="faq-sub">Everything about MuskPulse, $SPCX, TSLA, xAI, Optimus, and Neuralink — in one place.</p>
  </header>

  <?php foreach ($faq as $section_title => $items) : ?>
    <section class="faq-section">
      <div class="faq-section-title">// <?php echo esc_html(strtoupper($section_title)); ?></div>
      <?php foreach ($items as $item) : ?>
        <details class="faq-item">
          <summary class="faq-question">
            <?php echo esc_html($item['q']); ?>
            <span class="faq-toggle">+</span>
          </summary>
          <div class="faq-answer">
            <p><?php echo esc_html($item['a']); ?></p>
          </div>
        </details>
      <?php endforeach; ?>
    </section>
  <?php endforeach; ?>

</div>

<?php
// FAQPage structured data — mirrors the visible Q&A above exactly, per
// Google's structured data guidelines (schema must match on-page content).
$faq_schema = [
  '@context'   => 'https://schema.org',
  '@type'      => 'FAQPage',
  'mainEntity' => [],
];
foreach ($faq as $items) {
  foreach ($items as $item) {
    $faq_schema['mainEntity'][] = [
      '@type'          => 'Question',
      'name'           => $item['q'],
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text'  => $item['a'],
      ],
    ];
  }
}
?>
<script type="application/ld+json"><?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES); ?></script>

<?php get_footer(); ?>
