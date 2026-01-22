# Typography Schema Implementation

## Overview
A comprehensive typography system has been implemented for your WordPress theme. This schema ensures consistent typography across all posts, pages, and future content.

## Typography Specifications

### Headings

| Element | Font Size | Line Height | Font Weight |
|---------|-----------|-------------|-------------|
| H1      | 54px      | 120%        | 600         |
| H2      | 44px      | 120%        | 600         |
| H3      | 40px      | 120%        | 600         |
| H4      | 22px      | 120%        | 600         |
| H5      | 20px      | 120%        | 600         |
| H6      | 16px      | 120%        | 600         |

### Paragraphs

| Element           | Font Size | Line Height | Font Weight |
|-------------------|-----------|-------------|-------------|
| Large Paragraph   | 18px      | 120%        | 400         |
| Default Paragraph | 16px      | 120%        | 400         |

### Buttons

| Element       | Font Size | Line Height | Font Weight | Padding      |
|---------------|-----------|-------------|-------------|--------------|
| Large Button  | 16px      | 24px        | 500         | 12px 32px    |
| Medium Button | 14px      | 24px        | 500         | 10px 24px    |

## Responsive Breakpoints

### Tablet (768px - 1024px)
- H1: 44px
- H2: 36px
- H3: 32px
- H4: 20px
- H5: 18px
- H6: 15px
- Large Paragraph: 17px
- Default Paragraph: 15px

### Mobile (up to 767px)
- H1: 36px
- H2: 30px
- H3: 26px
- H4: 18px
- H5: 16px
- H6: 14px
- Large Paragraph: 16px
- Default Paragraph: 14px

## Files Created

1. **`/assets/scss/_typography-schema.scss`**
   - SCSS source file with the typography schema
   - Imported in `style-main.scss`

2. **`/assets/css/typography-schema.css`**
   - Compiled CSS file
   - Automatically enqueued on all pages
   - Also loaded in the WordPress block editor

## Implementation Details

### Automatic Application
The typography schema is automatically applied to:
- All existing posts
- All future posts
- Pages
- Custom post types
- WordPress block editor (Gutenberg)

### CSS Selectors
The schema targets these content areas:
- `.blog_single_item`
- `.editor-content`
- `.post-content`
- `.entry-content`
- `article`

### How It Works
1. The CSS file is enqueued in `/inc/enqueue.php`
2. It loads on every page load
3. Styles are applied with `!important` flags to override any conflicting styles
4. The schema is also loaded in the WordPress editor for WYSIWYG editing

## Usage

### For Content Creators
Simply create posts using the WordPress editor. All headings and paragraphs will automatically use the defined typography schema.

### For Developers
If you need to use specific typography classes:

```html
<!-- Headings -->
<h1>This is H1</h1>
<h2>This is H2</h2>

<!-- Paragraphs -->
<p>Default paragraph</p>
<p class="paragraph-large">Large paragraph</p>

<!-- Buttons -->
<button class="btn-large">Large Button</button>
<button class="btn-medium">Medium Button</button>
```

## Customization

To modify the typography schema:

1. **Edit SCSS (Recommended)**
   - Edit `/assets/scss/_typography-schema.scss`
   - Compile to CSS (if you have a build tool)
   - Or manually update the CSS file

2. **Edit CSS Directly**
   - Edit `/assets/css/typography-schema.css`
   - Changes will apply immediately

## Testing

To verify the implementation:
1. Create a new post or edit an existing one
2. Add various heading levels (H1-H6)
3. Add paragraphs
4. Preview the post
5. Check that all typography matches the schema

## Browser Compatibility
The typography schema uses standard CSS properties and is compatible with all modern browsers:
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers

## Notes
- The schema uses `!important` flags to ensure it overrides any conflicting styles
- Line height is set to 120% for optimal readability
- All measurements are in pixels for consistency
- Responsive breakpoints ensure readability on all devices
