#!/bin/bash

# =============================================================================
# Koperasi Syariah E2E Test Runner
# =============================================================================
# Main script to start the Laravel application and run Playwright E2E tests
# Usage: ./run-tests.sh [test-type]
# Test types: auth, anggota, pengurus, admin, all (default: all)
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
APP_PORT=${APP_PORT:-8010}
APP_URL=${APP_URL:-"http://localhost:${APP_PORT}"}
TEST_TYPE=${1:-"all"}
DB_NAME=${DB_NAME:-"koperasi_syariah_testing"}
LOG_FILE="tests/e2e/logs/application.log"
SCREENSHOT_DIR="tests/e2e/reports/screenshots"

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

# Function to cleanup background processes
cleanup() {
    print_status "🧹 Cleaning up..."

    # Kill Laravel application if running
    if [ ! -z "$APP_PID" ]; then
        print_status "Stopping Laravel application (PID: $APP_PID)"
        kill $APP_PID 2>/dev/null || true
        wait $APP_PID 2>/dev/null || true
    fi

    # Kill any other PHP processes on the same port
    pkill -f "php artisan serve --port=${APP_PORT}" 2>/dev/null || true
    pkill -f "serve --port=${APP_PORT}" 2>/dev/null || true

    print_status "✅ Cleanup completed"
}

# Set trap to cleanup on exit
trap cleanup EXIT INT TERM

# Function to check if port is available
check_port() {
    if lsof -Pi :$APP_PORT -sTCP:LISTEN -t >/dev/null 2>&1; then
        print_error "Port $APP_PORT is already in use"
        print_status "Trying to kill existing process..."
        lsof -ti:$APP_PORT | xargs kill -9 2>/dev/null || true
        sleep 2
        if lsof -Pi :$APP_PORT -sTCP:LISTEN -t >/dev/null 2>&1; then
            print_error "Port $APP_PORT is still in use. Please free it and try again."
            exit 1
        fi
    fi
}

# Function to setup test environment
setup_environment() {
    print_status "📋 Setting up test environment..."

    # Create necessary directories
    mkdir -p tests/e2e/logs
    mkdir -p tests/e2e/reports/html
    mkdir -p tests/e2e/reports/screenshots
    mkdir -p tests/e2e/reports/videos

    # Copy testing environment if exists
    if [ -f ".env.testing" ]; then
        cp .env.testing .env
        print_status "✅ Using .env.testing configuration"
    else
        print_warning ".env.testing not found, using existing .env"
    fi

    # Set environment variables for testing
    export APP_ENV=testing
    export APP_URL=$APP_URL
    export DB_DATABASE=$DB_NAME
    export DB_CONNECTION=mysql

    # Clear and cache configuration
    print_status "🔄 Optimizing application..."
    php artisan config:clear
    php artisan config:cache
    php artisan route:clear
    php artisan route:cache
    php artisan view:clear
    php artisan view:cache

    # Reset database
    print_status "🗄️ Reseeding test database..."
    php artisan db:seed --force
    php artisan storage:link --force

    print_status "✅ Test environment ready"
}

# Function to start Laravel application
start_application() {
    print_status "🌐 Starting Laravel application on port $APP_PORT..."

    # Start Laravel in background
    php artisan serve --host=0.0.0.0 --port=$APP_PORT > $LOG_FILE 2>&1 &
    APP_PID=$!

    print_status "Application started with PID: $APP_PID"

    # Wait for application to be ready
    print_status "⏳ Waiting for application to be ready..."
    local max_attempts=30
    local attempt=1

    while [ $attempt -le $max_attempts ]; do
        if curl -f -s "$APP_URL" > /dev/null 2>&1; then
            print_status "✅ Application is ready at $APP_URL"
            return 0
        fi

        echo -n "."
        sleep 1
        attempt=$((attempt + 1))
    done

    echo
    print_error "❌ Application failed to start within $max_attempts seconds"
    print_status "Checking application logs..."
    tail -20 $LOG_FILE
    return 1
}

