# Company Onboarding Module

Fixes the `RouteNotFoundException` that was surfacing as a 404 in
production, and adds the full flow: create a company, invite teammates by
email, accept an invitation, switch between companies.

## 1. Copy these in

```
app/Policies/CompanyPolicy.php
app/Http/Controllers/CompanyController.php
app/Http/Controllers/CompanyInvitationController.php
app/Mail/CompanyInvitationMail.php
resources/views/emails/company-invitation.blade.php
resources/views/companies/create.blade.php
resources/views/companies/members.blade.php
resources/views/components/company-switcher.blade.php
routes/company.php          ← OVERWRITES your existing one, see below
```

## 2. routes/company.php has been restructured — read this carefully

The previous version put every route (including `sars.*`) behind
`ensure.company.selected`. That's exactly what caused your production
404: a brand-new user has no company, gets redirected toward
`companies.create`, but that route didn't exist yet — so Laravel threw
`RouteNotFoundException`, which rendered as a 404-looking error page.

The new file splits routes into two groups:

- **`['auth', 'verified']`** — `companies.create`, `companies.store`,
  `invitations.accept`. Reachable with *no* company selected. This is the
  escape hatch a new user needs.
- **`['auth', 'verified', 'ensure.company.selected']`** — everything else
  (SARs, member management, company switching). Requires an active
  company in session, which by now always exists because of the group
  above.

Just replace your existing `routes/company.php` with the one in this zip
— it's the same file, restructured, with the new routes added.

## 3. Set up mail (or don't — invites still work either way)

`CompanyInvitationController::store()` tries to send an email via
whatever's in your `.env` `MAIL_*` config. If mail isn't configured yet
(likely, on a server you're still standing up), it doesn't fail the
invite — it falls back to showing the invite link directly in the status
message, which you can copy and send manually. Configure real mail
whenever you're ready; nothing else changes.

## 4. Migrate — no new migrations needed

`companies`, `company_user`, and `company_invitations` were already
migrated when you set up the tenancy core. This module is pure
application code on top of tables that already exist.

## 5. Update the nav

Apply `resources/views/_navigation-menu.PATCH.blade.php` to your
`navigation-menu.blade.php` — it now includes a "Members" link and the
`<x-company-switcher>` component (only renders once a user belongs to
more than one company).

## 6. Test the flow end-to-end

1. Log in as a fresh user with no company → you should land on
   `/companies/create` instead of erroring.
2. Create a company → redirected to dashboard, `/sars` now works.
3. Visit `/companies/members` → invite a second email address.
4. Log in as that second user (or check the fallback link if mail isn't
   set up) → visiting the accept link joins them to the company with the
   role you picked.
5. If that second user is also in another company, the switcher appears
   in the nav and `PUT /companies/switch/{id}` moves between them.

## 7. Your existing tinker-created company

The company and membership row you created by hand via tinker earlier
still work fine — this module doesn't touch existing data, it just adds
the UI/flow that was missing so the next signup doesn't need tinker at
all.

## Next module

Same pattern again: Data Breaches (72-hour ICO countdown) is next in the
roadmap whenever you're ready.
