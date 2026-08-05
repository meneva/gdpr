# Data Breach Module

Same five-piece pattern as the SAR module: migration → model → policy →
controller → views. This one tracks the 72-hour ICO notification window
instead of the SAR's 30-day statutory deadline.

## 1. Copy these in

```
database/migrations/2026_08_05_100000_create_data_breaches_table.php
app/Models/DataBreach.php
app/Models/Company.php                              ← updated, adds dataBreaches() relation
app/Http/Requests/StoreDataBreachRequest.php
app/Http/Requests/UpdateDataBreachRequest.php
app/Policies/DataBreachPolicy.php
app/Http/Controllers/DataBreachController.php
resources/views/breaches/index.blade.php
resources/views/breaches/create.blade.php
resources/views/breaches/edit.blade.php
resources/views/breaches/show.blade.php
routes/company.php                                   ← OVERWRITES yours, adds data-breaches routes
```

`routes/company.php` here is your existing one with two additions: the
`DataBreachController` import and a `Route::resource('data-breaches', ...)`
line inside the tenant-scoped group. If you've made other local changes
to this file, diff before overwriting rather than copying blind.

## 2. Migrate

```bash
php artisan migrate
```

Creates `data_breaches`. Uses the `breach_sequence` counter that was
already added to the `companies` table back in the tenancy-core
migration — no new sequence column needed.

## 3. Update the nav

Apply the updated `_navigation-menu.PATCH.blade.php` — adds a
"Breaches & Incidents" link alongside the existing SAR and Members links.

## 4. Test

```bash
php artisan route:list --path=breaches
```

Then in the browser: `/data-breaches` → report an incident → confirm the
ref number (`BRC-2026-001`) and 72-hour countdown appear, shown in hours
rather than days since the window is so much shorter than a SAR's.

## What's different from the SAR module, worth noting

- **Hours, not days.** `hoursRemaining()` on the model and the stamp
  logic in the views work in hours — a SAR has weeks to breathe, a
  breach has 72 hours, so the UI needs to communicate urgency much
  faster (red kicks in at 24h remaining, not just when overdue).
- **`resolved_at` auto-stamps.** When the status is changed to
  `resolved` and no explicit resolved timestamp was given, the
  controller fills it in automatically — one less manual field to
  remember when closing out an incident.
- **No `assigned_to` field.** Unlike SARs, this module didn't include
  an assignee dropdown — add one the same way the SAR module did
  (`company members` lookup passed to create/edit) if you want that.

## Next module

DPIAs are next in the roadmap — same pattern again, with a review-due
date instead of a hard statutory deadline, and a risk-level field driving
the workflow instead of a countdown. Say the word when you're ready.
