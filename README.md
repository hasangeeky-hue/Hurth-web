# Hurth-web

Working repository for **dev.aurenastarseed.com** (WordPress development site).

## Site facts

| | |
|---|---|
| Site URL | `https://dev.aurenastarseed.com` |
| WordPress | 6.8.6 |
| PHP | 8.3.30 |
| Database | MariaDB 11.8.8 (prefix `wp_`) |
| Active theme | `hello-elementor` |
| Page builder | Elementor |
| Host | Hostinger (LiteSpeed) |

## What is in this repository

Only version-controllable site code lives here:

```
wp-content/
  themes/
    astra/            stock Astra theme
    hello-elementor/  stock Hello Elementor theme (active)
    thetintteam/      Astra child theme (currently an empty stub)
  mu-plugins/         host-injected must-use plugins
  fonts/
```

## What is deliberately NOT in this repository

These are excluded by [`.gitignore`](.gitignore) and must stay out:

| Excluded | Why |
|---|---|
| `*.wpress` | The 1.02 GB site backup. Exceeds GitHub's 100 MB per-file hard limit. |
| `database.sql` | Contains `wp_users` (emails + password hashes) and `wp_options` (API keys, licence keys). |
| `wp-content/uploads/` | 686 MB media library — synced separately, not source. |
| `wp-content/package.json` | Migration metadata leaking the hosting absolute path and account name. |
| `wp-content/wflogs/` | Wordfence runtime state. |
| `wp-config.php`, `.env` | Site credentials. |

## Local backup

The full site backup is kept **locally only**, in this folder:

```
dev-aurenastarseed-com-20260801-095812-duurrrde6qpd.wpress   1.02 GB
```

It is an All-in-One WP Migration archive: 19,779 files, 0.94 GB uncompressed.
Restore it by uploading through the All-in-One WP Migration plugin, not via git.

## Important: the design is in the database, not in these files

This site is built with Elementor. Page layouts, section structure and styling
are stored as JSON inside `wp_postmeta` in the database — not in theme files.
The theme layer in this repository is stock vendor code, so **committing and
deploying this repository does not move the site's design.** Moving the design
requires the database, which is excluded here for security reasons.
