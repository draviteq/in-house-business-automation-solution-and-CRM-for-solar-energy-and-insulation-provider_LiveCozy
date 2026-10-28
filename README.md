# InstallationFormsController - CakePHP Code Snippet

This repository contains a CakePHP controller (`InstallationFormsController.php`) for managing dynamic installation forms, including input fields, options, and installation product types. The controller provides CRUD operations for form fields, handles AJAX requests, and supports dynamic field ordering and editing.

---

## Features

- **Dynamic Form Field Management:** Add, edit, delete, and reorder installation form fields.
- **Support for Multiple Input Types:** Text, notes, checkbox, radio, single/multiple choice, file, signature, date, and more.
- **AJAX Operations:** All field operations are handled asynchronously for a smooth user experience.
- **Installation Product Type Management:** Easily manage installation types and their flags (site audit, installation, quality check).
- **Customizable Frontend:** Returns HTML snippets for dynamic form rendering.

---

## Requirements

- PHP 7.2+
- [CakePHP 3.x](https://book.cakephp.org/3/en/installation.html) or compatible version
- Composer

---

## CakePHP Installation

If you do not have CakePHP installed, follow these steps:

1. **Install Composer**  
   Download and install Composer from [https://getcomposer.org/](https://getcomposer.org/).

2. **Create a New CakePHP Project**  
   ```sh
   composer create-project --prefer-dist cakephp/app my_app_name
   cd my_app_name
   ```

3. **Configure Database**  
   Edit `config/app.php` and set your database connection details.

---

## Integrating the Code Snippet

1. **Copy the Controller**

   Place `InstallationFormsController.php` in your CakePHP project's `src/Controller/` directory.

2. **Required Models**

   Ensure you have the following models in `src/Model/Table/`:
   - `InputTypesTable.php`
   - `InstallationFormFieldsTable.php`
   - `InstallationFormOptionsTable.php`
   - `InstallationProductTypesTable.php`

   These should correspond to your database tables.

3. **Database Tables**

   Make sure your database has the required tables:
   - `input_types`
   - `installation_form_fields`
   - `installation_form_options`
   - `installation_product_types`

   Adjust the controller/model code as needed to match your schema.

4. **Routes**

   Add routes in `config/routes.php` if needed:
   ```php
   $routes->connect('/installation-forms', ['controller' => 'InstallationForms', 'action' => 'index']);
   $routes->connect('/installation-forms/edit/*', ['controller' => 'InstallationForms', 'action' => 'edit']);
   // Add other routes as needed
   ```

5. **Assets**

   The controller references SweetAlert CSS/JS. Place these assets in your webroot or use CDN links:
   - `/plugins/bower_components/sweetalert/sweetalert.css`
   - `/plugins/bower_components/sweetalert/sweetalert.min.js`
   - `/plugins/bower_components/sweetalert/jquery.sweet-alert.custom.js`

6. **Constants**

   The code uses constants like `_ID_INSTALLATION_PRODUCT_TYPE`, `_ID_FLAG_YES`, etc.  
   Define these in a config file or in your `config/bootstrap.php` as needed.

---

## Usage

- Access `/installation-forms` in your browser to view and manage installation forms.
- Use the provided AJAX endpoints for dynamic field operations (add, edit, delete, reorder).
- The controller returns HTML snippets for dynamic form rendering in the frontend.

---

## Customization

- Update the constants and model methods as per your application's configuration.
- Modify the HTML output methods for custom frontend requirements.
- Adjust validation and business logic as needed.

---

## Support

For issues or questions, please open an issue in this repository.

---

**Note:**  
This code is a snippet and may require adaptation to fit your application's structure and database schema.
