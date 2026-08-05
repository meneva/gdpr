# SAR Module — Blade Views

This adds the views on top of the tenancy core + SAR module you already
copied in. It also makes two small changes to the controller you already
have, to support an "assign to" dropdown.

## 1. Copy these in

```
resources/views/components/status-stamp.blade.php
resources/views/sars/index.blade.php
resources/views/sars/create.blade.php
resources/views/sars/edit.blade.php
resources/views/sars/show.blade.php
```

`status-stamp` is a shared component — it'll be reused by every future
module (breaches, DPIAs, suppliers, training) for the same overdue/
due-soon/on-track badge styling, so it's worth keeping generic like this
rather than duplicating the markup per module.

## 2. Update your SubjectAccessRequestController

Your existing controller's `create()` and `edit()` methods need to pass an
`$assignees` collection (the current company's members) to the views, plus
a small helper method to fetch them. Apply this diff:

```php
public function create(): View
{
    $this->authorize('create', SubjectAccessRequest::class);

    return view('sars.create', [
        'assignees' => $this->companyMembers(),
    ]);
}
```

```php
public function edit(SubjectAccessRequest $sar): View
{
    $this->authorize('update', $sar);

    return view('sars.edit', [
        'sar' => $sar,
        'assignees' => $this->companyMembers(),
    ]);
}
```

And add this method to the bottom of the class:

```php
protected function companyMembers()
{
    return \App\Models\Company::query()
        ->whereKey(session('current_company_id'))
        ->first()
        ?->users
        ?? collect();
}
```

(If you'd rather have the full controller file with these changes already
applied, just ask and I'll hand you the whole file instead of a diff.)

## 3. Add the nav link

Open `resources/views/navigation-menu.blade.php` (Jetstream generated this
for you already) and add the link shown in
`resources/views/_navigation-menu.PATCH.blade.php` — one line in the
desktop nav section, one in the mobile nav section, next to the existing
Dashboard link.

## 4. Try it

```bash
php artisan serve
```

Log in, and you should be able to visit `/sars`, log a request, see the
30-day countdown on the index page, edit status/assignee, and delete.

## What this looks like once it's working

- **Index** — a table of all requests for the current company, each row
  showing a status stamp: green (completed), red (overdue), amber (due
  within 7 days), teal (on track).
- **Create** — logs a new request; ref number and deadline are generated
  server-side, not shown as editable fields.
- **Edit** — same fields plus status transitions and reassignment.
- **Show** — a read-only detail view, linked from the ref number anywhere
  it appears.

## Next module

Same five pieces, new fields: migration → model → form requests → policy
→ controller → views. Data Breaches is next in the roadmap (72-hour ICO
countdown instead of the SAR's 30-day one) — say the word and I'll build
it the same way.
