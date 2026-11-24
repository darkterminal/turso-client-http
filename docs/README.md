# Turso Client HTTP Docs

This directory contains the GitHub Pages site for **Turso Client HTTP**: a landing page and a user manual.

The manual in `docs/user-manual/` is the **source of truth** for package documentation. The GitHub wiki is legacy and should point readers here.

## Local preview

From the repository root:

```bash
cd docs
bundle install
bundle exec jekyll serve --baseurl /turso-client-http
```

Then open `http://127.0.0.1:4000/turso-client-http/`.

## Structure

- `index.html` — landing page
- `user-manual/` — manual pages (Markdown, `manual` layout)
- `_data/nav.yml` — manual sidebar navigation
- `assets/css/` — landing and manual styles
- `images/` — logo and static assets
