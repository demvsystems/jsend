# Use bash for more flexibility
set shell := ["bash", "-uc"]

# Default recipe
default: test

# Run PHPUnit tests
test:
    composer run test

# Run tests with coverage report
coverage:
    composer run coverage

# Run static analysis with PHPStan
stan:
    composer run phpstan

# Run coding standards check with PHP_CodeSniffer
cs:
    composer run phpcs

# Automatically fix coding style issues
fix:
    ./vendor/bin/phpcbf

# Run all quality checks (tests, static analysis, coding standards)
check:
    just test
    just stan
    just cs
