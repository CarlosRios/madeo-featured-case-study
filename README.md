# Madeo Featured Case Study

A small WordPress plugin that registers a custom ACF Gutenberg block called **Featured Case Study**.

## Goal

The block allows an editor to select a case study or post, choose a layout variant, optionally override the heading, and render the selected item on the frontend with its latest title, featured image, excerpt, and link.

## Setup

1. Install and activate WordPress.
2. Install and activate ACF Pro.
3. Activate this plugin: **Madeo Featured Case Study**.
4. Create at least one Case Study or Post with a title, excerpt, featured image, and published status.
5. Add the **Featured Case Study** block to a page.
6. Select a case study or post, choose a layout, and optionally add a custom heading.

## Running locally with WordPress Playground CLI

From the WordPress site root:

```bash
cd "/Users/carlosrios/Code/STX Web Design/Sites/madeo"
npx @wp-playground/cli@latest start --port=9400 --skip-browser
```

Then open:

- Frontend: `http://127.0.0.1:9400/`
- Admin: `http://127.0.0.1:9400/wp-admin/`

## Technical Decisions

- Built as a small plugin so the block is portable and not tied to a specific theme.
- Registers a simple `case_study` custom post type in PHP instead of relying on ACF for the post type.
- Uses ACF Pro for block fields and editor controls.
- Uses dynamic rendering so selected content stays current when the case study or post changes.
- Stores editor choices in ACF fields instead of duplicating post data in block markup.
- Handles missing, unpublished, or deleted content gracefully.
- Escapes frontend output and validates selected post data before rendering.
- Separates block registration, render template, frontend CSS, editor CSS, and small rendering helpers.

## Block Fields

- **Case Study or Post**: required post object field limited to published `case_study` and `post` content.
- **Layout**: button group with `Image left` and `Image top` variants.
- **Custom Heading**: optional text override. If empty, the selected item's title is used.

## Assumptions

- ACF Pro is available, as provided for the exercise.
- The plugin treats ACF Pro as a dependency, but fails gracefully with an admin notice if ACF Pro is inactive.
- A simple `case_study` custom post type is registered by the plugin because the environment does not already provide one.
- ACF fields are registered in PHP so the field group is version-controlled, portable, and easy for another developer to review without relying on database state.
- Visual styling is intentionally lightweight because the focus is architecture, maintainability, security, and editor experience.

## Development checks

Run lightweight helper tests from the plugin root:

```bash
php tests/rendering-test.php
```

Run PHP syntax checks:

```bash
find . -name '*.php' -not -path './.git/*' -print0 | xargs -0 -n1 php -l
```
