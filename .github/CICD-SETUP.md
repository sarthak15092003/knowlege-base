# CI/CD Pipeline Setup Guide

## Overview
This CI/CD pipeline automates the testing, building, and deployment of your WordPress theme using GitHub Actions.

## Pipeline Stages

### 1. Code Quality Check
- PHP syntax validation
- CSS linting (if configured)
- Runs on every push and pull request

### 2. Build Assets
- Compiles SCSS to CSS
- Minifies JavaScript
- Optimizes assets for production

### 3. Package Creation
- Creates a clean deployment package
- Excludes development files
- Generates a ZIP archive

### 4. Deployment
- **Staging**: Deploys from `develop` branch
- **Production**: Deploys from `main`/`master` branch

## Setup Instructions

### Step 1: Configure GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions

Add the following secrets:

#### For Staging Environment:
- `STAGING_FTP_SERVER` - Your staging FTP server address
- `STAGING_FTP_USERNAME` - FTP username
- `STAGING_FTP_PASSWORD` - FTP password

#### For Production Environment:
- `PROD_FTP_SERVER` - Your production FTP server address
- `PROD_FTP_USERNAME` - FTP username
- `PROD_FTP_PASSWORD` - FTP password

### Step 2: Branch Strategy

- `main` or `master` - Production branch
- `develop` - Staging/development branch
- Feature branches - Create from `develop`

### Step 3: Workflow

1. Create a feature branch from `develop`
2. Make your changes
3. Push to GitHub - triggers code quality checks
4. Create PR to `develop` - runs full pipeline
5. Merge to `develop` - deploys to staging
6. Merge to `main` - deploys to production

## Alternative Deployment Methods

### Option 1: SSH Deployment (Recommended)

Replace FTP deployment with SSH:

```yaml
- name: Deploy via SSH
  uses: easingthemes/ssh-deploy@main
  with:
    SSH_PRIVATE_KEY: ${{ secrets.SSH_PRIVATE_KEY }}
    REMOTE_HOST: ${{ secrets.REMOTE_HOST }}
    REMOTE_USER: ${{ secrets.REMOTE_USER }}
    TARGET: /var/www/html/wp-content/themes/docy/
```

### Option 2: WP-CLI Deployment

```yaml
- name: Deploy with WP-CLI
  run: |
    wp theme install docy-theme.zip --activate --force
```

### Option 3: Rsync Deployment

```yaml
- name: Deploy via Rsync
  run: |
    rsync -avz -e "ssh -o StrictHostKeyChecking=no" \
      --exclude='.git*' \
      ./ ${{ secrets.REMOTE_USER }}@${{ secrets.REMOTE_HOST }}:/path/to/theme/
```

## Customization

### Add Automated Testing

Add this job before deployment:

```yaml
test:
  name: Run Tests
  runs-on: ubuntu-latest
  steps:
    - uses: actions/checkout@v3
    - name: Run PHPUnit
      run: vendor/bin/phpunit
```

### Add Slack Notifications

```yaml
- name: Slack Notification
  uses: 8398a7/action-slack@v3
  with:
    status: ${{ job.status }}
    webhook_url: ${{ secrets.SLACK_WEBHOOK }}
```

### Add Database Backup Before Deployment

```yaml
- name: Backup Database
  run: |
    ssh user@server 'wp db export backup-$(date +%Y%m%d-%H%M%S).sql'
```

## Monitoring

View pipeline status:
- GitHub repository → Actions tab
- Check logs for each job
- Review deployment status

## Rollback Procedure

If deployment fails:

1. Go to Actions tab
2. Find last successful deployment
3. Re-run that workflow
4. Or manually revert the commit

## Security Best Practices

1. ✅ Never commit credentials
2. ✅ Use GitHub Secrets for sensitive data
3. ✅ Enable branch protection rules
4. ✅ Require PR reviews before merging
5. ✅ Use SSH instead of FTP when possible

## Troubleshooting

### Pipeline Fails on PHP Syntax Check
- Check PHP version compatibility
- Review error logs in Actions tab

### Build Fails
- Ensure `package.json` has correct scripts
- Check Node.js version compatibility

### Deployment Fails
- Verify FTP/SSH credentials
- Check server permissions
- Ensure target directory exists

## Next Steps

1. ✅ Set up GitHub Secrets
2. ✅ Test pipeline with a small change
3. ✅ Configure branch protection
4. ✅ Set up staging environment
5. ✅ Document deployment process for team

## Support

For issues or questions:
- Check GitHub Actions documentation
- Review workflow logs
- Contact DevOps team
