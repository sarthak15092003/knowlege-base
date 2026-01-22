#!/bin/bash

# Quick Deployment Script
# This script helps you quickly deploy changes to staging or production

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}   WordPress Theme Deployment Script   ${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if git is clean
if [[ -n $(git status -s) ]]; then
    echo -e "${YELLOW}Warning: You have uncommitted changes!${NC}"
    git status -s
    echo ""
    read -p "Do you want to continue? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Select environment
echo "Select deployment environment:"
echo "1) Staging (develop branch)"
echo "2) Production (main branch)"
echo "3) Cancel"
read -p "Enter choice [1-3]: " choice

case $choice in
    1)
        BRANCH="develop"
        ENV="Staging"
        ;;
    2)
        BRANCH="main"
        ENV="Production"
        echo -e "${RED}⚠️  WARNING: You are about to deploy to PRODUCTION!${NC}"
        read -p "Are you absolutely sure? (yes/no) " confirm
        if [[ $confirm != "yes" ]]; then
            echo "Deployment cancelled."
            exit 1
        fi
        ;;
    3)
        echo "Deployment cancelled."
        exit 0
        ;;
    *)
        echo -e "${RED}Invalid choice. Exiting.${NC}"
        exit 1
        ;;
esac

echo ""
echo -e "${GREEN}Deploying to ${ENV}...${NC}"
echo ""

# Checkout target branch
echo "📦 Checking out ${BRANCH} branch..."
git checkout $BRANCH

# Pull latest changes
echo "⬇️  Pulling latest changes..."
git pull origin $BRANCH

# Push to trigger CI/CD
echo "⬆️  Pushing to GitHub to trigger deployment..."
git push origin $BRANCH

echo ""
echo -e "${GREEN}✅ Deployment triggered successfully!${NC}"
echo ""
echo "Monitor the deployment progress at:"
echo "https://github.com/$(git remote get-url origin | sed 's/.*github.com[:/]\(.*\)\.git/\1/')/actions"
echo ""
echo -e "${YELLOW}Note: The actual deployment will take a few minutes to complete.${NC}"
