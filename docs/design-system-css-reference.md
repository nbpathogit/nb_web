# NB Patho Design System - CSS Quick Reference

## Complete CSS Stylesheet

Copy this entire `<style>` block into the `<head>` section of any PHP file to apply the modern design system:

```css
<style>
    :root {
        --primary-color: #009CFF;
        --primary-dark: #007ACC;
        --primary-light: #E3F2FF;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        --card-shadow-hover: 0 8px 25px rgba(0, 156, 255, 0.15);
    }

    .modern-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 156, 255, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .modern-card:hover {
        box-shadow: var(--card-shadow-hover);
        border-color: var(--primary-color);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        padding: 1.25rem 1.5rem;
        border: none;
        border-bottom: 3px solid rgba(255, 255, 255, 0.3);
    }

    .card-header-custom h1 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        color: #ffffff;
    }

    .card-header-custom h3 {
        font-size: 1.1rem;
        font-weight: 500;
        margin: 0;
        color: #ffffff;
        opacity: 0.95;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    .modern-label {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .modern-form-control {
        border: 2px solid #E8EEF5;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #FAFBFD;
    }

    .modern-form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 156, 255, 0.1);
        background: #ffffff;
    }

    .modern-form-control[readonly] {
        background: #F5F8FC;
        cursor: default;
    }

    .modern-select {
        border: 2px solid #E8EEF5;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #FAFBFD;
        cursor: pointer;
    }

    .modern-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 156, 255, 0.1);
        background: #ffffff;
    }

    .modern-btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        color: #ffffff;
        padding: 0.7rem 1.8rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 156, 255, 0.3);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 156, 255, 0.4);
        color: #ffffff;
    }

    .modern-btn-success {
        background: linear-gradient(135deg, #28A745 0%, #218838 100%);
        border: none;
        color: #ffffff;
        padding: 0.8rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .modern-btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        color: #ffffff;
    }

    .modern-btn-danger {
        background: linear-gradient(135deg, #DC3545 0%, #C82333 100%);
        border: none;
        color: #ffffff;
        padding: 0.7rem 1.8rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .modern-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        color: #ffffff;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        font-weight: 600;
        padding: 1rem;
        text-align: center;
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table thead th:first-child {
        border-top-left-radius: 8px;
    }

    .modern-table thead th:last-child {
        border-top-right-radius: 8px;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:nth-child(even) {
        background: #F8FBFE;
    }

    .modern-table tbody tr:hover {
        background: var(--primary-light);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 156, 255, 0.1);
    }

    .modern-table tbody td {
        padding: 0.9rem 1rem;
        border: none;
        border-bottom: 1px solid #E8EEF5;
        text-align: center;
        color: #495057;
        font-size: 0.9rem;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .modern-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }

    .modern-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    .form-group-custom {
        margin-bottom: 1.25rem;
    }

    .form-row-custom {
        background: #FAFBFD;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #E8EEF5;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
        margin: 2rem 0;
        opacity: 0.3;
    }

    .modern-hr {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
        margin: 2.5rem 0;
    }

    .table-container {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 156, 255, 0.1);
    }

    .section-title {
        color: var(--primary-dark);
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 24px;
        background: var(--primary-color);
        border-radius: 2px;
    }

    .select-label-inline {
        font-weight: 600;
        color: var(--primary-dark);
        margin-right: 0.5rem;
    }

    .config-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        border: 2px solid #E8EEF5;
    }

    @media (max-width: 768px) {
        .modern-table {
            font-size: 0.8rem;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.6rem 0.5rem;
        }
    }
</style>
```

---

## Quick Copy Templates

### Basic Card Template

```html
<div class="modern-card mb-4">
    <div class="card-header-custom">
        <h1><i class="fas fa-icon me-2"></i>Card Title</h1>
    </div>
    <div class="card-body-custom">
        <!-- Your content here -->
    </div>
</div>
```

### Form Template

