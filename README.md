# Aquariende WordPress theme

Standalone classic WordPress theme for **Aquariende**, the online
study-in-Germany consultancy for students from Bangladesh. It implements the
approved design, Concept A "The open file": the student's file laid open, with
every step visible.

**No page builder. No parent theme. No plugin dependencies. No shop.**

What the site does, and deliberately does not do:

- The only call to action is **WhatsApp on +49 1575 0129620**, with a
  prefilled English greeting. No other phone number appears anywhere.
- **No prices, no WooCommerce, no cart, no "Buy now".** Prices are shared on
  WhatsApp.
- **No uploads, no booking and no sign-up on the public site.** The flow, as
  the site states it: message on WhatsApp, pay the consultation fee, receive
  your portal link by email, then upload documents and book inside the portal.
- A small **Student login** link in the header goes to the client portal.
- An optional **call-back form** (name and WhatsApp number only) emails the
  site admin and can POST the lead to the ops system.

## Files

| File | Purpose |
|---|---|
| `style.css` | Theme header, design tokens (CSS custom properties), every component, responsive rules (360 to 1440 px), focus rings, reduced-motion rules |
| `functions.php` | Setup, settings constants, `aquariende_info()` (all business details in one place), stages/team/flow data, assets, fonts, meta description fallback, Organization schema, emoji removal, cache purge after deploy, call-back form handler |
| `header.php` | Fixed header: transparent over the dark top band, paper after 80 px of scroll; nav, Student login, WhatsApp button, mobile sheet menu |
| `footer.php` | Footer (pages, contact, legal menu) and the sticky mobile WhatsApp bar |
| `front-page.php` | Home: hero, trust strip, pinned "How it works", packages, why Aquariende, team, latest posts, WhatsApp call to action |
| `template-services.php` | Page template "Services": the three packages in detail, FAQ |
| `template-about.php` | Page template "About": story, how we work, mission, team |
| `template-contact.php` | Page template "Contact": WhatsApp, email, portal note, call-back form |
| `page.php` | Default pages (legal pages) |
| `single.php` | Blog post |
| `index.php` | Blog listing, archives, search results |
| `404.php`, `searchform.php` | Not found page, search form |
| `template-parts/` | `hero`, `file-card` (the SVG "open file"), `stage`, `package-card`, `team-card`, `post-card`, `cta`, `callback-form`, `page-head` |
| `assets/js/main.js` | Vanilla JS: header state, menu, GSAP hero and pinned scene, scroll reveals, sticky bar, form |
| `assets/img/` | Logo mark (transparent PNG + WebP), full logo for schema, five team portraits cropped 4:5 (WebP + JPEG) |
| `assets/img/source/` | Original images from the 2023 backup, see `MANIFEST.md` |
| `content/posts/` | The three blog posts from the old site, edited, ready to paste into WordPress |
| `SITE-STRUCTURE.md`, `BRAND-FACTS.md` | Research notes on the old site and the facts the copy is allowed to use |
| `tools/` | The backup extractor used for that research |

### Where to change things

- **Business details** (WhatsApp number, greeting, email, founding year,
  community size, address): `aquariende_info()` in `functions.php`.
- **Packages** (names, included / not included, WhatsApp messages):
  `aquariende_stages()`.
- **Team**: `aquariende_team()`. Replace photos in `assets/img/` with the same
  file names (480 x 600, WebP and JPEG).
- **The four-step flow**: `aquariende_flow()`.
- Page copy for Home, Services, About and Contact lives in the templates as
  translatable strings (text domain `aquariende`). Anything typed into those
  pages in the editor is shown below the designed sections, except on Home.

### Settings constants

Set any of these in `wp-config.php` (above "That's all, stop editing") to
override the defaults:

```php
define( 'AQUARIENDE_PORTAL_URL', 'https://ops.72-62-90-174.sslip.io/login' ); // Student login target (default shown)
define( 'AQUARIENDE_FACEBOOK_GROUP_URL', 'https://www.facebook.com/groups/...' ); // empty (default) hides every Facebook link
define( 'AQUARIENDE_SHOW_ADDRESS', true );   // default false: the Pabna address stays hidden
define( 'AQUARIENDE_LEAD_WEBHOOK', 'https://ops.example/api/leads' ); // default empty: call-back requests are only emailed
```

The webhook receives a JSON POST:
`{"source":"website","stage":"S1","name":"…","whatsapp":"+880…","page":"…","submitted_at":"…"}`.

## Motion

Exactly the storyboard from the plan:

- **Hero:** the file card fades up (400 ms), the stamp scales 1.3 to 1
  (300 ms), the three rows slide in with a 90 ms stagger. About 1.2 s,
  `power3.out`.
- **How it works:** on screens at least 1024 x 600, the section pins for three
  viewport heights (scrub 0.6). Stage text crossfades (300 ms), the file ticks
  the matching row (200 ms), the highlighter sweeps under the stage name.
  Clicking a stage name jumps to its scroll position. Below that size it is
  three stacked blocks, each with its own small file, fading in at 20 % in
  view.
