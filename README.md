# Right Way Medical Billing — website

A complete marketing site for a U.S. medical billing / revenue cycle management
company. HTML5 + PHP includes + Bootstrap 5.3 + custom CSS + vanilla JS.
No database, no build step.

---

## Running it

```bash
cd site
php -S localhost:8000
```

Then open <http://localhost:8000>. Any standard PHP 8.1+ / Apache stack works
too — drop the folder in the web root and the `.htaccess` takes over.

Requirements: PHP 8.1 or newer with the **GD** and **cURL** extensions (GD for
the image tooling, cURL for the Pexels integration). Neither is needed to serve
the site once images are cached.

---

## Before launch — the short list

1. **`includes/config.php`** — every business detail lives here and every
   placeholder is marked `// TODO`. Phone, email, address, socials, founding
   year, the production domain, and the analytics ID.
2. **Photography of the team** — `about-us.php` uses stock portraits for the
   four account-team roles and says so on the page. Swap in real photographs and
   delete that note.
3. **Legal pages** — `privacy-policy.php` and `terms-of-service.php` are
   plain-language starting points. Have counsel review them.
4. **Rebuild the sitemap** after changing the domain:
   `php tools/build-sitemap.php`
5. **Mail delivery** — the handlers use PHP `mail()`. On most hosts you will
   want SMTP instead; every submission is also written to
   `cache/submissions/YYYY-MM.log` so nothing is lost either way.

---

## Structure

```
index.php  about-us.php  services.php  specialties.php  contact-us.php
thank-you.php  404.php  credits.php  privacy-policy.php  terms-of-service.php

services/           8 service pages    — thin files, content lives in data/
specialties/       16 specialty pages  — same

includes/
  config.php        all business constants (single source of truth)
  functions.php     helpers: rw_url, rw_asset, rw_partial, CSRF, sessions
  meta.php          the whole <head>
  seo-schema.php    JSON-LD graph
  header.php        topbar, sticky masthead, mega menus, mobile drawer
  footer.php        footer, quote modal, WhatsApp, back-to-top, scripts
  data/             services.php, specialties.php, image-map.php, images.php
  lib/              images.php (Pexels layer), form.php (handler plumbing)
  partials/         reusable components — see below
  templates/        service.php, specialty.php (shared detail-page renderers)

handlers/           contact, newsletter, pexels-proxy
assets/             css/style.css, js/main.js, img/ (logo, icons, pexels-cache)
tools/              build scripts (see below)
cache/              generated: pexels JSON, sessions, rate limits, submissions
```

### Where content lives

All copy for the 24 detail pages is in `includes/data/services.php` and
`includes/data/specialties.php`. Each page file under `services/` and
`specialties/` is four lines: it names a slug and hands off to the shared
template. Edit the data file to change a page — never the page file.

That keeps the layout in one place while every page still has genuinely unique
content: its own headline, intro, pain points, scope list, workflow, FAQs and
cross-links.

### Partials

`rw_partial('name', [...])` renders `includes/partials/name.php`:

| Partial | Purpose |
|---|---|
| `page-header` | Inner-page hero band + breadcrumb |
| `trust-strip` | Reassurance row under a hero |
| `stats` | Animated counters (`dark` / `light` variants) |
| `process` | Four-step "how it works" |
| `why-choose` | Accordion + image + progress meters |
| `testimonials` | Swiper carousel |
| `faq` | FAQ accordion (pair with `$faq_schema`) |
| `related` | Three-item cross-link module |
| `contact-form` | Full consultation form |
| `cta-banner` | Closing conversion banner |

---

## Images

Every photograph is addressed by a key declared in
`includes/data/image-map.php` with its Pexels search term, alt text and a
fallback group. An entry may also pin an exact `photo_id`, which the hero uses
so a picture chosen by eye cannot drift when Pexels search rankings change. Resolution order:

1. `includes/data/images.php` — the build manifest, pointing at locally cached
   files in `assets/img/pexels-cache/` (the normal path: no network call).
2. A live Pexels lookup, **server-side only**.
3. A bundled on-brand SVG placeholder, so a broken image is impossible.

The Pexels API key lives in `config.php` and is used only on the server. It
never appears in page source, JS, or a network request from the browser.
`handlers/pexels-proxy.php` is the only browser-reachable path to Pexels and it
returns just `src`, dimensions, alt and photographer credit.

Photographers are credited on `/credits.php`, generated from the manifest.

### The hero images are not from Pexels

The home hero uses two client-supplied files, processed by
`tools/make-hero-assets.php`:

- `hero-dr.png` — transparent cut-out, alpha-trimmed so the figure sits
  flush in its container.
- `hero-backdrop.jpg` — the clinic interior, blurred **at build time** rather
  than with a CSS filter (a runtime blur repaints on every scroll and bleeds
  transparent edges), then contrast-boosted and partly desaturated to pull its
  strong green fixtures toward the brand.

The tint over it uses `mix-blend-mode: multiply` rather than a translucent
overlay. The source is a bright, low-contrast room, so an alpha wash just
averages to muddy grey; multiplying keeps the room readable while forcing the
hue into navy/teal. There is deliberately no `home-hero` key in the image map —
leaving one would credit a photographer for a photo no longer on the site.

### Tools

