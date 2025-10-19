# PHP Todo List Application

A modern, fully-featured Todo List application built with PHP, MySQL, and vanilla JavaScript. This application is designed to be easily deployed on cPanel hosting.

## Features

✨ **Full CRUD Operations** - Create, Read, Update, and Delete tasks  
🎯 **Priority Levels** - Organize tasks by Low, Medium, or High priority  
✅ **Task Status** - Mark tasks as Pending or Completed  
🔍 **Filtering** - View All, Pending, or Completed tasks  
📊 **Sorting** - Sort by Date (newest/oldest), Priority, or Title  
📱 **Responsive Design** - Works perfectly on desktop, tablet, and mobile  
🎨 **Modern UI** - Beautiful gradient design with smooth animations  
📈 **Statistics Dashboard** - Track total, pending, and completed tasks  

## File Structure

```
todo-list/
├── index.php          # Main application page
├── api.php            # API endpoint for AJAX operations
├── config.php         # Database configuration
├── database.sql       # Database schema
├── style.css          # Stylesheet
├── script.js          # JavaScript functionality
├── .htaccess          # Apache configuration
└── README.md          # This file
```

## Installation on cPanel

### Step 1: Upload Files

1. Log in to your cPanel account
2. Open **File Manager**
3. Navigate to `public_html` (or your desired directory)
4. Create a new folder (e.g., `todo-list`) or use the root directory
5. Upload all project files to this folder

### Step 2: Create MySQL Database

1. In cPanel, open **MySQL® Databases**
2. Create a new database:
   - Database Name: `todo_db` (or any name you prefer)
   - Click **Create Database**

3. Create a database user:
   - Username: `todo_user` (or any name you prefer)
   - Password: Create a strong password
   - Click **Create User**

4. Add user to database:
   - Select the user and database you created
   - Grant **ALL PRIVILEGES**
   - Click **Add**

### Step 3: Import Database Schema

1. In cPanel, open **phpMyAdmin**
2. Select your database from the left sidebar
3. Click the **SQL** tab
4. Open the `database.sql` file and copy its contents
5. Paste the SQL code into the text area
6. Click **Go** to execute

### Step 4: Configure Database Connection

1. Open `config.php` in File Manager's code editor
2. Update the database credentials:

```php
define('DB_HOST', 'localhost');           // Usually 'localhost'
define('DB_USER', 'your_db_username');    // Your database username
define('DB_PASS', 'your_db_password');    // Your database password
define('DB_NAME', 'your_db_name');        // Your database name
```

3. Save the file

### Step 5: Set Permissions (if needed)

Ensure files have proper permissions:
- Files: 644
- Directories: 755

You can set these in File Manager by right-clicking files/folders → **Change Permissions**

### Step 6: Access Your Application

Visit your application in a browser:
- If installed in root: `https://yourdomain.com/`
- If in subdirectory: `https://yourdomain.com/todo-list/`

## Usage

### Adding a Task

1. Enter task title in the main input field
2. (Optional) Add a description
3. Select priority level
4. Click **Add Task**

### Managing Tasks

- **Complete a Task**: Click the checkbox next to the task
- **Edit a Task**: Click the edit icon (pencil) to modify
- **Delete a Task**: Click the delete icon (trash)

### Filtering & Sorting

- Use the filter buttons to view All, Pending, or Completed tasks
- Use the sort dropdown to organize tasks by:
  - Newest First
  - Oldest First
  - Priority (High → Low)
  - Title (A-Z)

## Customization

### Change Color Scheme

Edit `style.css` and modify the CSS variables:

```css
:root {
    --primary-color: #667eea;
    --primary-dark: #5568d3;
    --secondary-color: #764ba2;
    /* ... other colors */
}
```

### Modify Timezone

Edit `config.php`:

```php
date_default_timezone_set('America/New_York'); // Change to your timezone
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Security Features

- Prepared SQL statements to prevent SQL injection
- Input validation and sanitization
- CSRF protection through POST requests
- XSS prevention with `htmlspecialchars()`
- Protected config files via `.htaccess`

## Troubleshooting

### Database Connection Error

- Verify database credentials in `config.php`
- Ensure the database exists and user has proper privileges
- Check if MySQL service is running

### Blank Page

- Check PHP error logs in cPanel
- Ensure PHP version is 7.4 or higher
- Verify all files were uploaded correctly

### 500 Internal Server Error

- Check `.htaccess` file syntax
- Review error logs in cPanel
- Ensure proper file permissions

### Tasks Not Appearing

- Verify database table was created correctly
- Check browser console for JavaScript errors
- Ensure `api.php` is accessible

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache with mod_rewrite (for .htaccess)
- Modern web browser with JavaScript enabled

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review cPanel error logs
3. Verify all installation steps were followed

## License

This project is free to use for personal and commercial purposes.

## Credits

- Icons: Font Awesome 6.4.0
- Fonts: System fonts for optimal performance

---

**Enjoy managing your tasks efficiently! 🚀**
