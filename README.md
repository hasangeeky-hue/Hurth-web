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
| `inc/content-installer.php` | Installs pages, posts, menus and reading settings from the theme itself; admin screen Appearance → Aquariende content |
| `inc/content/` | `pages.php`, `posts.php`, `menus.php`: the content the installer puts in, all from the 2022-23 site |
| `content/posts/` | The three original 2022 posts (lightly cleaned); the installer reads them from here |
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

## Content installer (no manual setup)

The theme installs the site content itself. Nothing has to be typed into WordPress.

- **When:** on theme activation; on the next admin page load by an administrator after a deploy that raised
  `AQUARIENDE_CONTENT_VERSION` (in `inc/content-installer.php`); after a Deployer for Git install; or via
  **Appearance → Aquariende content → Run installer again**.
- **What:** pages Home, Services, About, Contact, Blog, Privacy Policy, Terms and Condition, Refund and Returns,
  Cancellation; the three 2022 posts with their original dates (categories Process, Documents); menus
  "Primary" and "Legal" in their locations; static front page = Home, posts page = Blog, privacy page;
  site title "Aquariende", tagline "Consulting"; permalinks `/%postname%/`. The admin email is never changed.
- **Safe to repeat:** every item carries the meta `_aquariende_content_key` and is found by it, so nothing is
  duplicated. Existing items are only rewritten when the version rises, and never if they were edited in
  WordPress since they were installed. Items in the trash are left alone. A log (last 50 lines) is on the
  admin screen.
- **Old content:** "Move Friends Mobile content to trash" (with a confirm step) moves every page and post
  without the key to the trash (restorable) and removes old menus from the theme locations (menus have no
  trash in WordPress, so they are kept). Then the installer pages get their clean slugs (for example
  `/services/` instead of `/services-2/`).
- **Changing content:** edit `inc/content/*.php` or `content/posts/*.html`, raise `AQUARIENDE_CONTENT_VERSION`,
  push and deploy.

Still to do by hand: set **Settings → General → Administration email** to the inbox for call-back requests,
make sure the site can send email, add the `wp-config.php` constants you need, and remove old plugins
(WooCommerce and add-ons, Elementor, Ultimate Member, ACF Frontend Forms, Click to Chat).

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
- **Not from the old site:** the WhatsApp/portal flow (pay the consultation fee, portal link by email,
  upload and book in the portal, nothing paid on this website) follows the owner's 2026 instructions,
  not the 2022 text. The file illustration shows generic labels only.
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