```bash
php tools/fetch-images.php                  # resolve any uncached image
php tools/fetch-images.php --force          # re-resolve everything
php tools/fetch-images.php --only=home-hero # swap one weak photo
php tools/make-logo-assets.php              # rebuild all logo files + favicons
php tools/make-hero-assets.php              # re-process the two hero source images
php tools/make-fallbacks.php                # regenerate the SVG placeholders
php tools/make-og-image.php                 # regenerate the social share card
php tools/build-sitemap.php                 # regenerate sitemap.xml + robots.txt
```

---

## Brand

The palette follows the client-directed hierarchy:

| Role | Token | Value | Used for |
|---|---|---|---|
| Primary | `--rw-navy-900` | `#0E2E4F` | headings, header, dark bands, footer |
| Secondary / CTA | `--rw-teal-700` | `#0D7573` | primary buttons, links, active nav |
| Decorative teal | `--rw-teal-500` | `#17A2A0` | icons, shapes, rules, focus ring |
| Background | `--rw-sky-100` / `--rw-sky-50` | `#EAF4FB` / `#F6FAFD` | hero wash, alternating sections |
| Base | `--rw-white` | `#FFFFFF` | main background |
| Premium accent | `--rw-gold-500` | `#D4AF37` | **sparing** — see below |

Gold is deliberately restricted to seven places sitewide: the eyebrow rule,
testimonial stars, the CTA banner border and glow, the stat-chip edge, footer
heading underlines, the 404 numerals, and text selection. If you add an eighth,
it has stopped being an accent.

Contrast — every pairing verified, and re-measured on the rendered page with
alpha composited (26 elements, 0 failures):

- **Primary button** = teal-700 fill + white text → **5.5:1**.
- `teal-500` on white is only 3.1:1, so it is **decorative only** — never used
  for text. Actionable teal text uses `teal-700`.
- `gold-500` on white is 2.1:1 — never used as text on a light background;
  `--rw-gold-700` (5.3:1) exists for that.
- The focus ring is `teal-500`, which clears the 3:1 non-text threshold against
  both white (3.1:1) and navy-900 (4.4:1), so one ring works everywhere.

### Icon tones

Grouped icon wells (services, specialties, "How it works", "Who we serve",
the why-choose accordion, contact cards, values, and the scope grids on detail
pages) cycle through eight pastel tints. They are defined **once** in the
stylesheet as `[data-tone="0"]`..`[data-tone="7"]`, each setting
`--rw-tone-bg` and `--rw-tone-fg`. A component opts in by consuming those two
variables with a fallback:

```css
background: var(--rw-tone-bg, var(--rw-teal-100));
color:      var(--rw-tone-fg, var(--rw-teal-700));
```

so any well without a `data-tone` still renders in the single teal treatment.
Markup assigns the tone by loop index (`$i % 8`).

Both states are contrast-verified: glyph-on-tint at rest (4.7-6.9:1) and
white-on-tone when a card is hovered or an accordion row opens (5.3-8.3:1).
Adding a ninth tone means one line, not eight rules.

### Logo

`tools/make-logo-assets.php` regenerates every logo file from the supplied
artwork. The source is dark-green + gold on a baked-in glow, so the script:

1. strips the glow (it shows as a haze on a light header),
2. remaps green ink → navy and gold ink → teal, preserving the original 3D
   shading — the artwork is recoloured, never redrawn,
3. builds a **horizontal** lock-up for the header (the supplied stacked mark is
   ~1.55:1, which in a horizontal bar is taller than the bar itself) and keeps
   the stacked one for the footer, where height is not scarce,
4. exports light variants for dark backgrounds, plus favicons and app icons.

Re-run it if the source artwork changes. `--rw-logo-h` / `--rw-logo-gap` in the
stylesheet drive the header height, which is derived from them so the bar can
never be shorter than the logo plus its clear space.

---

## Forms

Three forms (contact, quote modal, newsletter) share one pipeline:

- CSRF token bound to the session
- Off-screen honeypot field, answered with a fake success so bots learn nothing
- Per-IP, per-form rate limit (file-backed)
- Full server-side validation independent of the client-side checks
- JSON responses to `fetch()`, redirects for a plain post — **the forms work
  with JavaScript disabled**
- Every submission logged to `cache/submissions/` as a delivery safety net

Sessions are stored in `cache/sessions/` when the host's configured
`session.save_path` is missing or unwritable — without that, CSRF validation
would silently reject every submission.

---

## Accessibility and performance notes

- Semantic landmarks, one `<h1>` per page, no skipped heading levels
- Visible focus ring on every interactive element (never removed, only restyled)
- Mega menus and the mobile drawer are keyboard operable with focus trapping
- Accordions use real buttons with `aria-expanded` / `aria-controls` and arrow-key navigation
- Every content image has descriptive alt text plus explicit `width`/`height`
- `prefers-reduced-motion` disables AOS, counters, the WhatsApp pulse and all transitions
- CSS/JS are cache-busted by file mtime; assets carry long cache headers via `.htaccess`

---

## Known limitations

- `cache/` must be writable by the web server.
- The Google Map is an unkeyed embed. For production, use the Maps Embed API
  with your own key.
- Statistics used in the copy are illustrative placeholders. Replace them with
  your real figures before launch — `terms-of-service.php` already discloses
  that they are aggregate and not a guarantee.
