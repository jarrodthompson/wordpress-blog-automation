# FGOS — Fresh Green Operating System

A multi-brand **WordPress blog automation SaaS**: research, generate, humanise, schedule
and publish content to WordPress, with live analytics pulled straight from each connected
site's WP REST API. Built as a faithful reproduction of the FGOS dashboard.

**Stack:** Laravel · Blade + Tailwind CSS v4 (compiled with Vite) · Alpine.js · Chart.js ·
Google Gemini · WordPress REST API. Runs on SQLite out of the box; MySQL in production.

> The in-app "cPanel Stack" label reads **Laravel 11 + MySQL + Gemini AI + WP REST API**
> to match the reference product. The project is generated on the current Laravel release
> (13.x); everything below is version-agnostic.

---

## Features

| Module | What it does |
| --- | --- |
| **Dashboard** | Live hero stats, workflow-stage pipeline, pipeline insights, block-mix donut, publishing cadence, content-activity chart, site performance grade, activity feed, live WP posts. |
| **Blog Editor** | Generate a draft with Gemini in the brand voice, edit HTML, set stage, save/publish. |
| **Blog Manager** | Every post filtered by workflow stage (Plan → Write → Review → Live). |
| **AI Image Generator** | Prompt-based visual briefs + the media library grid. |
| **CRM & Automations** | Trigger→action automations with run counts. |
| **Scoreboard & Orders** | Revenue, orders and status breakdown. |
| **Product Manager** | Catalogue cards with price/stock. |
| **AutoBlog Scheduler** | Scheduled queue + ready-to-schedule drafts. |
| **Activity Log** | Paginated publish/edit history. |
| **Feature Tracker** | Planned / In-progress / Shipped roadmap board. |
| **Brand DNA & Vault** | Voice, tone, pillars, keywords; encrypted credential vault. |
| **Settings & Configs** | WordPress + AI provider connection. |

## Architecture

```
app/
  Http/Controllers/   DashboardController, EditorController, ModuleController, SyncController
  Models/             Brand, Post, MediaItem, Comment, Product, Order, Activity, Feature, Automation
  Services/
    BrandContext      Resolves the active brand (query ?brand= or session) + shares nav data
    GeminiService     Blog generation via Gemini, with a local-draft fallback when no key
    WordPressService  WP REST API reads + a real HTTP homepage measurement (TTFB, weight, grade)
resources/views/
  layouts/app         Sidebar + topbar shell
  pages/              One Blade view per module (+ pages/partials for the dashboard sections)
```

- **Multi-brand:** all data is scoped to a `Brand`. The active brand is resolved once and
  shared to the sidebar/topbar via a view composer.
- **WP-synced metrics** (published count, media, TTFB, page weight, A–F grade) live in a
  `metrics` JSON on the brand and are refreshed by the **Sync Data** button
  (`WordPressService::syncSnapshot()`), which degrades gracefully offline.
- **AI generation** works with zero credentials — `GeminiService` returns a structured local
  draft when `GEMINI_API_KEY` is unset, so the editor is fully usable in development.

## Quick start

```bash
composer install
npm install
cp .env.example .env        # already set to sqlite
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
npm run build               # or: npm run dev
php artisan serve
```

Open http://127.0.0.1:8000 — the **Fresh Green Classics** demo brand is seeded with 13
pipeline posts, 237 media items and matching analytics.

## Configuration

`.env`:

```dotenv
# Local dev (default)
DB_CONNECTION=sqlite

# Production (MySQL)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=fgos
# DB_USERNAME=fgos
# DB_PASSWORD=secret

# AI generation (optional — falls back to a local template when empty)
GEMINI_API_KEY=
GEMINI_MODEL=gemini-1.5-flash
```

Per-brand WordPress credentials (`wp_url`, `wp_username`, `wp_app_password`) and the Gemini
key are stored on the `Brand` record and hidden from serialization.

## Tests

```bash
php artisan test
```
