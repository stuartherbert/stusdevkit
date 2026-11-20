.PHONY: *

# Set additional parameters for command
OPTS=

# Set DEBUG=1 to enable xdebug
ifeq ($(origin DEBUG),undefined)
    XDEBUG :=
    ACCEPTANCE_XDEBUG :=
    PHPSTAN_XDEBUG :=
else
    XDEBUG := XDEBUG_SESSION=1
    ACCEPTANCE_XDEBUG := ACCEPTANCE_TEST_DEBUG=1
    PHPSTAN_XDEBUG := --xdebug
endif

CLAUDECODEKIT=$(realpath ../stusdevkit-claudecodekit)
LINK_CCK_AGENT=$(CLAUDECODEKIT)/bin/link-agent.sh
LINK_CCK_HOOK=$(CLAUDECODEKIT)/bin/link-hook.sh
LINK_CCK_SKILL=$(CLAUDECODEKIT)/bin/link-skill.sh

list:
	@grep -E '^[a-zA-Z%_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

init: configure-githooks docker-rebuild composer-install ## Initialize Docker dev environment

claudecodekit: ## Link in Claude Code agents, skills, and hooks
	@$(LINK_CCK_AGENT) $(CLAUDECODEKIT)/claude/all-agents/thinking-hats.md
	@$(LINK_CCK_AGENT) $(CLAUDECODEKIT)/claude/php/agents/all-tests.md
	@$(LINK_CCK_AGENT) $(CLAUDECODEKIT)/claude/php/agents/php-all-checks.md
	@$(LINK_CCK_AGENT) $(CLAUDECODEKIT)/claude/php/agents/php-code-formatter.md
	@$(LINK_CCK_AGENT) $(CLAUDECODEKIT)/claude/php/agents/php-implementation-accuracy-reviewer.md
	@$(LINK_CCK_AGENT) $(CLAUDECODEKIT)/claude/php/agents/php-syntax-checker.md
	@$(LINK_CCK_AGENT) $(CLAUDECODEKIT)/claude/php/agents/php-unit-tests.md
	@$(LINK_CCK_HOOK) $(CLAUDECODEKIT)/claude/hooks/php-syntax-check.sh
	@$(LINK_CCK_SKILL) $(CLAUDECODEKIT)/claude/skills/opensource-review
	@$(LINK_CCK_SKILL) $(CLAUDECODEKIT)/claude/skills/review-against
	@$(LINK_CCK_SKILL) $(CLAUDECODEKIT)/claude/skills/review-branch
	@$(LINK_CCK_SKILL) $(CLAUDECODEKIT)/claude/skills/review-release

docker-rebuild: ## Rebuild Docker dev environment
	docker compose build --pull

composer-validate: composer-validate-85 composer-validate-86 ## Validate composer dependencies

composer-validate-85:
	@docker compose run --rm -q test-container-85 composer validate --strict

composer-validate-86:
	@docker compose run --rm -q test-container-86 composer validate --strict

composer-install: composer-validate ## Install composer dependencies
	@docker compose run --rm -q test-container-85 composer install

composer-update: ## Run composer update
	@docker compose run --rm -q test-container-85 composer update

composer-update-lock: ## Run composer update --lock
	@docker compose run --rm -q test-container-85 composer update --lock

composer-bump-deps: ## Update deps and check outdated
	@docker compose run --rm -q test-container-85 composer update --bump-after-update
	@docker compose run --rm -q test-container-85 composer outdated

composer-dumpautoload: ## Composer Dumpautoload
	@docker compose run --rm -q test-container-85 composer dumpautoload

composer-audit: ## Run composer audit
	@docker compose run --rm -q test-container-85 composer audit

clean: ## Remove all Docker containers, volumes, etc
	docker compose down -v --rmi all --remove-orphans
	rm -rf vendor

changelog: ## Rebuild the CHANGELOG.md file
	@docker compose run --rm -q test-container-85 sh -c "php tools/ChangelogTool/bin/changelog-tool"

configure-githooks: ## Configure git to use the project githooks
	git config core.hooksPath .githooks

shell: ## Open a shell on the test container
	@docker compose run --rm -q test-container-85 ash || true

all-checks: composer-validate static-checks cs-fix test ## Run all checks

static-checks: syntax-check phpstan ## Run all static code checks

cs: ## Run code style checks
	@docker compose run --rm -q test-container-85 sh -c "vendor/bin/phpcs ${OPTS}"

cs-fix: ## Fix code style issues
	@docker compose run --rm -q test-container-85 sh -c "vendor/bin/phpcbf ${OPTS} || true; vendor/bin/phpcs ${OPTS}"

phpstan: phpstan-85 phpstan-86 ## Run all static analysis checks

phpstan-85:
	@docker compose run --rm -q test-container-85 vendor/bin/phpstan $(PHPSTAN_XDEBUG) --memory-limit=-1 ${OPTS}

