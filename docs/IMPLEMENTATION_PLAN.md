# DevDoko — Implementation Plan

Laravel 12 dev-social platform. Started as capstone project, pivoting toward
Substack-for-developers: writing/publishing + follow/subscribe, kept alongside
existing social features (posts, groups, marketplace, jobs, messaging).

Full audit: see git log / PR history for `devdoko-audit` findings (2026-09-24).
Verdict: core CRUD/authz/data-layer is solid (no mass-assignment, no N+1, no
missing authz checks). Real problems are a few concrete bugs + unfinished
feature surface, not systemic rot.

## Phase 0 — Critical bugs — DONE (2026-09-24, commit 043297f)
1. Stored XSS: `marketplace/show.blade.php` listing title via `addslashes()` in HTML attr. Fixed with `@js()`.
2. OAuth account takeover: GitHub login auto-linked to any local account by email match, no challenge, no exception handling. Fixed: only auto-links by verified `github_id`, email collisions now bounce to login instead of silent auth.
3. Moderation gating missing on Explore + Search. Fixed: both now use `Post::visibleTo()` / `hiddenUserIds()`.
4. `ProfileController::following()` rendered a nonexistent view. Fixed: points at `follow.following`.
5. Report resolution didn't hide content. Fixed: `resolveReport()` now soft-deletes the reported content.

## Phase 1 — Finish or remove half-built features — DONE (commits 183238f, 3430307, 85c30d1, 96a87e7)
- `MarketplaceSavedSearch`: fixed validation (was checking fields the form doesn't send), added list/delete UI, wired a Save Search button — previously the endpoint was unreachable from any view.
- `MarketplaceCategory`, `CodeSnippet` models: deleted, zero references anywhere.
- Orphaned tables (`collaborations`/`collaboration_participants`/`collaboration_applications`, `message_threads`, `code_snippets`, `marketplace_categories`): drop migration written (`2026_09_24_182641_drop_orphaned_unused_tables.php`) — **not run yet**, needs `php artisan migrate` manually (sandbox blocks destructive DB ops; back up first if this DB has real data).
- Wired 7 routes for already-built controller methods: group invite resend/cancel, group settings/permissions, group ownership transfer, project like, message unread count.
- Fixed `Post::share_details` cast, `MarketplaceListing` boost-field fillable gap.
- Added `throttle:60,1` across the whole authenticated route group.
- Found the app already had a private-posts-as-drafts mechanism (route/controller/view all present) that was simply never linked from any UI — added nav link + fixed `publish()` to notify followers (previously silent).

## Phase 2 — Substack pivot: writing core
Turns out most of the backend already existed, just unreachable — same pattern as Phase 1. Don't rebuild what's there.
- `article` is already a valid `Post` type with its own icon/label, and `posts/create.blade.php` is already a full-page long-form editor (markdown preview, image paste, reading time, tags) — NOT the cramped modal composer. No new content type needed.
- Draft-before-publish already works via the private-posts mechanism fixed in Phase 1 (`My Drafts` nav link → mark a post Private → publish later from the drafts list).
- Follow + `notifyFollowers()` already covers "subscribe to an author, get notified of new content" — no separate Subscribe model needed, would've been a duplicate abstraction.
- Added: Articles nav link to `/posts?type=article` (the filter already existed in `PostController::index`, just wasn't exposed in any nav) — commit 38a9db9.
- **Still open** (real gaps, not yet done):
  - Author page isn't a real "publication" page yet — profile shows all post types mixed, no article-only view, no subscriber count.
  - No reading-feed weighting that prioritizes articles from people you follow over short posts.
  - No claps (separate from likes) on articles specifically — likes work today, claps would be new UI/UX, low priority.

## Post-audit fix — privacy leak (commit 7e9cb51)
Found while building Phase 2, more severe than anything in the original audit:
`ProfileController::show()` listed a user's posts with **zero visibility
filtering**, and `profile.show` has no auth middleware — any visitor, logged
in or not, could see another user's private/draft posts by opening their
profile. Same gap existed in `HomeController`'s Trending sidebar query and
`TagController::show()` (partial — only checked `visibility=public`, missed
blocked-user gating). All three now use `Post::visibleTo()`. Also deleted
`PostService`/`FeedService` — dead code, zero references, and both had the
same missing-filter bug plus a broken `orWhere` in `FeedService` that
ignored its own following-filter.

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
