#!/bin/bash
# SETUP SCRIPT - Taman Nasional Tesso Nilo System
# Run this script to initialize the system

echo "=================================================="
echo "  Taman Nasional Tesso Nilo - Setup Script"
echo "=================================================="
echo ""

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Step 1: Checking prerequisites...${NC}"
echo ""

# Check if Apache is running
if ! command -v apache2ctl &> /dev/null; then
    echo -e "${RED}✗ Apache not found. Please start XAMPP first.${NC}"
    exit 1
else
    echo -e "${GREEN}✓ Apache found${NC}"
fi

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo -e "${RED}✗ PHP not found. Please install/start XAMPP.${NC}"
    exit 1
else
    echo -e "${GREEN}✓ PHP found ($(php --version | head -n1))${NC}"
fi

echo ""
echo -e "${YELLOW}Step 2: Checking file structure...${NC}"
echo ""

# Check if all required files exist
files=(
    "config/database.php"
    "api/init_db.php"
    "login.html"
    "register.html"
    "dashboard.html"
    "booking.html"
    "index.html"
)

all_exist=true
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✓ Found: $file${NC}"
    else
        echo -e "${RED}✗ Missing: $file${NC}"
        all_exist=false
    fi
done

if [ "$all_exist" = false ]; then
    echo -e "${RED}Some files are missing!${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}Step 3: Database initialization...${NC}"
echo ""
echo "Please open your browser and visit:"
echo -e "${GREEN}http://localhost/tessonilov3/api/init_db.php${NC}"
echo ""
echo "Wait for the message 'Database initialized successfully'"
echo ""

read -p "Press Enter when database initialization is complete..."

echo ""
echo -e "${YELLOW}Step 4: Verify installation...${NC}"
echo ""

echo "Testing API endpoints..."
echo ""

# Test database connection
echo "Testing database connection..."
response=$(curl -s http://localhost/tessonilov3/api/auth/check_session.php)
if [[ $response == *"Not authenticated"* ]] || [[ $response == *"success"* ]]; then
    echo -e "${GREEN}✓ API is responding${NC}"
else
    echo -e "${RED}✗ API not responding properly${NC}"
fi

echo ""
echo -e "${GREEN}=================================================="
echo "  Installation Complete! 🎉"
echo "==================================================${NC}"
echo ""
echo "Next steps:"
echo ""
echo "1. Admin Login:"
echo -e "   ${GREEN}http://localhost/tessonilov3/login.html${NC}"
echo "   Username: ${GREEN}admin${NC}"
echo "   Password: ${GREEN}admin123${NC}"
echo ""
echo "2. Customer Registration:"
echo -e "   ${GREEN}http://localhost/tessonilov3/register.html${NC}"
echo ""
echo "3. Customer Booking:"
echo -e "   ${GREEN}http://localhost/tessonilov3/booking.html${NC}"
echo ""
echo "For detailed documentation, see:"
echo -e "   ${GREEN}DOKUMENTASI.md${NC}"
echo ""
echo -e "${YELLOW}Happy using! 🌿🐘${NC}"
