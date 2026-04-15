# Architecture

## Overview

This system manages devices deployed across various locations for multiple tenants. The UI is a real-time status card requiring aggregated data from device telemetry, subscription management, and other such data.

## Business Logic & Assumptions

1. Multi-Tenancy Schema:

The platform serves multiple clients so data is scoped by `tenant_id`. A Tenant has many Locations, and a Location has many Devices. (This is also where we'd slice things if - as Dion mentioned - the tables get too huge)

2. Device Identity:

"Avenue du President Rene Coty" represents the assigned `name` of the device, probably from its installation location. `Latitude` & `Longitude` hold the coordinates for the precise location of the device and we store with sub-centimeter accuracy so the device can be found (if they're always accessible then this accuracy isn't needed). "Intellisonde DI" is the `DeviceModel`.

3. Alert State:

The UI shows distinct warning states (Red triangle or hardware icons). I guessing an "Alert State" is automatically triggered when telemetry thresholds are breached (e.g. `sensor_days_remaining <= 5`). That's business logic so should be handled by the backend, passing a `has_alert` boolean to the frontend. I've not implemented this but there's an `artisan app:` command to simulate it...

4. Telemetry Data Flow:

Physical devices push data via webhooks. To balance read/write performance, the webhook inserts an append-only row into `telemetry_logs` (for historical charting) and updates the latest state directly on the `devices` table for fast UI reads.

5. Progress Sars:

The "Battery charge" progress bar will use a computed value (79 days/X max battery days / 100), as will the "Sensor pack" progress bar (2 day/X max sensor day / 100). They'll animate with a subtle ease-in-out interpolation.

6. Subscriptions & Replacements:

"SondeCare+" is a subscription plan I'd imagine so a device has a `hasOne` relationship with a Subscription and a Replacement order.

7. UI Interactions:

The "Delivered on 2026-03-04" text has a dotted underline, which suggests a tooltip usually, but I don't know what data it'd surface.

I'm going to assume the white-on-blue "Order now" pill is solely a pill and not a small call to action button.

8. Asides:

Of the three icons in the top-right of the card (battery, sensor, connectivity), only the battery looks visually different - it's hollow - colour is used to denote state but someone who has a colour vision deficiency is missing information there.
