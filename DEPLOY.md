# Deploying Fieldbook for free

This app now has a `Dockerfile` that builds the front-end assets and serves
Laravel via nginx + php-fpm. The stack below is the most reliable **fully
free, forever** combo for a small Laravel app in 2026:

- **Render** (free web service) — hosts the app, HTTPS included, no card required
- **Neon** (free Postgres) — the database, and unlike Render's own free
  Postgres it does **not** expire after 30 days

Trade-off of the free tier: the Render web service sleeps after ~15 minutes
of no traffic and takes ~30-60s to wake back up on the next visit. Everything
else (your data, uploads, logins) stays intact — it's just a cold start, not
data loss.

One code change was made to support Postgres: `ReportController.php` used a
MySQL-only `MONTH()` function in one report query. It's now written to work
on either MySQL or Postgres automatically.

---

## 1. Push the code to GitHub

Run these from the project folder on your own machine (not needed here —
just documenting the steps):

```bash
git init
git add .
git commit -m "Initial commit"
```

Create a new **empty** repository on https://github.com/new (don't add a
README/.gitignore there), then:

```bash
git remote add origin https://github.com/<your-username>/fieldbook.git
git branch -M main
git push -u origin main
```

`.env` is already excluded via `.gitignore`, so no secrets get pushed.

## 2. Create the free database (Neon)

1. Go to https://neon.com and sign up (no card needed).
2. Create a new project (any region close to you).
3. On the project dashboard, copy the **connection string** — it looks like:
   `postgresql://user:password@ep-xxxx.neon.tech/dbname?sslmode=require`

## 3. Create the web service (Render)

1. Go to https://render.com and sign up (no card needed).
2. **New +** → **Web Service** → connect your GitHub account → pick the
   `fieldbook` repo.
3. Render should auto-detect the `Dockerfile`. If it asks for a runtime,
   choose **Docker**.
4. Instance type: **Free**.
5. Under **Environment Variables**, add:

| Key | Value |
|---|---|
| `APP_NAME` | `Fieldbook` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:SW6m7gorEYhangiRGnYlFPPJSI5Yp0y50rF512oi5Js=` |
| `APP_URL` | `https://<your-render-service-name>.onrender.com` |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | *(paste the full Neon connection string from step 2)* |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `LOG_CHANNEL` | `stderr` |

A couple of notes on these:
- The `APP_KEY` above was freshly generated for you and isn't used anywhere
  else — treat it as a secret and don't commit it to the repo. Keeping it
  fixed (rather than letting it regenerate) matters because it encrypts
  sessions and cookies.
- Once the app is live, update `APP_URL` to match the actual `.onrender.com`
  address Render gives you (or your custom domain later).

6. Click **Deploy**. The first build installs Composer/npm dependencies and
   runs your migrations automatically (via the base image's boot script), so
   the database schema gets created on first deploy — no manual `artisan
   migrate` needed.

## 4. Visit the site

Render gives you a URL like `https://fieldbook.onrender.com`. Since there's
no seeded admin user, register your first account from the app's own
`/register` page.

---

## Optional: custom domain

Render's free plan supports custom domains at no extra cost — add one under
the service's **Settings → Custom Domains** and point your domain's DNS at
Render as instructed there.

## Troubleshooting

- **502 / build fails on first deploy**: check the Render build logs — it's
  almost always a missing environment variable (`APP_KEY` or `DB_URL`).
- **"could not find driver" for pgsql**: the `richarvey/nginx-php-fpm` image
  ships the `pdo_pgsql` extension already enabled, so this shouldn't happen;
  if it does, redeploy — it usually means the build was cached mid-change.
- **App looks unstyled**: the Vite build step failed. Check the build logs
  for the `npm run build` stage.
