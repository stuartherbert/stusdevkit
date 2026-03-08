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

CLAUDECODEKIT=$(realpath ../claudecodekit)
LINK_CCK_AGENT=$(CLAUDECODEKIT)/bin/link-agent.sh
LINK_CCK_HOOK=$(CLAUDECODEKIT)/bin/link-hook.sh
LINK_CCK_SKILL=$(CLAUDECODEKIT)/bin/link-skill.sh

list:
	@grep -E '^[a-zA-Z%_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

init: docker-rebuild composer-install ## Initialize Docker dev environment

claudecodekit: ## Link in Claude Code agents, skills, and hooks
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

composer-validate: ## Validate composer dependencies
	docker compose run --rm test-container-85 composer validate --strict

composer-install: composer-validate ## Install composer dependencies
	docker compose run --rm test-container-85 composer install

composer-update: ## Run composer update
	docker compose run --rm test-container-85 composer update
	docker compose run --rm test-container-85 composer outdated

composer-bump-deps: ## Update deps and check outdated
	docker compose run --rm test-container-85 composer update --bump-after-update
	docker compose run --rm test-container-85 composer outdated

composer-dumpautoload: ## Composer Dumpautoload
	docker compose run --rm test-container-85 composer dumpautoload

clean: ## Remove all Docker containers, volumes, etc
	docker compose down -v --rmi all --remove-orphans
	rm -rf vendor

shell: ## Open a shell on the test container
	docker compose run --rm test-container-85 ash || true

all-checks: composer-validate static-checks cs-fix test ## Run all checks

static-checks: syntax-check phpstan ## Run all static code checks

cs: ## Run code style checks
	docker compose run --rm test-container-85 sh -c "vendor/bin/phpcs ${OPTS}"

cs-fix: ## Fix code style issues
	docker compose run --rm test-container-85 sh -c "vendor/bin/phpcbf ${OPTS} || true; vendor/bin/phpcs ${OPTS}"

phpstan: ## Run all static analysis checks
	docker compose run --rm test-container-85 vendor/bin/phpstan $(PHPSTAN_XDEBUG) --memory-limit=-1 ${OPTS}

syntax-check: ## Check all PHP files for syntax errors
	@docker compose run --rm test-container-85 find kits/*/src kits/*/tests -name '*.php' | xargs php -l

test: unit ## Run all tests

unit:  ## Run all unit tests
	docker compose run --rm test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-collectionskit: ## Run unit tests for the CollectionsKit
	docker compose run --rm test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-collectionskit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-datetimekit: ## Run unit tests for the DateTimeKit
	docker compose run --rm test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-datetimekit --display-all-issues --testdox --testdox-summary ${OPTS}"

unit-exceptionskit: ## Run unit tests for the ExceptionsKit
	docker compose run --rm test-container-85 sh -c "$(XDEBUG) vendor/bin/phpunit --testsuite=unit-exceptionskit --display-all-issues --testdox --testdox-summary ${OPTS}"

coverage: ## run all test and report on code coverage
	docker compose run --rm test-container-85 sh -c "$(XDEBUG) XDEBUG_MODE=coverage vendor/bin/phpunit --testsuite=unit ${OPTS} --coverage-html testcoverage "

