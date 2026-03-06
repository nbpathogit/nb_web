# NB Patho Design System Documentation

## Overview

This design system provides a modern, consistent UI/UX framework for the NB Patho application. It uses a card-based layout with the primary color **#009CFF** as the foundation for all visual elements.

## Table of Contents

- [Color Palette](#color-palette)
- [CSS Variables](#css-variables)
- [Typography](#typography)
- [Spacing](#spacing)
- [Components](#components)
  - [Cards](#cards)
  - [Buttons](#buttons)
  - [Forms](#forms)
  - [Tables](#tables)
  - [Section Dividers](#section-dividers)
- [Layout Guidelines](#layout-guidelines)
- [Implementation Guide](#implementation-guide)

---

## Color Palette

### Primary Colors
- **Primary Blue**: `#009CFF`
- **Primary Dark**: `#007ACC` (used for gradients and hover states)
- **Primary Light**: `#E3F2FF` (used for backgrounds and highlights)

### Neutral Colors
- **Dark Text**: `#191C24`
- **Light Background**: `#F3F6F9`
- **White**: `#FFFFFF`
- **Border Color**: `#E8EEF5`
- **Muted Text**: `#495057`

### Functional Colors
- **Success Green**: `#28A745` (gradient to `#218838`)
- **Danger Red**: `#DC3545` (gradient to `#C82333`)
- **Table Row (Even)**: `#F8FBFE`

---

## CSS Variables

All styling uses CSS variables for easy theming and maintenance:

```css
:root {
    --primary-color: #009CFF;
    --primary-dark: #007ACC;
    --primary-light: #E3F2FF;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    --card-shadow-hover: 0 8px 25px rgba(0, 156, 255, 0.15);
}
```

---

## Typography

### Font Families
- **Primary**: 'Heebo', sans-serif (Google Fonts)
- **Icons**: Font Awesome 6.0.0
- **Bootstrap Icons**: Bootstrap Icons 1.4.1

### Font Sizes
- **Card Headers (h1)**: 1.5rem / 24px
- **Section Headers (h3)**: 1.1rem / 17.6px
- **Labels**: 0.9rem / 14.4px
- **Form Inputs**: 0.95rem / 15.2px
- **Table Headers**: 0.9rem / 14.4px
- **Table Cells**: 0.9rem / 14.4px

### Font Weights
- **Headers**: 600 (semi-bold)
- **Labels**: 600 (semi-bold)
- **Body Text**: 400 (regular)
- **Button Text**: 500 (medium)

---

## Spacing

### Standard Spacing Units
- **Card Padding**: 1.5rem (24px)
- **Header Padding**: 1.25rem × 1.5rem
- **Form Group Margin**: 1.25rem (20px)
- **Card Margin Bottom**: 1.5rem (24px)
- **Section Divider Margin**: 2rem (32px)
- **Custom HR Margin**: 2.5rem (40px)

### Border Radius
- **Cards**: 12px
- **Form Elements**: 8px
- **Buttons**: 8px
- **Table Headers**: 8px (corners)

---

## Components

### Cards

Modern card design with subtle shadows and hover effects.

#### Structure
```
.modern-card
├── .card-header-custom
│   └── h1 or h3
└── .card-body-custom
    └── content
```

#### Usage Example
```html
<div class="modern-card mb-4">
    <div class="card-header-custom">
        <h1><i class="fas fa-icon me-2"></i>Card Title</h1>
    </div>
    <div class="card-body-custom">
        <!-- Card content goes here -->
    </div>
</div>
```

#### Key Features
- Subtle shadow: `0 4px 6px rgba(0, 0, 0, 0.07)`
- Hover effect: Enhanced shadow and border color change
- Gradient header background
- Smooth transitions (0.3s ease)
- Rounded corners (12px)

---

### Buttons

Gradient buttons with smooth hover animations and icons.

#### Button Types

**Primary Button**
```html
<button class="modern-btn-primary">
    <i class="fas fa-icon me-2"></i>Button Text
</button>
```
- Gradient: #009CFF → #007ACC
- Shadow: `0 2px 8px rgba(0, 156, 255, 0.3)`
- Hover: Transforms up 2px, enhanced shadow

**Success Button**
```html
<button class="modern-btn-success">
    <i class="fas fa-icon me-2"></i>Button Text
</button>
```
- Gradient: #28A745 → #218838
- Shadow: `0 2px 8px rgba(40, 167, 69, 0.3)`

**Danger Button**
```html
<button class="modern-btn-danger">
    <i class="fas fa-icon me-2"></i>Button Text
</button>
```
- Gradient: #DC3545 → #C82333
- Shadow: `0 2px 8px rgba(220, 53, 69, 0.3)`

#### Button Styling
- Padding: 0.7rem × 1.8rem
- Font weight: 500
- Border radius: 8px
- Transition: all 0.3s ease
- No border (gradient background)

---

### Forms

Modern form controls with consistent styling and focus states.

#### Form Labels
```html
<label for="input-id" class="modern-label">
    <i class="fas fa-icon me-1"></i>Label Text
</label>
```
- Font weight: 600
- Color: `--primary-dark`
- Font size: 0.9rem
- Margin bottom: 0.5rem
- Supports icons

#### Text Inputs
```html
<input type="text" 
       id="input-id" 
       name="input-name" 
       class="modern-form-control"
       placeholder="Placeholder text">
```
- Border: 2px solid #E8EEF5
- Border radius: 8px
- Padding: 0.6rem × 1rem
- Background: #FAFBFD
- Focus: Primary color border with ring shadow
- Readonly: Lighter background (#F5F8FC)

#### Select Dropdowns
```html
<select id="select-id" 
        name="select-name" 
        class="modern-select" 
        required>
    <option value="">Select option</option>
    <!-- options -->
</select>
```
- Same styling as text inputs
- Cursor pointer
- Focus state with shadow ring

#### Form Groups
```html
<div class="form-group-custom">
    <label for="input-id" class="modern-label">Label</label>
    <input type="text" id="input-id" class="modern-form-control">
</div>
```
- Margin bottom: 1.25rem
- Consistent spacing

#### Form Rows (Grouped Fields)
```html
<div class="form-row-custom">
    <div class="row">
        <!-- form groups -->
    </div>
</div>
```
- Background: #FAFBFD
- Border radius: 10px
- Padding: 1.5rem
- Border: 1px solid #E8EEF5

---

### Tables

Modern data tables with gradient headers and interactive rows.

#### Structure
```html
<div class="table-container">
    <table id="table-id" class="modern-table" style="width:100%">
        <thead>
            <tr>
                <th>Header 1</th>
                <th>Header 2</th>
                <!-- more headers -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
                <!-- more data -->
            </tr>
            <!-- more rows -->
        </tbody>
    </table>
</div>
```

#### Table Styling
- **Header**: Gradient background, white text, uppercase
- **Even Rows**: Light background (#F8FBFE)
- **Hover**: Primary light background (#E3F2FF), scale(1.01), shadow
- **Cells**: Padding 0.9rem × 1rem, centered text
- **Borders**: Bottom border only (1px, #E8EEF5)
- **Corners**: Rounded top (header) and bottom (last row)

#### Key Features
- Smooth transitions on hover
- Scale effect on row hover
- No vertical borders (cleaner look)
- Consistent spacing
- Uppercase headers with letter spacing

---

### Section Dividers

Visual separators with gradient effects.

#### Custom HR
```html
<div class="modern-hr"></div>
```
- Height: 2px
- Background: Linear gradient (transparent → primary → transparent)
- Margin: 2.5rem (top and bottom)
- Opacity: 0.3

#### Section Title
```html
<div class="section-title">
    <i class="fas fa-icon me-2"></i>Section Title
</div>
```
- Font weight: 600
- Color: `--primary-dark`
- Font size: 1.2rem
- Left accent bar (4px × 24px, primary color)
- Flexbox alignment

---

## Layout Guidelines

### Container Structure
```html
<div class="container-fluid pt-4 px-4">
    <!-- Cards and content -->
</div>
```

### Card Spacing
- Use `mb-4` class for consistent bottom margin between cards
- Standard margin: 1.5rem (24px)

### Grid System
- Use Bootstrap 5 grid system (row, col-*)
- Responsive breakpoints: xl, md, sm
- Form groups typically use: `col-xl-4 col-md-6`

### Content Width
- Maintains sidebar layout (250px sidebar)
- Content width: `calc(100% - 250px)`
- Responsive: Full width on mobile

---

## Implementation Guide

### Step 1: Add CSS Styles

Include the design system CSS in your PHP file:

```php
<style>
    :root {
        --primary-color: #009CFF;
        --primary-dark: #007ACC;
        --primary-light: #E3F2FF;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        --card-shadow-hover: 0 8px 25px rgba(0, 156, 255, 0.15);
    }

    /* Add all component styles here */
    /* (See complete CSS in generate_label.php) */
</style>
```

### Step 2: Wrap Content in Cards

Replace old divs with modern cards:

**Before:**
```html
<div class="bg-nb bg-blue-a rounded p-3 mx-1 border border-secondary">
    <h1>Section Title</h1>
    <!-- content -->
</div>
```

**After:**
```html
<div class="modern-card mb-4">
    <div class="card-header-custom">
        <h1><i class="fas fa-icon me-2"></i>Section Title</h1>
    </div>
    <div class="card-body-custom">
        <!-- content -->
    </div>
</div>
```

### Step 3: Update Form Elements

Replace form controls with modern versions:

**Before:**
```html
<label for="input-id" class="form-label">Label</label>
<input type="text" id="input-id" class="form-control">
```

**After:**
```html
<label for="input-id" class="modern-label">
    <i class="fas fa-icon me-1"></i>Label
</label>
<input type="text" id="input-id" class="modern-form-control">
```

### Step 4: Update Tables

Replace table structure:

**Before:**
```html
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <!-- headers -->
    </thead>
    <tbody>
        <!-- rows -->
    </tbody>
</table>
```

**After:**
```html
<div class="table-container">
    <table class="modern-table" style="width:100%">
        <thead>
            <!-- headers -->
        </thead>
        <tbody>
            <!-- rows -->
        </tbody>
    </table>
</div>
```

### Step 5: Update Buttons

Replace button classes:

**Before:**
```html
<button class="btn btn-primary">Button Text</button>
```

**After:**
```html
<button class="modern-btn-primary">
    <i class="fas fa-icon me-2"></i>Button Text
</button>
```

### Step 6: Add Icons

Use Font Awesome icons throughout:
- Card headers
- Labels
- Buttons
- Section titles

**Icon Examples:**
- `fas fa-table` - Tables
- `fas fa-plus-circle` - Add actions
- `fas fa-print` - Print actions
- `fas fa-file-pdf` - PDF generation
- `fas fa-calendar` - Date fields
- `fas fa-cogs` - Settings
- `fas fa-trash-alt` - Delete actions

---

## Best Practices

### 1. Consistency
- Always use the modern component classes
- Maintain consistent spacing and margins
- Use icons consistently across similar actions

### 2. Accessibility
- Include proper labels for all form elements
- Use semantic HTML (h1, h3, etc.)
- Ensure color contrast meets WCAG standards
- Add `required` attributes to mandatory fields

### 3. Responsive Design
- Use Bootstrap grid system
- Test on mobile devices
- Use appropriate breakpoints (xl, md, sm)
- Ensure touch targets are large enough (min 44px)

### 4. Performance
- Use CSS transitions (not JavaScript) for animations
- Keep animations short (0.3s)
- Use transform for animations (better performance)

### 5. Maintenance
- Use CSS variables for all colors and dimensions
- Comment complex CSS rules
- Keep component styles modular
- Document custom classes

---

## Color Usage Guidelines

### When to Use Primary Color
- Card headers (gradient background)
- Buttons (gradient background)
- Table headers (gradient background)
- Form focus states
- Active/hover states
- Accent elements and icons

### When to Use Primary Dark
- Text labels and headings
- Hover states
- Gradient backgrounds (with primary)

### When to Use Primary Light
- Table row hover backgrounds
- Form focus ring backgrounds
- Highlight states
- Subtle background accents

### Neutral Colors
- **White**: Card bodies, input backgrounds
- **Light Gray (#F3F6F9)**: Page backgrounds
- **Medium Gray (#E8EEF5)**: Borders, dividers
- **Dark Gray (#191C24)**: Primary text, headings

---

## Common Patterns

### Form Section Pattern
```html
<div class="modern-card mb-4">
    <div class="card-header-custom">
        <h1><i class="fas fa-edit me-2"></i>Form Section</h1>
    </div>
    <div class="card-body-custom">
        <form>
            <div class="form-row-custom mb-4">
                <div class="row">
                    <div class="col-xl-4 col-md-6 form-group-custom">
                        <label for="field1" class="modern-label">
                            <i class="fas fa-icon me-1"></i>Field 1
                        </label>
                        <input type="text" id="field1" class="modern-form-control">
                    </div>
                    <!-- more fields -->
                </div>
            </div>
            
            <div class="modern-hr"></div>
            
            <div class="section-title">
                <i class="fas fa-sliders-h me-2"></i>Configuration
            </div>
            
            <div class="text-center mt-4">
                <button type="submit" class="modern-btn-primary">
                    <i class="fas fa-save me-2"></i>Save
                </button>
            </div>
        </form>
    </div>
</div>
```

### Data Table Pattern
```html
<div class="modern-card mb-4">
    <div class="card-header-custom">
        <h1><i class="fas fa-table me-2"></i>Data Table</h1>
    </div>
    <div class="card-body-custom">
        <div class="table-container">
            <div class="table-responsive">
                <table class="modern-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Column 1</th>
                            <th>Column 2</th>
                            <th>Column 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- data rows -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-center mt-4">
            <button class="modern-btn-success">
                <i class="fas fa-plus me-2"></i>Add New
            </button>
        </div>
    </div>
</div>
```

### Action Buttons Pattern
```html
<div class="text-center">
    <button class="modern-btn-primary me-2">
        <i class="fas fa-check me-2"></i>Confirm
    </button>
    <button class="modern-btn-danger">
        <i class="fas fa-times me-2"></i>Cancel
    </button>
</div>
```

---

## Maintenance Notes

### Version History
- **v1.0** (2024): Initial design system implementation for generate_label.php

### Future Enhancements
- Dark mode support
- Additional color themes
- More animation options
- Advanced form validation styles
- Loading states and spinners

### Files Using This Design System
- `generate_label.php` (full implementation)

### Dependencies
- Bootstrap 5.0+
- Font Awesome 6.0+
- jQuery (for datepicker)
- jQuery UI (for datepicker)

---

## Support and Documentation

For questions or issues related to this design system:
1. Check this documentation first
2. Review `generate_label.php` for implementation examples
3. Test changes in a development environment first
4. Maintain consistency with existing styled pages

---

**Last Updated**: 2024
**Maintained By**: NB Patho Development Team