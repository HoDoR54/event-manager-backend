.DEFAULT_GOAL := help

HOST ?= 0.0.0.0
PORT ?= 8080

.PHONY: help run start migrate test-db lint

help: ## Show this help message
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'

run: ## Start the local PHP built-in server (e.g. make run PORT=8080)
	php -S $(HOST):$(PORT) -t public

start: run ## Alias for 'run'

migrate: ## Apply database migrations from database/migrations
	php scripts/migrate.php

test-db: ## Verify PostgreSQL database connectivity
	php scripts/test-db.php

lint: ## Check PHP syntax for all project files
	find src public scripts -name '*.php' -print0 | xargs -0 -n1 php -l
