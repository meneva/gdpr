# GDPR Compliance SaaS — Starter (Tenancy core + SAR module)

This is Phase 2–4 of the roadmap, coded: company tenancy (single database,
shared schema) plus the first compliance module (Subject Access Requests)
built all the way through — migration → model → policy → controller →
routes. Every later module (breaches, DPIAs, suppliers, training) repeats
this exact shape.

## 1. Start from a fresh install

```bash
composer create-project laravel/laravel gdpr-saas
cd gdpr-saas
composer require laravel/jetstream
php artisan jetstream:install livewire --teams
```

## 2. Copy these files in

Copy everything in this zip into your project at matching paths, **except**
`app/Models/User.php.PATCH.php` — that one is not a real file, see step 3.

```
database/migrations/2026_08_02_100000_create_companies_table.php
database/migrations/2026_08_02_100001_add_current_company_id_to_users_table.php
database/migrations/2026_08_02_100002_create_company_user_table.php
database/migrations/2026_08_02_100003_create_company_invitations_table.php
database/migrations/2026_08_02_100004_create_subject_access_requests_table.php
app/Models/Concerns/BelongsToCompany.php
app/Models/Concerns/GeneratesRefNumbers.php
app/Models/Company.php
app/Models/CompanyInvitation.php
app/Models/SubjectAccessRequest.php
app/Support/Deadlines.php
app/Http/Middleware/EnsureCompanySelected.php
app/Http/Controllers/CompanySwitchController.php
app/Http/Controllers/SubjectAccessRequestController.php
app/Http/Requests/StoreSubjectAccessRequestRequest.php
app/Http/Requests/UpdateSubjectAccessRequestRequest.php
app/Policies/SubjectAccessRequestPolicy.php
routes/company.php
```

Delete Jetstream's `database/migrations/*_create_teams_table.php`,
`*_create_team_user_table.php`, and `*_create_team_invitations_table.php` —
`companies`, `company_user`, and `company_invitations` replace them.

## 3. Patch app/Models/User.php

Jetstream's generated `User.php` already has real content in it (API
tokens, profile photo, two-factor auth). Don't overwrite it — open
`app/Models/User.php.PATCH.php` in this zip and merge the imports and
methods into your existing `User` class by hand. It's about 15 lines.

## 4. Register the middleware and route file

In `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'ensure.company.selected' => \App\Http\Middleware\EnsureCompanySelected::class,
    ]);
})
->withRouting(
    // ...your existing web/api entries...
    then: function () {
        Route::middleware('web')->group(base_path('routes/company.php'));
    },
)
```

Policies don't need explicit registration — Laravel auto-discovers
`SubjectAccessRequestPolicy` from the `SubjectAccessRequest` model name by
convention, as long as it's in `app/Policies`.

## 5. Migrate

```bash
php artisan migrate
```

## 6. What you get at this point

- A user can belong to multiple companies via `company_user`, each with a role.
- `session('current_company_id')` drives every query automatically — any
  model using the `BelongsToCompany` trait is invisibly scoped to it.
- Creating a `SubjectAccessRequest` auto-generates a ref number
  (`SAR-2026-014`) and a 30-day deadline from `received_at`, race-safe
  under concurrent writes via a row lock on the company.
- `sars.index/create/store/show/edit/update/destroy` routes exist, each
  policy-checked by company role (owner/admin/editor can create & edit,
  admin/owner can delete, viewer can only view).

## 7. What's not included yet (on purpose)

- **Blade views** — `sars.index`, `sars.create`, etc. don't exist yet.
  Wire these up next; the controller/policy layer is stable underneath
  whatever UI you build (Blade, Livewire, or an API + separate frontend).
- **Company creation/invitation UI** — the `companies.create` route
  referenced in the middleware redirect needs a controller + view.
- **The other five modules** (breaches, DPIAs, suppliers, RoPA, training)
  — copy the SAR module's five files (migration, model, request×2, policy,
  controller) and swap the field names and deadline rule. `Deadlines.php`
  already has `breachNotifyDeadline()` waiting for the breach module.

Happy to build out Blade views for the SAR module, or scaffold the Data
Breach module next using this same pattern — just say which.
