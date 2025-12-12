#!/bin/bash

# =============================================================================
# Koperasi Syariah E2E Test Runner - Quick Version
# =============================================================================
# Quick test runner for running Playwright E2E tests against running application
# Usage: ./run-tests-quick.sh [test-type] [options]
# =============================================================================

set -e  # Exit on any error

# Get the directory where this script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Change to the application directory
cd "$SCRIPT_DIR"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_URL=${APP_URL:-"http://localhost:8000"}
TEST_TYPE=${1:-"all"}
HEADFUL_MODE=0

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${BLUE}=================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}=================================${NC}"
}

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --headed|--browser|--show-browser)
            HEADFUL_MODE=1
            shift
            ;;
        --url)
            APP_URL="$2"
            shift 2
            ;;
        --help|-h)
            echo "Usage: $0 [test-type] [options]"
            echo ""
            echo "Test Types:"
            echo "  auth                - Run authentication tests only"
            echo "  anggota             - Run anggota tests only"
            echo "  pengurus            - Run pengurus tests only"
            echo "  admin               - Run admin tests only"
            echo "  functional          - Run all functional tests"
            echo "  anggota-management  - Run anggota management functional tests"
            echo "  simpanan            - Run simpanan functional tests"
            echo "  pinjaman            - Run pinjaman functional tests"
            echo "  angsuran            - Run angsuran functional tests"
            echo "  laporan             - Run laporan functional tests"
            echo "  all                 - Run all tests (default)"
            echo ""
            echo "Options:"
            echo "  --headed        - Show browser during testing"
            echo "  --browser       - Show browser during testing (alias)"
            echo "  --show-browser  - Show browser during testing (alias)"
            echo "  --url URL       - Custom application URL (default: http://localhost:8000)"
            echo "  --help          - Show this help message"
            echo ""
            echo "Examples:"
            echo "  $0 auth                    # Run auth tests against localhost:8000"
            echo "  $0 auth --headed           # Run auth tests with visible browser"
            echo "  $0 all --url http://localhost:8010  # Run all tests against custom URL"
            echo ""
            echo "IMPORTANT: Make sure your Laravel application is running before executing this script!"
            exit 0
            ;;
        *)
            if [ -z "$TEST_TYPE_SET" ]; then
                TEST_TYPE="$1"
                TEST_TYPE_SET=1
            fi
            shift
            ;;
    esac
done

# Validate test type
if [[ ! "$TEST_TYPE" =~ ^(auth|anggota|pengurus|admin|functional|anggota-management|simpanan|pinjaman|angsuran|laporan|all)$ ]]; then
    print_error "Invalid test type: $TEST_TYPE"
    print_status "Valid options: auth, anggota, pengurus, admin, functional, anggota-management, simpanan, pinjaman, angsuran, laporan, all"
    exit 1
fi

print_header "🚀 Koperasi Syariah E2E Test Runner - Quick Version"

# Check if application is running
print_status "🔍 Checking if application is running at $APP_URL..."

if ! curl -f -s "$APP_URL" > /dev/null 2>&1; then
    print_error "❌ Application is not responding at $APP_URL"
    print_status "Please start your Laravel application first:"
    print_status "  cd $(pwd)"
    print_status "  php artisan serve --port ${APP_URL##*:}"
    print_status ""
    print_status "Or use the main test runner script that starts the app automatically:"
    print_status "  ./run-tests.sh $TEST_TYPE"
    exit 1
fi

print_status "✅ Application is running at $APP_URL"

# Check Playwright dependencies
print_status "🔧 Checking Playwright dependencies..."

# Change to E2E directory
cd tests/e2e

# Install dependencies if needed
if [ ! -d "node_modules" ]; then
    print_status "📦 Installing Node.js dependencies..."
    npm install
fi

# Check if playwright browsers are installed
if ! npx playwright --version > /dev/null 2>&1; then
    print_status "🌐 Installing Playwright browsers..."
    npx playwright install chromium
