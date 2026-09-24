# DevDoko — Implementation Plan

Laravel 12 dev-social platform. Started as capstone project, pivoting toward
Substack-for-developers: writing/publishing + follow/subscribe, kept alongside
existing social features (posts, groups, marketplace, jobs, messaging).

Full audit: see git log / PR history for `devdoko-audit` findings (2026-09-24).
Verdict: core CRUD/authz/data-layer is solid (no mass-assignment, no N+1, no
missing authz checks). Real problems are a few concrete bugs + unfinished
feature surface, not systemic rot.

## Phase 0 — Critical bugs (security-blocking, fix before anything else)
1. Stored XSS: `marketplace/show.blade.php` listing title via `addslashes()` in HTML attr.
2. OAuth account takeover: GitHub login auto-links to any local account by email match, no challenge, no exception handling.
3. Moderation gating missing on Explore + Search (blocked/hidden users' content still visible there, unlike Home feed).
4. `ProfileController::following()` renders a view that doesn't exist — crashes if ever routed to.
5. Report resolution doesn't hide content — moderation is a no-op past the admin screen.

## Phase 1 — Finish or remove half-built features
- Wire up `MarketplaceSavedSearch` (list/manage/alerts) or drop it.
- `MarketplaceCategory` model unused (categories are free text) — normalize or delete.
- `CodeSnippet` model unused — delete.
- Orphaned tables `collaborations`/`collaboration_participants`/`collaboration_applications`, `message_threads` — drop via migration.
- Missing routes for already-built controller methods: Group admin (resend/cancel invite, settings, permissions, transfer ownership), `ProjectController::toggleLike`, `MessageController::unreadCount`.
- Fix cast/fillable asymmetry (`Post::share_details`, `MarketplaceListing` boost fields).
- Add `throttle` to unprotected mutating endpoints (likes, follows, messages, group posts, interests, reports).

## Phase 2 — Substack pivot: writing core
- New `Article` content type (title, rich body, cover image, reading time, draft/publish) distinct from short-form `Post`.
- Subscribe (not just follow) to an author → notified on new article.
- Author page becomes a publication page (bio, article list, subscriber count).
- Reading feed: subscribed authors' articles prioritized.
- Claps/comments on articles.

## Phase 3 — Publishing growth (after Phase 2 ships and is used)
- Real email delivery (`MAIL_MAILER` off `log`) + queued digest mail for new articles.
- Author analytics (views, read time, subscriber growth).
- Algorithmic/topic-based article discovery.

## Phase 4 — Infra polish (ongoing, low priority)
- Real file storage (S3) instead of `local` disk for uploads.
- Extract inline `<style>`/`<script>` sprawl out of `welcome.blade.php`/`cover.blade.php`.
- Real test coverage on auth, posts, moderation gating (currently zero).

## Working rules for this project
- Fix-first: Phase 0/1 before any Phase 2+ feature work.
- Commit + push after every completed, working chunk — not one giant PR.
- No new external OAuth/payment integrations without explicit ask.
