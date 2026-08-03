# Hurth

Standalone WordPress theme for **Friends Mobile** — Mobile & DHL Service Center,
Luxemburger Straße 96, 50354 Hürth.

**No page builder. No parent theme. No plugin dependencies.**

## Why this exists

The site was built in Elementor, with the layout stored as JSON in the database.
That made the design unreachable from git. But the pages also carry their
content as **plain HTML in `post_content`** — headings, paragraphs, images and
lists — so a hand-written theme can render all of it directly.

This theme does exactly that. Everything visual now lives in files, in git.

## Files

| File | Purpose |
|---|---|
| `style.css` | Full design system — tokens, layout, components, responsive |
| `functions.php` | Theme setup, menus, asset loading, business details |
| `header.php` | Sticky header, responsive navigation |
| `footer.php` | Footer with address and service area |
| `front-page.php` | Home — hero, page content, service cards, latest posts |
| `page.php` | Standard pages |
| `single.php` | Blog posts |
| `index.php` | Blog listing, archives, search |
| `404.php` | Not found |

Business details (address, service area, tagline) are centralised in
`hurth_info()` in `functions.php` — edit them once, there.

## Deploying

**WP Admin → Deployer for Git → Deploy Theme**

| Field | Value |
|---|---|
| Provider | GitHub |
| Repository URL | `https://github.com/hasangeeky-hue/Hurth-web` |
| Branch | `master` or `main` — both exist and track the same commit |

The URL must have **no `.git` suffix**; the plugin's validator regex rejects it.

Installs to `wp-content/themes/Hurth-web/`. Activate **Hurth** under
Appearance → Themes.

The installer runs with `clear_destination => true`, so that folder is wiped and
replaced on every deploy. **Never edit theme files on the server** — those edits
are destroyed by the next deploy. Edit here, push, deploy.

## What still needs a plugin

Honest limits of going plugin-free:

- **Contact forms.** The contact page markup is Contact Form 7 output. Without
  CF7 active the form renders but will not send. It needs either CF7 kept on, or
  a custom handler added to `functions.php`.
- **Elementor layouts.** Section arrangement inside pages came from Elementor.
  This theme restyles the content it left behind; it does not reproduce
  builder-specific layouts.

## Local backup

```
dev-aurenastarseed-com-20260801-095812-duurrrde6qpd.wpress   1.02 GB
```

All-in-One WP Migration archive, 1 Aug 2026 — 19,779 files, 0.94 GB
uncompressed, including the full database. Untracked and gitignored.

**Do not re-run the import.** AIOWM clears `template`, `stylesheet` and
`active_plugins` before importing and only restores them on success. A failed
import — likely, since the free tier caps well below 1 GB — leaves the site with
no active theme and no active plugins, which renders a completely blank front
end while `wp-login.php` still works.

The earlier `wp-content` archive of this repo remains in history at `b8f613f`.


## Photography

Placeholder photos are from [Pexels](https://www.pexels.com/license/) under the
Pexels licence: free for commercial use, no attribution required, no permission
needed. Sourced from the phone-repair collection and verified individually
before use.

| File | Used for |
|---|---|
| images/hero-repair.* | Front page hero |
| images/service-hands.* | Photo band — Repair |
| images/workbench.* | Photo band — Honest diagnosis |
| images/detail-board.* | Photo band — Precision |

Each ships as WebP with a JPEG fallback via hurth_picture(), with explicit
width and height so nothing shifts while loading. Total payload 375 KB.

**These are placeholders.** Real photographs of the Hürth shop, the workbench
and the team will outperform any stock image for a local trust business.
Replace the files in images/ keeping the same names, or set a featured image
on the front page — that takes precedence over the hero photo automatically.

## 360 degree product viewer

hurth_spin( \, \ ) renders a drag-to-rotate viewer. It activates only
when a frame sequence exists:

`
images/spin/<set>/frame-01.jpg
images/spin/<set>/frame-02.jpg
...
`

At least 8 frames are required; 24-36 gives smooth rotation. With fewer, it
falls back to a single still so nothing breaks. Shoot on a turntable with
fixed lighting and a constant camera position.

Supports pointer drag, touch swipe and left/right arrow keys.
