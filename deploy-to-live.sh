#!/bin/bash

# Quick Deploy to Live Server
# This script commits and pushes your changes to trigger automatic deployment

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}   Deploy to Live Server (GitHub)     ${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if there are changes
if [[ -z $(git status -s) ]]; then
    echo -e "${YELLOW}No changes to deploy!${NC}"
    exit 0
fi

# Show changes
echo -e "${YELLOW}Changes to be deployed:${NC}"
git status -s
echo ""

# Get commit message
read -p "Enter commit message (or press Enter for default): " commit_msg
if [[ -z "$commit_msg" ]]; then
    commit_msg="Deploy to production - $(date '+%Y-%m-%d %H:%M:%S')"
fi

# Confirm deployment
echo ""
echo -e "${RED}⚠️  This will deploy to your LIVE server!${NC}"
read -p "Continue? (yes/no): " confirm

if [[ $confirm != "yes" ]]; then
    echo "Deployment cancelled."
    exit 0
fi

# Deploy
echo ""
echo -e "${GREEN}📦 Committing changes...${NC}"
git add .
git commit -m "$commit_msg"

echo -e "${GREEN}⬆️  Pushing to GitHub...${NC}"
git push origin main

echo ""
echo -e "${GREEN}✅ Deployment triggered successfully!${NC}"
echo ""
echo "Monitor deployment progress at:"
echo "https://github.com/sarthak15092003/knowlege-base/actions"
echo ""
echo -e "${YELLOW}⏱️  Deployment typically takes 2-5 minutes.${NC}"