- **Packages:** checklist ticks fade in once, 60 ms stagger. **Team:** photos
  fade in at 30 % in view, 60 ms stagger. Everything else is CSS hover only.
- **Sticky WhatsApp bar** (phones only): slides up after the top band leaves
  the screen, hides while the call to action, form or footer is on screen.

GSAP 3.12.5 loads from cdnjs **on the front page only**, deferred.
ScrollTrigger is fetched by `main.js` only when the screen is at least
1024 x 600 (the pin), so phones never download it. All motion is off under `prefers-reduced-motion`, with `?motion=off`
in the URL (useful for checking), or if GSAP fails to load. The CSS always
holds the final state, so nothing stays hidden without JavaScript.

## Deploying

**WP Admin → Deployer for Git → Deploy Theme**

| Field | Value |
|---|---|
| Provider | GitHub |
| Repository URL | `https://github.com/hasangeeky-hue/Hurth-web` (no `.git` suffix; the plugin rejects it) |
| Branch | `main` |

It installs to `wp-content/themes/Hurth-web/`. Then activate **Aquariende**
under Appearance → Themes.

The installer replaces that folder on every deploy, so never edit theme files
on the server. Edit here, push, deploy. The theme purges known page caches
(LiteSpeed, WP Rocket, W3TC, WP Super Cache, object cache) when its version
number changes, so bump `Version` in `style.css` with each release.

## WP Admin setup checklist (owner)

1. **Settings → General:** Site title `Aquariende`; set **Administration
   email** to the inbox that should receive call-back requests (for example
   `info@aquariende.com`). Make sure the site can send email (an SMTP plugin
   or the host's mail).
2. **Pages → Add new** (titles and slugs matter for the default menu):

   | Title | Slug | Template (Page → Template) |
   |---|---|---|
   | Home | `home` | Default |
   | Services | `services` | Services |
   | About | `about` | About |
   | Blog | `blog` | Default (leave empty) |
   | Contact | `contact` | Contact |

   Legal pages, default template: `privacy-policy`, `terms-and-condition`,
   `refund_returns`, `cancellation`.
3. **Settings → Reading:** "Your homepage displays" → A static page;
   Homepage = **Home**, Posts page = **Blog**.
4. **Settings → Permalinks:** Post name.
5. **Appearance → Menus:**
   - Menu "Primary" with Services, About, Blog, Contact → location **Primary
     menu**.
   - Menu "Legal" with the four legal pages → location **Footer menu (legal
     pages)**.
   (Without menus the theme falls back to the same links.)
6. **Posts:** create categories Process, Documents, Visa, and paste the three
   posts from `content/posts/` (each file starts with its title, slug and
   category).
7. **wp-config.php:** add the constants above if you need them (Facebook group
   URL, address, webhook, portal URL).
8. **Rank Math** works as is: the theme keeps `wp_head`/`wp_footer` intact,
   prints its own meta description only when no SEO plugin is active, and
   skips its Organization schema when Rank Math is active.
9. Remove or deactivate old plugins that are no longer needed: WooCommerce and
   its add-ons, Elementor, Ultimate Member, ACF Frontend Forms, Click to Chat.

## Legal pages: the owner must fix the text

- The **Privacy Policy** on the old site is still the WordPress default
  "Suggested text". It needs a real policy before launch (it should mention the
  call-back form, WhatsApp, the portal and the one-year data retention stated
  in the terms).
- The **Cancellation** page says 48 hours' notice; the **Terms** and **Refund**
  pages say "7 days before the service start time to 7 days after". They
  contradict each other. Decide which is right and make all three agree.
- The Terms also name aamarPay as the refund gateway; check that this is still
  true.

## Honest limits

- **Facts to confirm before launch:** the 50,000-member figure dates from
  2022–23; the five team members, roles and universities are from the 2023
  site (they were students then); each person should agree to be shown.
- **Not included lists** say third-party fees (uni-assist, universities,
  embassy, blocked account) are not part of the packages. That is the usual
  arrangement; confirm it matches how Aquariende charges.
- **The file illustration** uses an invented example ("Sadia R.", ref
  AQ-0142, a deadline of 15 Jan) and is captioned as an illustration.
- **Email delivery** depends on the server. If `wp_mail` fails and no webhook
  is set, the visitor is told to use WhatsApp instead.
- **Bengali:** all strings go through the `aquariende` text domain, and IBM
  Plex Sans Bengali is loaded, but no Bengali translation ships yet. Add a
  `languages/aquariende-bn_BD.po/.mo` (or use Loco Translate) to translate.
- **Fonts and GSAP** load from Google Fonts and cdnjs. On Lighthouse's
  simulated slow-4G phone, the home page's largest paint (~3 s) is the
  headline waiting for the web font. Mobile performance on the test bench
  ranged from about 70 to 100 between runs (the test machine itself was
  noisy). Self-hosting the two font families (WOFF2, preloaded)
  is the next step if a higher mobile score matters; it would also remove a
  third-party connection.
- The **logo** is the 2022 raster logo; only the mark is used in the header,
  with the name set in type. A vector logo would be sharper.
- No comments UI: posts are articles, not discussions.
