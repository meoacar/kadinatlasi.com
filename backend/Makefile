# Admin Panel Test Commands

.PHONY: test test-unit test-feature test-browser test-coverage test-parallel

# Run all tests
test:
	php artisan test

# Run only unit tests
test-unit:
	php artisan test --testsuite=Unit

# Run only feature tests
test-feature:
	php artisan test --testsuite=Feature

# Run only browser tests (requires Dusk)
test-browser:
	php artisan dusk

# Run tests with coverage
test-coverage:
	php artisan test --coverage --min=80

# Run tests in parallel
test-parallel:
	php artisan test --parallel

# Run specific test file
test-file:
	php artisan test $(FILE)

# Run tests with specific filter
test-filter:
	php artisan test --filter=$(FILTER)

# Setup test environment
test-setup:
	php artisan migrate:fresh --env=testing
	php artisan db:seed --env=testing

# Clean test environment
test-clean:
	php artisan migrate:reset --env=testing

# Run performance tests
test-performance:
	php artisan test tests/Unit/Services/PerformanceServiceTest.php

# Run security tests
test-security:
	php artisan test tests/Feature/Admin/AdminAuthTest.php

# Run validation tests
test-validation:
	php artisan test tests/Unit/Validation/AdminValidationTest.php

# Generate test report
test-report:
	php artisan test --coverage-html=storage/app/test-coverage

# Watch tests (requires file watcher)
test-watch:
	php artisan test --watch

# Lint and test
lint-test:
	./vendor/bin/pint --test
	php artisan test

# Quick test (unit + feature, no browser)
test-quick:
	php artisan test --testsuite=Unit,Feature

# Full test suite with coverage
test-full:
	php artisan test --coverage --testsuite=Unit,Feature,Browser

# Test specific admin functionality
test-admin:
	php artisan test tests/Feature/Admin/

# Test models
test-models:
	php artisan test tests/Unit/Models/

# Test services
test-services:
	php artisan test tests/Unit/Services/

# Help
help:
	@echo "Available test commands:"
	@echo "  test          - Run all tests"
	@echo "  test-unit     - Run unit tests only"
	@echo "  test-feature  - Run feature tests only"
	@echo "  test-browser  - Run browser tests only"
	@echo "  test-coverage - Run tests with coverage report"
	@echo "  test-parallel - Run tests in parallel"
	@echo "  test-quick    - Run unit and feature tests (no browser)"
	@echo "  test-full     - Run full test suite with coverage"
	@echo "  test-setup    - Setup test database"
	@echo "  test-clean    - Clean test database"
	@echo ""
	@echo "Examples:"
	@echo "  make test-file FILE=tests/Unit/Services/UserServiceTest.php"
	@echo "  make test-filter FILTER=admin_can_login"