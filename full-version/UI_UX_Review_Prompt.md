# 🎯 UI/UX Full Review — Prompt for AI Execution

## Context
The UI has been fully built. Your task is to **review every point below thoroughly**, test each one, fix any issues found, and report the result.

Do NOT skip any point. Go through every section systematically.

> 🔴 **CORE RULE — Single Page Application (SPA):**
> This project is a SPA. Every Add and Edit operation **must** open in a **Popup/Modal** — never navigate to a separate page. If any Add or Edit currently opens a new page or route, it must be converted to a popup immediately.

---

## 📌 SECTION 1 — Add Popup (البوب آب - إضافة)

**Review and fix if needed:**
- [ ] Popup opens correctly when clicking the Add button
- [ ] All required fields are present — nothing is missing
- [ ] Validation works: required fields show error if empty, formats are correct (email, phone, number, etc.)
- [ ] After saving: popup closes and new data appears immediately in the list/table
- [ ] Success message (toast/alert) appears after successful add

---

## 📌 SECTION 2 — Edit Popup (البوب آب - تعديل)

**Review and fix if needed:**
- [ ] Popup opens correctly and loads the existing data into the fields
- [ ] All fields are present and pre-filled with the correct current values
- [ ] After saving: data updates immediately in the list/table without page refresh
- [ ] Success message appears after successful edit

---

## 📌 SECTION 3 — Delete (الحذف)

**Review and fix if needed:**
- [ ] Clicking delete shows a Confirmation Dialog before anything is deleted
- [ ] Confirmation dialog has two buttons: Confirm and Cancel
- [ ] Clicking Cancel closes the dialog and nothing is deleted
- [ ] Clicking Confirm deletes the item and removes it from the UI immediately
- [ ] Success message appears after successful deletion

---

## 📌 SECTION 4 — Change Status (تغيير الحالة)

**Review and fix if needed:**
- [ ] A button or toggle exists to change the status of each record
- [ ] Status changes immediately in the UI after action
- [ ] Color and label update with the status (e.g., green = active, red = inactive/suspended)
- [ ] If a confirmation is needed before changing status, it appears correctly

---

## 📌 SECTION 5 — Form Fields (الفيلدز)

**Review and fix if needed:**
- [ ] Every form has all required fields — nothing missing
- [ ] All labels are clear and in the correct language
- [ ] Placeholder text exists where needed
- [ ] Required fields are marked with (*) or equivalent indicator

---

## 📌 SECTION 6 — Loading States

**Review and fix if needed:**
- [ ] A loading indicator appears during any async operation (add, edit, delete, fetch)
- [ ] Buttons are disabled during loading to prevent duplicate submissions

---

## 📌 SECTION 7 — Empty States

**Review and fix if needed:**
- [ ] When there is no data, an empty state message or illustration is shown
- [ ] Empty state is clear and guides the user on what to do next

---

## 📌 SECTION 8 — Error Handling

**Review and fix if needed:**
- [ ] If an error occurs, a clear error message is shown to the user
- [ ] Error messages are in the correct language and are user-friendly (not raw API errors)

---

## 📌 SECTION 9 — Design Consistency Across All Pages (اتساق التصميم)

**Review and fix if needed:**
- [ ] Colors are identical across all pages — no page has a different color scheme
- [ ] Typography (font family, sizes, weights) is consistent across all pages
- [ ] Spacing and padding are consistent across all pages
- [ ] Cards and containers look identical across all pages
- [ ] Shadows and borders are consistent
- [ ] Border radius is uniform across similar elements
- [ ] No page feels visually different or disconnected from the rest of the project

---

## 📌 SECTION 10 — Select & Dropdowns (الـ Select)

**Review and fix if needed:**
- [ ] All Select components open correctly and display their options
- [ ] Placeholder is visible before a selection is made
- [ ] Selected value displays correctly after choosing
- [ ] Multi-select (if present) works correctly
- [ ] Searchable select (if present): search inside it works correctly
- [ ] Select design is visually consistent across all forms
- [ ] Select shows validation error if left empty when required
- [ ] Select dropdown does not overlap other elements (z-index is correct)

---

## 📌 SECTION 11 — Action Buttons (أزرار الإجراءات)

**Review and fix if needed:**
- [ ] Action buttons (edit, delete, view, change status) are visually identical across all pages
- [ ] Same icon is used for the same action everywhere in the project
- [ ] Same color for the same button type everywhere (e.g., red = delete, blue = edit)
- [ ] Tooltip appears on icon-only buttons so users know their function
- [ ] Save and Cancel buttons in forms follow the same order and style across all pages

---

## 📌 SECTION 12 — Reports Page (صفحة التقارير الشاملة)

