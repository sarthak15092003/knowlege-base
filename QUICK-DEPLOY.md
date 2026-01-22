# 🚀 Quick Deploy - Staging Server

## GitHub Secrets to Add

Go to: https://github.com/sarthak15092003/knowlege-base/settings/secrets/actions

Add these 3 secrets:

| Secret Name | Value |
|------------|-------|
| `STAGING_FTP_SERVER` | `23.106.70.138` |
| `STAGING_FTP_USERNAME` | `cmgdesignsftpstg@e89fp3uohq-staging.onrocket.site` |
| `STAGING_FTP_PASSWORD` | `95$@Bj&vK:)` |

## Deploy Command

```bash
git checkout develop
git add .
git commit -m "Your changes"
git push origin develop
```

## Check Deployment

- **Actions:** https://github.com/sarthak15092003/knowlege-base/actions
- **Live Site:** https://e89fp3uohq-staging.onrocket.site

## Manual FTP Access

- **Host:** 23.106.70.138
- **User:** cmgdesignsftpstg@e89fp3uohq-staging.onrocket.site
- **Pass:** 95$@Bj&vK:)
- **Path:** `/wp-content/themes/docy/`

---

**Full Guide:** See `.github/STAGING-SETUP.md`
