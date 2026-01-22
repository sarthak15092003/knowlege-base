# 🚀 Quick Start: Deploy Your WordPress Theme to Live Server

## ✅ Pre-Deployment Checklist

Before deploying, make sure you have:

- [ ] A live hosting server with WordPress installed
- [ ] FTP/SFTP credentials from your hosting provider
- [ ] GitHub account with your repository
- [ ] All local changes committed to Git

---

## 🎯 Choose Your Deployment Method

### Method 1: Automated Deployment (Easiest) ⭐ RECOMMENDED

**Time Required:** 10 minutes setup, then instant deployments

**Steps:**

1. **Get your hosting credentials** from your hosting provider:
   - FTP Server (e.g., `ftp.yourdomain.com`)
   - FTP Username
   - FTP Password
   - Theme path (usually `/public_html/wp-content/themes/docy/`)

2. **Add credentials to GitHub:**
   - Go to: https://github.com/sarthak15092003/knowlege-base/settings/secrets/actions
   - Click "New repository secret"
   - Add these three secrets:
     ```
     PROD_FTP_SERVER = your-ftp-server.com
     PROD_FTP_USERNAME = your-username
     PROD_FTP_PASSWORD = your-password
     ```

3. **Deploy:**
   - Double-click `deploy-to-live.bat` (Windows)
   - Or run: `./deploy-to-live.sh` (Mac/Linux)
   - Or manually:
     ```bash
     git add .
     git commit -m "Deploy to production"
     git push origin main
     ```

4. **Monitor:**
   - Check deployment: https://github.com/sarthak15092003/knowlege-base/actions
   - Wait 2-5 minutes for completion

✅ **Done!** Your site is live!

---

### Method 2: Manual FTP Upload (Simple)

**Time Required:** 15-20 minutes

**Steps:**

1. **Download FileZilla:** https://filezilla-project.org/

2. **Connect to your server:**
   - Host: `ftp.yourdomain.com`
   - Username: Your FTP username
   - Password: Your FTP password
   - Port: 21

3. **Navigate to:** `/public_html/wp-content/themes/`

4. **Upload the `docy` folder** (exclude `.git`, `node_modules`, `.github`)

5. **Activate theme:**
   - Login: `yourdomain.com/wp-admin`
   - Go to: Appearance → Themes
   - Click "Activate" on Docy theme

✅ **Done!** Your theme is live!

---

### Method 3: SSH/Git Clone (Advanced)

**Time Required:** 5 minutes

**Requirements:** SSH access to server

**Steps:**

```bash
# 1. Connect to server
ssh username@yourdomain.com

# 2. Navigate to themes directory
cd /public_html/wp-content/themes/

# 3. Clone repository
git clone https://github.com/sarthak15092003/knowlege-base.git docy

# 4. Set permissions
chmod -R 755 docy
```

✅ **Done!** Theme is live!

---

## 📋 After Deployment

Once deployed, complete these steps:

### 1. Activate Theme
- [ ] Login to WordPress admin: `yourdomain.com/wp-admin`
- [ ] Go to Appearance → Themes
- [ ] Click "Activate" on Docy theme

### 2. Verify Everything Works
- [ ] Check homepage loads correctly
- [ ] Test navigation menu
- [ ] Verify search functionality
- [ ] Check responsive design on mobile
- [ ] Test all custom features

### 3. Optimize Performance
- [ ] Install caching plugin (WP Super Cache)
- [ ] Enable GZIP compression
- [ ] Optimize images
- [ ] Set up CDN (optional)

### 4. Security
- [ ] Install SSL certificate (HTTPS)
- [ ] Install security plugin (Wordfence)
- [ ] Update WordPress core
- [ ] Set strong admin password

---

## 🆘 Troubleshooting

### "White screen after activation"
**Solution:**
1. Rename theme folder via FTP to deactivate
2. Check error logs in `/wp-content/debug.log`
3. Enable debug mode in `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

### "CSS/JS not loading"
**Solution:**
1. Clear browser cache (Ctrl+F5)
2. Clear WordPress cache
3. Check file permissions (should be 644)
4. Verify asset URLs in browser console

### "FTP connection failed"
**Solution:**
1. Verify credentials with hosting provider
2. Try SFTP (port 22) instead of FTP (port 21)
3. Check if server requires passive mode
4. Whitelist your IP address

### "GitHub Actions deployment failed"
**Solution:**
1. Check GitHub Actions logs for errors
2. Verify FTP credentials in GitHub Secrets
3. Confirm server path is correct
4. Check server disk space

---

## 🎉 Success! What's Next?

Your WordPress theme is now live! Here's what you can do:

### Regular Updates
Use the automated deployment:
```bash
# Make changes locally
# Test on localhost
# Then deploy:
git add .
git commit -m "Update description"
git push origin main
```

### Monitor Your Site
- Set up Google Analytics
- Install uptime monitoring (UptimeRobot)
- Enable error logging
- Set up automated backups

### Improve Performance
- Use a CDN (Cloudflare)
- Optimize database
- Compress images
- Minify CSS/JS

---

## 📞 Need Help?

**Hosting Provider Support:**
- Most hosting providers have 24/7 support
- Check their knowledge base first
- Contact via live chat or ticket

**WordPress Community:**
- WordPress Forums: https://wordpress.org/support/
- Stack Overflow: https://stackoverflow.com/questions/tagged/wordpress

**GitHub Issues:**
- Report bugs: https://github.com/sarthak15092003/knowlege-base/issues

---

## 🔗 Useful Links

- **Your GitHub Repo:** https://github.com/sarthak15092003/knowlege-base
- **GitHub Actions:** https://github.com/sarthak15092003/knowlege-base/actions
- **WordPress Codex:** https://codex.wordpress.org/
- **FileZilla Download:** https://filezilla-project.org/

---

**Ready to deploy?** Choose a method above and follow the steps!

**Questions?** Check the full DEPLOYMENT-GUIDE.md for detailed instructions.