phpstan-86:
	@docker compose run --rm -q test-container-86 vendor/bin/phpstan $(PHPSTAN_XDEBUG) --memory-limit=-1 ${OPTS}

syntax-check: syntax-check-85 syntax-check-86 ## Check all PHP files for syntax errors

syntax-check-85:
	@docker compose run --rm -q test-container-85 sh -c "find kits/*/src kits/*/tests tools/*/src tools/*/tests -name '*.php' | xargs php -l | grep -v 'No syntax errors detected'; test \$$? -ne 0"

syntax-check-86:
	@docker compose run --rm -q test-container-86 sh -c "find kits/*/src kits/*/tests tools/*/src tools/*/tests -name '*.php' | xargs php -l | grep -v 'No syntax errors detected'; test \$$? -ne 0"

test: test-85 test-86 ## Run all tests

test-85: ## Run all tests against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit,acceptance --display-all-issues --testdox --testdox-summary ${OPTS}"

test-86: ## Run all tests against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit,acceptance --display-all-issues --testdox --testdox-summary ${OPTS}"

acceptance: acceptance-85 acceptance-86 ## Run all acceptance tests

acceptance-85: ## Run all acceptance tests against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=acceptance --display-all-issues --testdox --testdox-summary ${OPTS}"

acceptance-86: ## Run all acceptance tests against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=acceptance --display-all-issues --testdox --testdox-summary ${OPTS}"

acceptance-validationkit: acceptance-validationkit-85 acceptance-validationkit-86 ## Run acceptance tests for the ValidationKit

acceptance-validationkit-85 : ## Run acceptance tests for the ValidationKit against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=acceptance-validationkit --display-all-issues --testdox --testdox-summary ${OPTS}"

acceptance-validationkit-86 : ## Run acceptance tests for the ValidationKit against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=acceptance-validationkit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit: unit-85 unit-86 ## Run all unit tests

unit-85: ## Run all unit tests against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-86: ## Run all unit tests against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-assertionskit: unit-assertionskit-85 unit-assertionskit-86 ## Run unit tests for the AssertionsKit

unit-assertionskit-85: ## Run unit tests for the AssertionsKit against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-assertionskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-assertionskit-86: ## Run unit tests for the AssertionsKit against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-assertionskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-collectionskit: unit-collectionskit-85 unit-collectionskit-86 ## Run unit tests for the CollectionsKit

unit-collectionskit-85: ## Run unit tests for the CollectionsKit against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-collectionskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-collectionskit-86: ## Run unit tests for the CollectionsKit against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-collectionskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-datetimekit: unit-datetimekit-85 unit-datetimekit-86 ## Run unit tests for the DateTimeKit

unit-datetimekit-85: ## Run unit tests for the DateTimeKit against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-datetimekit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-datetimekit-86: ## Run unit tests for the DateTimeKit against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-datetimekit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-dependencykit: unit-dependencykit-85 unit-dependencykit-86 ## Run unit tests for the DependencyKit

unit-dependencykit-85: ## Run unit tests for the DependencyKit against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-dependencykit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-dependencykit-86: ## Run unit tests for the DependencyKit against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-dependencykit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-validationkit: unit-validationkit-85 unit-validationkit-86 ## Run unit tests for the ValidationKit

unit-validationkit-85: ## Run unit tests for the ValidationKit against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-validationkit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-validationkit-86: ## Run unit tests for the ValidationKit against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-validationkit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-exceptionskit: unit-exceptionskit-85 unit-exceptionskit-86 ## Run unit tests for the ExceptionsKit

unit-exceptionskit-85: ## Run unit tests for the ExceptionsKit against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-exceptionskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-exceptionskit-86: ## Run unit tests for the ExceptionsKit against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-exceptionskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-missingbitskit: unit-missingbitskit-85 unit-missingbitskit-86 ## Run unit tests for the MissingBitsKit

unit-missingbitskit-85: ## Run unit tests for the MissingBitsKit against PHP 8.5
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-missingbitskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-missingbitskit-86: ## Run unit tests for the MissingBitsKit against PHP 8.6
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-missingbitskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-changelogtool: unit-changelogtool-85 unit-changelogtool-86 ## Run unit tests for the ChangelogTool
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-changelogtool --display-all-issues --testdox --testdox-summary ${OPTS}"
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-changelogtool --display-all-issues --testdox --testdox-summary ${OPTS}"

coverage: coverage-85 coverage-86 ## run all tests and report on code coverage

coverage-85: ## run all tests against PHP 8.5 and report on code coverage
	@docker compose run --rm -q test-container-85 sh -c "$(XDEBUG) XDEBUG_MODE=coverage vendor/bin/phpunit --testsuite=unit ${OPTS} --coverage-html testcoverage "

coverage-86: ## run all tests against PHP 8.6 and report on code coverage
	@docker compose run --rm -q test-container-86 sh -c "$(XDEBUG) XDEBUG_MODE=coverage vendor/bin/phpunit --testsuite=unit ${OPTS} --coverage-html testcoverage "

