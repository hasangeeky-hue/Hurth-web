# Aquariende WordPress theme (v2.0.0)

A standalone classic WordPress theme for **Aquariende**. It holds guides and
services for students from Bangladesh who want to study in Germany. It keeps
Concept A, "The open file":

- colours: sea ink, paper, aqua as the one action colour, the highlighter, and stamp red;
- fonts: Bricolage Grotesque and IBM Plex Sans.

Everything else was rebuilt from the strategy in `strategy/` (00–06).

**There is:**

- no page builder, no parent theme and no plugin dependency;
- no shop, no prices and no forms;
- no third-party request on page load.

## Hard rules built into the code

- **Contact.** The only contacts are WhatsApp on +49 1575 0129620 and info@aquariende.com.
- **One action per page.** Every page has a single WhatsApp action. Its prefilled message ends in a page code, for example `… [G-BLOCKED]`, so the ERP inbox can see which page brought the lead. The header button, the inline button, the sticky phone bar and the footer link all carry the same code; their `data-wa="CODE:position"` tells them apart.
- **No money in the text.** No price and no money amount appears anywhere. The installer **refuses** any item whose title, text, excerpt or fields contain a currency symbol or an amount, and logs a `REFUSED` line. Figures that change (blocked-account amount, fees, waiting and processing times, deadlines) are never written as text. A *VolatileFact* card links to the official page instead: `[aq_volatile topic="…"]`.
- **No claims without a source.**
  - No testimonials, success rates or "first/best/only" claims.
  - "No one can guarantee a visa" is said on Home, Services, the visa pages, the footer and `llms.txt`.
  - Every claim traces to the old site, the owner's process documents, BRAND-FACTS.md, or an official source. Official sources are listed with their URL and an "accessed" date (`inc/content/sources.php`).
- **Removed from version 1:** the call-back form, GSAP/ScrollTrigger, the pinned scroll scene, Google Fonts, and the "Why are we the best?" table.

## Structure

| Path | What it is |
|---|---|
| `style.css` | Theme header (Version 2.0.0), `@font-face` (self-hosted), tokens, every component, responsive rules, reduced motion, print. About 9 KB gzipped. |
| `functions.php` | Settings constants only; loads `inc/`. |
| `inc/data.php` | `aquariende_info()` (all business facts), the three services, the 6-stage journey, the 5-step flow, the "we never" list, "have these ready", how we work, team seed data, and the source and volatile-fact loaders. |
| `inc/helpers.php` | URLs by content key, WhatsApp links with page codes, icons, images, logo, breadcrumbs, the navigation walker (disclosure buttons for submenus). |
| `inc/setup.php` | Theme supports and menus. Assets: one stylesheet, one deferred script, 2 preloaded fonts, no block CSS. Emoji and third-party hints removed. Cache purge and rewrite flush after a deploy. |
| `inc/post-types.php` | Custom post types `aq_guide` (`/guides/…`, archive `/guides/`), `aq_term` (glossary, not public), `aq_faq` (not public) and `aq_person` (`/about/team/…`, shown only with consent). Taxonomies `aq_stage` and `aq_faq_group`. All meta fields are registered with `show_in_rest`. |
| `inc/components.php` | Shortcodes for editable content: `[aq_volatile]`, `[aq_deadline]`, `[aq_stage_guides]`, `[aq_never]`, `[aq_next_steps]`, `[aq_have_ready]`, `[aq_service_link]`, `[aq_link key="…"]`. Content filters: glossary links on first use, "(external site)" labels, heading anchors. FAQ, related-guides and table-of-contents queries. |
| `inc/seo.php` | Title (`… · Aquariende`), meta description (answer block), canonical without query strings, Open Graph, robots (staging noindex), robots.txt with the AI crawler allow list, core XML sitemaps, `/llms.txt` and `/llms-full.txt`, optional IndexNow. |
| `inc/schema.php` | One JSON-LD `@graph` per page: ProfessionalService, WebSite, WebPage types, BreadcrumbList, Service (no offers), Article with citations, HowTo (How it works), DefinedTermSet (glossary), FAQPage (only on /faq/), Person (only with consent). |
| `inc/i18n-bn.php` | The Bengali structure (`/bn/…`, hreflang pairs, `<html lang>`, language switch). Off unless `AQUARIENDE_ENABLE_BN` is true. |
| `inc/redirects.php` + `inc/content/redirects.php` | Old URLs: 301 to their new homes, or 410 for the shop and test pages. Applied only to requests that would otherwise be a 404. |
| `inc/content-installer.php` | The content installer (version 2). |
| `inc/admin.php` | The "Aquariende fields" box, the guides "Next review" column, and Appearance → Aquariende content. |
| `inc/cli.php` | `wp aquariende schema-check <url>` and `wp aquariende install`. |
| `inc/content/*.php` | Installer data: `pages`, `guides`, `terms`, `faqs`, `menus`, `posts`, `retired`, `redirects`, `sources`, `volatile`. |
| `content/pages/*.html`, `content/guides/*.html`, `content/legal/*.html` | Page and guide bodies. The first line is a comment naming the sources; it is stripped on install. |
| Templates | `front-page.php`, `page-pillar.php`, `template-services.php`, `template-service.php`, `template-how.php`, `archive-aq_guide.php`, `single-aq_guide.php`, `template-glossary.php`, `template-faq.php`, `template-about.php`, `single-aq_person.php`, `template-parents.php`, `template-contact.php`, `template-login.php`, `page.php`, `single.php`, `index.php`, `404.php` (with `template-parts/gone.php` for 410). |
| `template-parts/` | answer, page-head, breadcrumbs, journey-map, verify-strip, never-promised, what-happens-next, have-these-ready, volatile-fact, deadline-fact, source-list, review-stamp, author-box, related, toc, faq, wa-cta, guide-card, post-card, team-card, share-family, lang-switch, hero, file-card. |
| `assets/fonts/` | WOFF2 files, with their OFL licences. Bricolage Grotesque and IBM Plex Sans (latin, variable) are preloaded. Noto Sans Bengali loads only when Bengali text appears. IBM Plex Sans Bengali is not published as a web font, so Noto Sans Bengali replaces it. |
| `assets/img/og/` | Open Graph cards, 1200×630: default, guides, services. |
| `strategy/` | The strategy (00–06) and `NOT-BUILT.md`. |