```html
<div class="modern-card mb-4">
    <div class="card-header-custom">
        <h1><i class="fas fa-edit me-2"></i>Form Title</h1>
    </div>
    <div class="card-body-custom">
        <form>
            <div class="form-row-custom mb-4">
                <div class="row">
                    <div class="col-xl-4 col-md-6 form-group-custom">
                        <label for="field1" class="modern-label">
                            <i class="fas fa-icon me-1"></i>Field Label
                        </label>
                        <input type="text" id="field1" class="modern-form-control" placeholder="Enter value">
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="modern-btn-primary">
                    <i class="fas fa-save me-2"></i>Submit
                </button>
            </div>
        </form>
    </div>
</div>
```

### Table Template

```html
<div class="modern-card mb-4">
    <div class="card-header-custom">
        <h1><i class="fas fa-table me-2"></i>Table Title</h1>
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
                        <tr>
                            <td>Data 1</td>
                            <td>Data 2</td>
                            <td>Data 3</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
```

### Button Templates

```html
<!-- Primary Button -->
<button class="modern-btn-primary">
    <i class="fas fa-check me-2"></i>Primary Action
</button>

<!-- Success Button -->
<button class="modern-btn-success">
    <i class="fas fa-plus me-2"></i>Success Action
</button>

<!-- Danger Button -->
<button class="modern-btn-danger">
    <i class="fas fa-trash me-2"></i>Danger Action
</button>
```

### Section Divider Template

```html
<div class="modern-hr"></div>

<div class="section-title">
    <i class="fas fa-icon me-2"></i>Section Title
</div>
```

---

## Common Font Awesome Icons

| Icon | Class | Usage |
|------|-------|-------|
| Table | `fas fa-table` | Data tables |
| Plus | `fas fa-plus-circle` | Add actions |
| Edit | `fas fa-edit` | Edit forms |
| Print | `fas fa-print` | Print actions |
| PDF | `fas fa-file-pdf` | PDF generation |
| Calendar | `fas fa-calendar` | Date fields |
| Hospital | `fas fa-hospital` | Hospital numbers |
| Barcode | `fas fa-barcode` | Serial numbers |
| File Medical | `fas fa-file-medical` | Medical records |
| Trash | `fas fa-trash-alt` | Delete actions |
| Save | `fas fa-save` | Save actions |
| Check | `fas fa-check` | Confirm actions |
| Times | `fas fa-times` | Cancel actions |
| Settings | `fas fa-cogs` | Configuration |
| Search | `fas fa-search` | Search fields |
| Filter | `fas fa-filter` | Filter options |

---

## Implementation Checklist

- [ ] Copy the complete CSS stylesheet into the `<head>` section
- [ ] Replace `bg-nb bg-blue-a` divs with `modern-card` class
- [ ] Add `card-header-custom` and `card-body-custom` wrappers
- [ ] Update form controls to `modern-form-control` and `modern-select`
- [ ] Update labels to `modern-label` class
- [ ] Replace `btn btn-primary` with `modern-btn-primary`
- [ ] Replace table classes with `modern-table`
- [ ] Add `table-container` wrapper around tables
- [ ] Add appropriate Font Awesome icons
- [ ] Test responsive behavior on mobile devices
- [ ] Verify all hover effects and transitions work
- [ ] Check color contrast and accessibility

---

## Color Variables Reference

```css
/* Primary Colors */
--primary-color: #009CFF;    /* Main primary blue */
--primary-dark: #007ACC;     /* Darker shade for gradients */
--primary-light: #E3F2FF;    /* Light shade for backgrounds */

/* Effects */
--card-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
--card-shadow-hover: 0 8px 25px rgba(0, 156, 255, 0.15);
```

---

## File Locations

- **Main Documentation**: `docs/design-system.md`
- **CSS Reference**: `docs/design-system-css-reference.md` (this file)
- **Example Implementation**: `generate_label.php`

---

**Note**: Always test changes in a development environment before applying to production pages. Maintain consistency with existing styled pages.