fi

cd ../..
print_status "✅ Playwright dependencies ready"

# Function to run specific test type
run_tests() {
    local test_command=""
    local test_options=""

    # Add headful mode if requested
    if [ "$HEADFUL_MODE" = "1" ]; then
        test_options="--headed"
        print_status "👁️ Running in headful mode (browser will be visible)"
    fi

    case $TEST_TYPE in
        "auth")
            print_status "🧪 Running authentication tests..."
            test_command="npx playwright test tests/auth/ $test_options"
            ;;
        "anggota")
            print_status "🧪 Running anggota tests..."
            test_command="npx playwright test tests/anggota/ $test_options"
            ;;
        "pengurus")
            print_status "🧪 Running pengurus tests..."
            test_command="npx playwright test tests/pengurus/ $test_options"
            ;;
        "admin")
            print_status "🧪 Running admin tests..."
            test_command="npx playwright test tests/admin/ $test_options"
            ;;
        "functional")
            print_status "🧪 Running all functional tests..."
            test_command="npx playwright test tests/functional/ $test_options"
            ;;
        "anggota-management")
            print_status "🧪 Running anggota management functional tests..."
            test_command="npx playwright test tests/functional/anggota-management.spec.js $test_options"
            ;;
        "simpanan")
            print_status "🧪 Running simpanan functional tests..."
            test_command="npx playwright test tests/functional/simpanan.spec.js $test_options"
            ;;
        "pinjaman")
            print_status "🧪 Running pinjaman functional tests..."
            test_command="npx playwright test tests/functional/pinjaman.spec.js $test_options"
            ;;
        "angsuran")
            print_status "🧪 Running angsuran functional tests..."
            test_command="npx playwright test tests/functional/angsuran.spec.js $test_options"
            ;;
        "laporan")
            print_status "🧪 Running laporan functional tests..."
            test_command="npx playwright test tests/functional/laporan.spec.js $test_options"
            ;;
        "all")
            print_status "🧪 Running all E2E tests..."
            test_command="npx playwright test $test_options"
            ;;
    esac

    # Change to E2E directory and run tests
    cd tests/e2e

    # Set environment variable for tests
    export APP_URL=$APP_URL

    print_status "🚀 Executing: $test_command"

    # Run tests and capture exit code
    if eval "$test_command"; then
        TEST_EXIT_CODE=0
        echo -e "\n${GREEN}✅ Tests completed successfully!${NC}"
    else
        TEST_EXIT_CODE=$?
        echo -e "\n${RED}❌ Some tests failed!${NC}"
        print_status "Check reports for detailed information"
    fi

    cd ../..

    return $TEST_EXIT_CODE
}

# Run tests
START_TIME=$(date +%s)
run_tests
TEST_RESULT=$?

# Calculate duration
END_TIME=$(date +%s)
DURATION=$((END_TIME - START_TIME))

# Show reports
print_status "📊 Test reports available at:"
print_status "  • HTML Report: tests/e2e/reports/html/index.html"
print_status "  • JUnit Report: tests/e2e/reports/junit.xml"
print_status "  • Screenshots: tests/e2e/reports/screenshots/"
print_status "  • Videos: tests/e2e/reports/videos/"

# If HTML report exists, offer to open it
if [ -f "tests/e2e/reports/html/index.html" ]; then
    if command -v open >/dev/null 2>&1; then
        read -p "Open HTML report? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            open tests/e2e/reports/html/index.html
        fi
    fi
fi

# Final summary
print_header "📋 Test Execution Summary"
print_status "Duration: ${DURATION}s"
print_status "Test Type: $TEST_TYPE"
print_status "Application URL: $APP_URL"

if [ $TEST_RESULT -eq 0 ]; then
    print_status "Result: ${GREEN}SUCCESS${NC}"
    EXIT_CODE=0
else
    print_status "Result: ${RED}FAILED${NC}"
    EXIT_CODE=1
fi

exit $EXIT_CODE