## Settings (wp-config.php, above "That's all, stop editing")

```php
define( 'AQUARIENDE_PORTAL_URL', 'https://portal.example' ); // Student login target (default: the current ops portal)
define( 'AQUARIENDE_NOINDEX', true );         // force noindex + disallow-all robots.txt (hosts *.hostingersite.com are always noindex)
define( 'AQUARIENDE_ENABLE_BN', false );      // Bengali pages, hreflang and language switch
define( 'AQUARIENDE_SAMEAS', 'https://www.facebook.com/…' ); // confirmed profiles for schema sameAs, comma separated
define( 'AQUARIENDE_FACEBOOK_GROUP_URL', '' ); // shows the community link when set
define( 'AQUARIENDE_SHOW_ADDRESS', false );   // Pabna address on Contact, footer and schema
define( 'AQUARIENDE_INDEXNOW_KEY', '' );      // optional IndexNow (production only)
```

The header "Student login" link goes straight to `AQUARIENDE_PORTAL_URL`. The
`/student-login/` page explains how to recognise the real portal link. Old
`/login/`, `/my-account/` and similar URLs redirect to it.

## How content is stored and edited

- **Where it lives.** Pages, guides, glossary terms and FAQ items are ordinary WordPress content, created by the installer.
- **Editing.** Edit them in WordPress like any other post. Each item has an **"Aquariende fields"** box for its answer block (with a live 40–60 word count), sources (one per line: `Publisher | Title | URL | accessed YYYY-MM-DD`), review dates, WhatsApp page code and message, and related guides.
- **Owner edits are safe.** Every installed item carries `_aquariende_content_key`, a text hash and a field hash. Once you edit an item, later theme versions **never** overwrite it. The log says "Kept … as it is".
- **FAQ items** are stored once. "Show on" lists the content keys (for example `home, guide-visa, faq`) where the question appears. Only `/faq/` prints FAQPage schema.
- **Glossary terms** link automatically: the first time a term or one of its aliases appears in a guide, it links to `/glossary/#slug`.
- **Team members** (Team in the admin menu) appear nowhere — no card, no page, no author line, no schema — until **consent** is ticked and the entry is published.
- **Appearance → Aquariende content** shows:
  - installer status and indexing mode;
  - **guides due for review** (red when the next-review date has passed);
  - quality warnings per guide (answer length, sources, FAQ items, at least 3 inbound links, review date);
  - missing consent;
  - a button to move the previous site's content to the trash (with a confirmation step);
  - the log.