# Function to check Playwright dependencies
check_playwright_dependencies() {
    print_status "🔧 Checking Playwright dependencies..."

    # Change to E2E test directory
    cd tests/e2e

    # Install dependencies if needed
    if [ ! -d "node_modules" ]; then
        print_status "📦 Installing Node.js dependencies..."
        npm install
    fi

    # Install Playwright browsers if needed
    if [ ! -d "$HOME/Library/Caches/ms-playwright/chromium-1200" ]; then
        print_status "🌐 Installing Playwright browsers..."
        npx playwright install chromium
    fi

    cd ../..
    print_status "✅ Playwright dependencies ready"
}

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
        "all")
            print_status "🧪 Running all E2E tests..."
            test_command="npx playwright test $test_options"
            ;;
        *)
            print_error "Unknown test type: $TEST_TYPE"
            print_status "Valid options: auth, anggota, pengurus, admin, all"
            return 1
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

# Function to show test reports
show_reports() {
    print_status "📊 Test reports available at:"
    print_status "  • HTML Report: tests/e2e/reports/html/index.html"
    print_status "  • JUnit Report: tests/e2e/reports/junit.xml"
    print_status "  • Screenshots: $SCREENSHOT_DIR"
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
}

# Function to show debug information
show_debug_info() {
    print_header "🐛 Debug Information"

    print_status "Application URL: $APP_URL"
    print_status "Test Type: $TEST_TYPE"
    print_status "Log File: $LOG_FILE"
    print_status "Environment: $APP_ENV"
    print_status "Database: $DB_NAME"

    if [ -f "$LOG_FILE" ]; then
        echo
        print_status "Last 10 lines of application log:"
        echo "----------------------------------------"
        tail -10 "$LOG_FILE"
        echo "----------------------------------------"
    fi
}

# Main execution
main() {
    print_header "🚀 Koperasi Syariah E2E Test Runner"

    # Parse command line arguments
    while [[ $# -gt 0 ]]; do
        case $1 in
            --debug)
                DEBUG_MODE=1
                shift
                ;;
            --port)
                APP_PORT="$2"
                APP_URL="http://localhost:${APP_PORT}"
                shift 2
                ;;
            --headed|--browser|--show-browser)
                HEADFUL_MODE=1
                shift
                ;;
            --help|-h)
                echo "Usage: $0 [test-type] [options]"
                echo ""
                echo "Test Types:"
                echo "  auth      - Run authentication tests only"
                echo "  anggota   - Run anggota tests only"
                echo "  pengurus  - Run pengurus tests only"
                echo "  admin     - Run admin tests only"
                echo "  all       - Run all tests (default)"
                echo ""
                echo "Options:"
                echo "  --debug         - Show debug information"
                echo "  --port N        - Use custom application port"
                echo "  --headed        - Show browser during testing"
                echo "  --browser       - Show browser during testing (alias)"
                echo "  --show-browser  - Show browser during testing (alias)"
                echo "  --help          - Show this help message"
                echo ""
                echo "Examples:"
                echo "  $0 auth                    # Run auth tests (headless)"
                echo "  $0 auth --headed           # Run auth tests with visible browser"
                echo "  $0 all --debug --port 8020 # Run all tests with debug and custom port"
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
    if [[ ! "$TEST_TYPE" =~ ^(auth|anggota|pengurus|admin|all)$ ]]; then
        print_error "Invalid test type: $TEST_TYPE"
        print_status "Valid options: auth, anggota, pengurus, admin, all"
        exit 1
    fi

    # Start the process
    START_TIME=$(date +%s)

    # Setup
    check_port
    setup_environment
    check_playwright_dependencies

    # Start application
    if ! start_application; then
        if [ "$DEBUG_MODE" = "1" ]; then
            show_debug_info
        fi
        exit 1
    fi

    # Run tests
    run_tests
    TEST_RESULT=$?

    # Calculate duration
    END_TIME=$(date +%s)
    DURATION=$((END_TIME - START_TIME))

    # Cleanup and reports
    cleanup

    if [ "$DEBUG_MODE" = "1" ]; then
        show_debug_info
    fi

    show_reports

    # Final summary
    print_header "📋 Test Execution Summary"
    print_status "Duration: ${DURATION}s"
    print_status "Test Type: $TEST_TYPE"

    if [ $TEST_RESULT -eq 0 ]; then
        print_status "Result: ${GREEN}SUCCESS${NC}"
        EXIT_CODE=0
    else
        print_status "Result: ${RED}FAILED${NC}"
        EXIT_CODE=1
    fi

    exit $EXIT_CODE
}

# Run main function with all arguments
main "$@"