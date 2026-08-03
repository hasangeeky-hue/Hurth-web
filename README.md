# Hurth

Child theme of **Hello Elementor** for [dev.aurenastarseed.com](https://dev.aurenastarseed.com),
deployed from this repository by the **Deployer for Git** WordPress plugin.

The repository root *is* the theme root — `style.css` sits at the top level.
Deployer for Git requires this: it unpacks the branch zip into
`wp-content/themes/<repo-name>/`, so this repo lands at
`wp-content/themes/Hurth-web/`.

## Connecting this repo to the site

**WP Admin → Deployer for Git → Install Theme**

| Field | Value |
|---|---|
| Provider | GitHub |
| Repository URL | `https://github.com/hasangeeky-hue/Hurth-web` |
| Branch | `main` |
| Private repository | unchecked |

Two things the plugin is strict about:

- The URL must have **no `.git` suffix** — the validator regex rejects the dot.
- The branch field **defaults to `master`**. This repo uses `main`, so type it.

Then activate **Hurth** under Appearance → Themes.

## Workflow after setup

1. Changes are committed and pushed to `main` here.
2. In WP Admin → Deployer for Git, click update on the package
   (or let it auto-update on commit).
3. The site pulls the new zip and replaces `wp-content/themes/Hurth-web/`.

The installer runs with `clear_destination => true`, so that folder is wiped
and replaced on every deploy. Never edit theme files directly on the server —
those edits are destroyed by the next deploy. Edit here, push, deploy.

## What deploys from here

- `style.css` — all custom CSS
- `functions.php` — hooks, filters, custom PHP
- Template overrides copied from `hello-elementor` and edited
- Custom assets (JS, images, fonts) added to this folder

## What does NOT deploy from here

This site is built with Elementor. Page layouts, sections and their styling are
stored as JSON in the `wp_postmeta` database table — not in theme files. So
pushing here **cannot** change page designs, menus, widgets or Customizer
settings. Those need the WordPress REST/MCP API or a database change.

## Local backup

The full site backup stays in this folder, untracked:

```
dev-aurenastarseed-com-20260801-095812-duurrrde6qpd.wpress   1.02 GB
```

An All-in-One WP Migration archive — 19,779 files, 0.94 GB uncompressed.
Restore it through that plugin, not via git.

The previous `wp-content/` archive that lived in this repo is still in git
history at commit `b8f613f` if it is ever needed.
