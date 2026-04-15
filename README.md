# Readme

## Upgrade

```bash
composer global require laravel/installer # Ensure latest Laravel
bun upgrade # Ensure latest Bun
```

## Create

```bash
laravel new intellitect_water_device --react --no-authentication --pest --bun --boost 
cd intellitect_water_device
```

## Seed

```bash
php artisan migrate:fresh --seed
```

## Lint & Test
```bash
./vendor/bin/pint --parallel # Lint Backend
bun run lint:ts # Lint Frontend
composer run test # Test Backend
bun run test:unit # Test Frontend (fast unit tests)
bun run test:stories # Test Frontend (slow stories tests)
bun run storybook # View Storybook
```

## Build & Run

```bash
bun run build # Build Frontend
composer run dev # Serve Development
```

Browse URL http://localhost:8000/

## Simulate telemetry

```bash
php artisan app:simulate-telemetry 1
php artisan app:simulate-device-changes 1 # Requires a page reload
```
