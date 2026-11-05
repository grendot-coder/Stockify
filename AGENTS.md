# Stockify — Project Instructions

## Project Overview
Stockify is a warehouse inventory management application built with Laravel 10, MySQL, Tailwind CSS, and Flowbite. It manages products, categories, suppliers, stock movements, users, and inventory reports.

## Before Making Changes
- Inspect the existing project structure and relevant files before editing.
- Check `composer.json`, `package.json`, routes, controllers, models, migrations, and Blade views as needed.
- Reuse existing layouts, components, naming conventions, and dependencies.
- Explain the proposed approach before making large or risky changes.

## Laravel Guidelines
- Preserve compatibility with the project's existing Laravel 10 version.
- Follow established Laravel MVC conventions.
- Use Blade for server-rendered pages unless the project already uses another approach.
- Use Eloquent relationships and validation patterns consistent with the existing code.
- Never modify database structure without explaining the required migration.
- Never run destructive database commands without explicit permission.

## Frontend Guidelines
- Use the existing Tailwind CSS and Flowbite setup.
- Build consistent layouts, navigation, tables, forms, dialogs, and dashboard components.
- Prioritize clear visual hierarchy, readable typography, useful spacing, and responsive layouts.
- Keep the interface professional and appropriate for warehouse management.
- Reuse existing fonts, colors, and design tokens unless a redesign is requested.
- Do not add frontend libraries or replace existing dependencies without permission.

## Code Safety
- Do not overwrite working features just to simplify implementation.
- Do not expose secrets, credentials, or environment variables.
- Avoid unrelated changes when implementing a feature.
- Explain important changes and list the files modified.
- Run relevant tests or build checks when practical, and report failures honestly.

## Working Style
- Work in small, verifiable steps.
- Ask before making major architectural decisions.
- Prefer maintainable, beginner-readable code over unnecessary abstraction.
- If project requirements are unclear, inspect the existing implementation before making assumptions.