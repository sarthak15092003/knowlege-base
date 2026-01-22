# CI/CD Setup Guide - Staging Server

## 🎯 Your Staging Server Details

**Server URL:** https://e89fp3uohq-staging.onrocket.site  
**FTP IP:** 23.106.70.138  
**FTP Username:** cmgdesignsftpstg@e89fp3uohq-staging.onrocket.site  
**FTP Password:** 95$@Bj&vK:)

---

## 🚀 Quick Setup (5 Minutes)

### Step 1: Add GitHub Secrets

1. Go to your GitHub repository: https://github.com/sarthak15092003/knowlege-base
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **"New repository secret"**
4. Add these **3 secrets**:

#### Secret 1: FTP Server
```
Name: STAGING_FTP_SERVER
Value: 23.106.70.138
```

#### Secret 2: FTP Username
```
Name: STAGING_FTP_USERNAME
Value: cmgdesignsftpstg@e89fp3uohq-staging.onrocket.site
```

#### Secret 3: FTP Password
```
Name: STAGING_FTP_PASSWORD
Value: 95$@Bj&vK:)
```

### Step 2: Test the Deployment

Once secrets are added:

1. Make a small change to any file
2. Commit and push to the `develop` branch:
   ```bash
   git checkout develop
   git add .
   git commit -m "Test staging deployment"
   git push origin develop
   ```

3. Monitor deployment:
   - Go to: https://github.com/sarthak15092003/knowlege-base/actions
   - Watch the pipeline run
   - Deployment takes ~2-5 minutes

4. Verify on staging:
   - Visit: https://e89fp3uohq-staging.onrocket.site
   - Check if your changes are live

---

## 📊 Deployment Workflow

```
Local Changes → Git Push → GitHub Actions → FTP Upload → Live on Staging
     ↓              ↓              ↓              ↓              ↓
  Edit files    Push to      Runs CI/CD    Uploads via    Changes
  locally       develop       pipeline         FTP         visible
```

### Branch Strategy

- **`develop`** → Deploys to **Staging** (https://e89fp3uohq-staging.onrocket.site)
- **`main`** → Deploys to **Production** (when you're ready)

---

## 🛠️ Manual FTP Access (Optional)

If you need to manually access the server:

### Using FileZilla

1. **Download FileZilla**: https://filezilla-project.org/
2. **Connect with these settings**:
   - **Host:** 23.106.70.138
   - **Username:** cmgdesignsftpstg@e89fp3uohq-staging.onrocket.site
   - **Password:** 95$@Bj&vK:)
   - **Port:** 21 (FTP) or 22 (SFTP)

3. **Navigate to:** `/wp-content/themes/docy/`

### Using WinSCP (Windows)

1. **Download WinSCP**: https://winscp.net/
2. **New Site Settings**:
   - **File protocol:** FTP or SFTP
   - **Host name:** 23.106.70.138
   - **User name:** cmgdesignsftpstg@e89fp3uohq-staging.onrocket.site
   - **Password:** 95$@Bj&vK:)

---

## 🔄 Daily Workflow

### For Staging Deployment

```bash
# 1. Make your changes locally
# 2. Test on localhost

# 3. Commit and push to develop
git checkout develop
git add .
git commit -m "Your change description"
git push origin develop

# 4. Wait 2-5 minutes
# 5. Check https://e89fp3uohq-staging.onrocket.site
```

### For Production Deployment

```bash
# 1. Test thoroughly on staging first
# 2. Merge develop to main

git checkout main
git merge develop
git push origin main

# 3. Production deployment will start automatically
```

---

## 📁 Server Directory Structure

Your theme will be deployed to:
```
/wp-content/themes/docy/
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── template-parts/
├── inc/
├── style.css
├── functions.php
└── ... (all theme files)
```

---

## ✅ Verification Checklist

After deployment, verify:

- [ ] Website loads: https://e89fp3uohq-staging.onrocket.site
- [ ] Theme is active in WordPress admin
- [ ] CSS changes are visible
- [ ] JavaScript works correctly
- [ ] Images load properly
- [ ] No console errors (F12 → Console)

---

## 🐛 Troubleshooting

### Issue: Deployment Failed

**Solution:**
1. Check GitHub Actions logs
2. Verify FTP credentials in GitHub Secrets
3. Ensure server is accessible
4. Check FTP server permissions

### Issue: Changes Not Visible

**Solution:**
1. Clear browser cache (Ctrl + F5)
2. Clear WordPress cache
3. Check if files uploaded correctly via FTP
4. Verify file permissions (should be 644 for files, 755 for folders)

### Issue: FTP Connection Timeout

**Solution:**
1. Verify server IP: 23.106.70.138
2. Check if firewall is blocking connection
3. Try SFTP (port 22) instead of FTP (port 21)
4. Contact hosting provider

---

## 🔐 Security Best Practices

✅ **DO:**
- Keep GitHub Secrets secure
- Use SFTP instead of FTP when possible
- Regularly update WordPress and plugins
- Monitor deployment logs

❌ **DON'T:**
- Share FTP credentials publicly
- Commit credentials to Git
- Deploy untested code to production
- Skip staging environment

---

## 📞 Support

### Hosting Support
- **Provider:** Rocket.net
- **Server:** e89fp3uohq-staging.onrocket.site
- **Support:** Contact Rocket.net support team

### GitHub Actions
- **Documentation:** https://docs.github.com/en/actions
- **Status:** https://www.githubstatus.com/

---

## 🎉 You're All Set!

Your CI/CD pipeline is ready to use. Here's what happens automatically:

1. ✅ Code quality checks on every push
2. ✅ Asset building and optimization
3. ✅ Clean package creation
4. ✅ Automatic FTP deployment to staging
5. ✅ Deployment notifications

**Next Steps:**
1. Add the GitHub Secrets (see Step 1 above)
2. Push a test change to `develop` branch
3. Watch it deploy automatically! 🚀

---

**Questions?** Check the main DEPLOYMENT-GUIDE.md or CICD-SETUP.md files.