### How to add a guide

1. **Write the guide.** Create `content/guides/<slug>.html`. The first line is a comment naming the source keys. Use the shortcodes `[aq_volatile topic="…"]` and `[aq_deadline topic="…"]`, and never write a figure that changes.
2. **Register any new source.** Add it to `inc/content/sources.php` (publisher, title, URL, the date you opened it). Add a new changing figure to `inc/content/volatile.php`.
3. **Add the guide's entry** to `inc/content/guides.php`:
   - title and slug;
   - `stage` (eligibility, applications, offer, money, visa or arrival);
   - `priority`, `service` and `sources`;
   - `related` (other guide keys);
   - `meta`: question, a 40–60 word answer, SEO title, WhatsApp code `G-…` and message, `_aq_reviewed`, `_aq_next_review`.
4. **Add 3–6 FAQ items** to `inc/content/faqs.php` with `pages` including the new key.
5. **Link the guide from at least two other pages,** for example with `[aq_link key="guide-…"]…[/aq_link]`.
6. **Raise the version and deploy.** Raise `AQUARIENDE_CONTENT_VERSION` in `inc/content-installer.php`, bump `Version` in `style.css`, then commit, push and deploy. The next admin page load installs the guide.

You can also add a guide directly in WordPress (Guides → Add new). Fill in
"Aquariende fields" and choose a journey stage.

## Upgrading staging from version 1 (what happens on deploy)

1. Deployer for Git replaces the theme files. The new theme version purges known page caches and flushes rewrite rules.
2. The next admin page load by an administrator runs installer version 2 (or Appearance → Aquariende content → Run installer again). It:
   - creates the new pages (pillar, 3 service pages, How it works, For parents, Glossary, FAQ, Student login), 9 guides, 14 glossary terms, 45 FAQ items, and 5 draft team records without consent;
   - updates the version 1 pages nobody edited (Home, Services, About, Blog → "Updates"). Edited ones are kept;
   - leaves the four legal pages as they are;
   - sets the three 2022 posts to draft (unless edited); their old URLs redirect;
   - rebuilds the Primary menu with the two dropdowns (unless it was edited), sets the tagline, and flushes permalinks.
3. Pages listed in `inc/content/retired.php` are moved to the trash, but only if they carry the installer key and were not edited. Version 2 lists none.

## Deploying

**WP Admin → Deployer for Git → Deploy Theme**, provider GitHub, repository
`https://github.com/hasangeeky-hue/Hurth-web` (no `.git` suffix), branch
`main`. It installs to `wp-content/themes/Hurth-web/`.

Never edit theme files on the server. Edit here, push, deploy. Bump `Version`
in `style.css` with each release, and `AQUARIENDE_CONTENT_VERSION` whenever
anything in `inc/content/` or `content/` changes.

**Staging** (any `*.hostingersite.com` host, or `AQUARIENDE_NOINDEX`) sends:

- `noindex,nofollow` as a meta tag and an `X-Robots-Tag` header;
- a disallow-all `robots.txt`.

**Production** allows crawling, with explicit allow lines for Googlebot,
Bingbot, Google-Extended, OAI-SearchBot, ChatGPT-User, GPTBot, PerplexityBot,
ClaudeBot, Claude-User and others. It serves `/llms.txt`, `/llms-full.txt` and
`/wp-sitemap.xml`, the sitemap listing pages, posts, guides and consented team
members.

**Launch checklist.** Use 06 §10, plus these steps:

- remove the leftover plugins (WooCommerce and add-ons, Elementor, Ultimate Member, ACF Frontend Forms, Click to Chat);
- run "Move the old content to the trash" once the owner agrees;
- choose a cookieless analytics tool. `main.js` already sends `wa_click`, `email_click`, `portal_click`, `lang_switch`, `source_click` and `share_family` to `window.plausible` or `window.umami` if one is present.

If Rank Math, Yoast, AIOSEO or SEOPress is active, the theme does not print its
own description, canonical, Open Graph or JSON-LD. Robots and staging noindex
stay with the theme.

## Honest limits

- The team section, team pages and guide author lines stay empty until consent is recorded. That is intended.
- All official sources were opened on 21 November 2026. The visa, blocked-account, documents, costs and APS guides are due for review on 21 February 2027; the others on 21 May 2027.
- The bench measurements (Lighthouse, schema validation) were taken on a disposable test WordPress, not on Hostinger, where TTFB depends on the page cache.
