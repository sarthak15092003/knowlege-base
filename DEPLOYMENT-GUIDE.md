# Deploy WordPress Theme to Live Server - Step by Step Guide

## Prerequisites Checklist
- [ ] GitHub repository with your theme code
- [ ] Live hosting server (shared hosting, VPS, or cloud)
- [ ] WordPress installed on live server
- [ ] FTP/SFTP or SSH access to server
- [ ] Database credentials for live server

## Deployment Options

### Option 1: Automated Deployment via GitHub Actions (RECOMMENDED)

This is the easiest method - push to GitHub and it automatically deploys!

#### Step 1: Get Your Server Details

You need these from your hosting provider:
- **FTP/SFTP Server**: (e.g., ftp.yourdomain.com)
- **Username**: Your FTP username
- **Password**: Your FTP password
- **Theme Directory Path**: Usually `/public_html/wp-content/themes/docy/`

#### Step 2: Add Secrets to GitHub

1. Go to your GitHub repository
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add these secrets:

```
Name: PROD_FTP_SERVER
Value: ftp.yourdomain.com

Name: PROD_FTP_USERNAME
Value: your_ftp_username

Name: PROD_FTP_PASSWORD
Value: your_ftp_password
```

#### Step 3: Update Deployment Configuration

The CI/CD pipeline is already set up! Just update the server path if needed:

Edit `.github/workflows/ci-cd.yml` and find the production deployment section.
Update `server-dir` to match your hosting path.

#### Step 4: Deploy!

```bash
# Commit your changes
git add .
git commit -m "Ready for production deployment"

# Push to main branch to trigger deployment
git push origin main
```

✅ **Done!** Check GitHub Actions tab to monitor deployment progress.

---

### Option 2: Manual FTP Deployment

If you prefer manual control:

#### Using FileZilla (Windows/Mac/Linux)

1. **Download FileZilla**: https://filezilla-project.org/
2. **Connect to your server**:
   - Host: ftp.yourdomain.com
   - Username: your_ftp_username
   - Password: your_ftp_password
   - Port: 21 (FTP) or 22 (SFTP)

3. **Navigate to theme directory**:
   - Remote site: `/public_html/wp-content/themes/`

4. **Upload theme files**:
   - Drag and drop the `docy` folder
   - Exclude: `.git`, `node_modules`, `.github`

5. **Activate theme**:
   - Login to WordPress admin: `yourdomain.com/wp-admin`
   - Go to Appearance → Themes
   - Activate "Docy" theme

---

### Option 3: SSH/Git Deployment (Advanced)

For servers with SSH access:

#### Step 1: Connect to Server

```bash
ssh username@yourdomain.com
```

#### Step 2: Navigate to Themes Directory

```bash
cd /var/www/html/wp-content/themes/
# or
cd /public_html/wp-content/themes/
```

#### Step 3: Clone Repository

```bash
# Remove old theme if exists
rm -rf docy

# Clone from GitHub
git clone https://github.com/sarthak15092003/knowlege-base.git docy

# Enter theme directory
cd docy

# Checkout main branch
git checkout main
```

#### Step 4: Set Permissions

```bash
# Set correct ownership
chown -R www-data:www-data /path/to/wp-content/themes/docy

# Set correct permissions
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
```

#### Step 5: Update Theme (Future Updates)

```bash
cd /path/to/wp-content/themes/docy
git pull origin main
```

---

### Option 4: cPanel Git Deployment

If your host has cPanel with Git Version Control:

1. **Login to cPanel**
2. **Find "Git Version Control"** in Files section
3. **Click "Create"**
4. **Fill in details**:
   - Clone URL: `https://github.com/sarthak15092003/knowlege-base.git`
   - Repository Path: `/public_html/wp-content/themes/docy`
   - Repository Name: `docy-theme`

5. **Click "Create"**
6. **Click "Manage"** → **Pull or Deploy** → **Update from Remote**

---

## Post-Deployment Checklist

After deploying, verify these:

### 1. Theme Activation
- [ ] Login to WordPress admin
- [ ] Go to Appearance → Themes
- [ ] Activate "Docy" theme

### 2. Verify Assets
- [ ] Check if CSS loads correctly
- [ ] Check if JavaScript works
- [ ] Verify images display properly

### 3. Test Functionality
- [ ] Test navigation menu
- [ ] Test search functionality
- [ ] Check responsive design
- [ ] Test all custom features

### 4. Performance Optimization
- [ ] Enable caching (WP Super Cache or W3 Total Cache)
- [ ] Optimize images
- [ ] Enable GZIP compression
- [ ] Set up CDN (optional)

### 5. Security
- [ ] Update WordPress core
- [ ] Update all plugins
- [ ] Set proper file permissions
- [ ] Install security plugin (Wordfence)
- [ ] Enable SSL certificate

---

## Troubleshooting

### Issue: Theme files not uploading
**Solution**: Check FTP credentials and server path

### Issue: White screen after activation
**Solution**: 
```bash
# Enable WordPress debug mode
# Edit wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

### Issue: CSS/JS not loading
**Solution**:
- Clear browser cache
- Clear WordPress cache
- Check file permissions (should be 644)
- Verify asset paths in theme

### Issue: Database connection error
**Solution**: Update `wp-config.php` with correct database credentials

---

## Automated Deployment Workflow

Once set up, your workflow will be:

```
1. Make changes locally
   ↓
2. Test on localhost
   ↓
3. Commit to Git
   ↓
4. Push to GitHub
   ↓
5. GitHub Actions deploys automatically
   ↓
6. Live site updated! ✅
```

---

## Hosting Recommendations

### Budget-Friendly Options:
- **Hostinger** - $2.99/month - Great for WordPress
- **Bluehost** - $2.95/month - Official WordPress recommendation
- **SiteGround** - $3.99/month - Excellent performance

### Premium Options:
- **WP Engine** - $20/month - Managed WordPress hosting
- **Kinsta** - $35/month - Premium managed hosting
- **Cloudways** - $10/month - Cloud hosting with flexibility

### Free Options (for testing):
- **InfinityFree** - Free with limitations
- **000webhost** - Free tier available
- **Netlify** - Free for static sites (not WordPress)

---

## Need Help?

### Common Commands

**Check Git status:**
```bash
git status
```

**View deployment logs:**
```bash
# On GitHub: Repository → Actions → Click on workflow run
```

**Connect via SSH:**
```bash
ssh username@yourdomain.com
```

**Check file permissions:**
```bash
ls -la /path/to/theme/
```

---

## Quick Start Script

Save this as `deploy-to-live.sh` and run it:

```bash
#!/bin/bash
echo "🚀 Deploying to Live Server..."
git add .
git commit -m "Deploy to production"
git push origin main
echo "✅ Deployment triggered! Check GitHub Actions for progress."
```

Make it executable:
```bash
chmod +x deploy-to-live.sh
./deploy-to-live.sh
```

---

## Support Resources

- **WordPress Codex**: https://codex.wordpress.org/
- **GitHub Actions Docs**: https://docs.github.com/en/actions
- **FileZilla Guide**: https://wiki.filezilla-project.org/
- **cPanel Documentation**: https://docs.cpanel.net/

---

**Ready to deploy?** Follow Option 1 for automated deployment or Option 2 for manual deployment!