**Review and implement if needed:**
- [ ] Each tab that currently exists inside the Reports page must be converted to its own **separate independent page**
- [ ] Clear navigation exists to reach each report page
- [ ] Each report page has its own unique URL/route
- [ ] Navigation between report pages is intuitive and easy
- [ ] Each report page has a clear, descriptive title

---

## 📌 SECTION 13 — Filter & Search (الفلتر والبحث)

**Review and fix if needed:**
- [ ] Every page that needs filtering has a date filter: either a single date picker OR a date range (From / To)
- [ ] A search field exists on all pages that need it
- [ ] Search and filter options vary per page based on the data available on that page
- [ ] A Reset/Clear button exists to clear all active filters
- [ ] Results update immediately or after pressing a Search button — behavior must be clear
- [ ] Filter and search controls are placed consistently across all pages (e.g., always above the table)

---

## 📌 SECTION 14 — Image Upload (رفع الصور)

**Review and fix if needed:**
- [ ] All image upload areas across the project use the **same unified component** as the one used in the Contract Image (صورة العقد)
- [ ] A preview of the uploaded image is shown after upload
- [ ] A button exists to remove or replace the uploaded image
- [ ] Allowed file types and max file size are shown to the user
- [ ] A loading indicator appears while the image is uploading
- [ ] If the file is too large or the wrong type, a clear error message appears

---

## 📌 SECTION 15 — Icons (الأيقونات)

**Review and fix if needed:**
- [ ] All icons render correctly — no broken or missing icons anywhere
- [ ] All icons come from the same icon library (consistent style and stroke weight)
- [ ] Icon sizes are consistent in similar contexts
- [ ] Icons clearly represent their function
- [ ] No icon overlaps with text or other UI elements

---

## 📌 SECTION 16 — Create Order with Unregistered Customer (إنشاء أوردر لعميل غير مسجل)

**Context:**
When creating a new order, the customer might not exist in the system yet.
It is NOT acceptable to force the user to leave the order form, go to the Customers page, add the customer there, then come back to create the order.
Everything must happen **in one place, in one flow.**

**Implement the following behavior inside the Create Order page/popup:**

- [ ] The customer select/search field has a **"+ Add New Customer"** option that appears when no match is found OR as a fixed option at the top/bottom of the dropdown
- [ ] Clicking "Add New Customer" opens a **quick inline form or a small popup** (do NOT navigate away from the order form)
- [ ] The quick customer form contains only the essential fields needed to register a new customer (name, phone, email — minimal required info)
- [ ] After saving the new customer, they are **automatically selected** in the order form — the user does not need to search or select manually
- [ ] The order form stays open and intact — no data entered so far is lost
- [ ] The newly added customer is also saved to the Customers list in the background
- [ ] If the user cancels the quick customer form, they return to the order form with everything still in place
- [ ] The flow is seamless: create customer → auto-selected → continue filling the order → save — all without leaving the page

---

## 📌 SECTION 17 — SPA Enforcement: All Add & Edit Must Be Popups

**Context:**
This is a Single Page Application (SPA). The user must **never be navigated to a different page** to add or edit any record.
Any Add or Edit that currently routes to a new page is a bug and must be fixed.

**Review every single Add and Edit action across the entire project:**

- [ ] Every "Add" button opens a **Modal/Popup** — not a new page
- [ ] Every "Edit" button opens a **Modal/Popup** — not a new page
- [ ] No Add or Edit action changes the URL to a different route (e.g., `/customers/add` or `/orders/edit/5` as a full page)
- [ ] Popups open on top of the current page without losing the page context behind them
- [ ] Pressing Escape or clicking outside the popup closes it (with a confirmation if there is unsaved data)
- [ ] The page behind the popup does not scroll or jump when the popup opens
- [ ] After saving from a popup, the list/table on the page updates immediately without a full page reload
- [ ] All popups are consistent in size, structure, header style, and close button position across the entire project

**If any Add or Edit is currently a separate page, convert it to a popup. This is mandatory.**

---



After reviewing and fixing everything, respond using this exact format:

```
### ✅ Done & Working
List every point that was already correct.

### 🔧 Fixed
List every issue that was found and fixed, with a short description of what was wrong and what was changed.

### ⚠️ Needs Attention (Cannot Fix Automatically)
List any points that need a design decision, content, or backend data before they can be resolved.

---
### 📊 Summary
- Total points reviewed: X
- ✅ Already correct: X
- 🔧 Fixed: X
- ⚠️ Needs attention: X
```

---

## ⚠️ IMPORTANT RULES

1. **Do not skip any section or point.** Every checkbox must be evaluated.
2. **Fix issues directly** — do not just report them unless the fix requires external input.
3. **Consistency is the priority** — if something looks different on one page vs another, make them match.
4. **Do not change functionality** — only fix UI/UX issues unless a section explicitly asks for a structural change (like Section 12).
5. **The Reports page tabs (Section 12) must become separate pages** — this is a required structural change, not optional.
