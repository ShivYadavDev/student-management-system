STUDENT MANAGEMENT SYSTEM - PHP + MYSQL

REQUIREMENTS
- XAMPP with Apache, MySQL and PHP
- VS Code or another text editor
- Web browser

INSTALLATION
1. Extract the student_management folder into C:\xampp\htdocs\
2. Open XAMPP Control Panel.
3. Start Apache and MySQL.
4. Open http://localhost/phpmyadmin/
5. Select the Import tab.
6. Choose database.sql from this project folder and click Import.
   Alternative: open the SQL tab, paste database.sql contents and run it.
7. Open http://localhost/student_management/

DATABASE SETTINGS
The default XAMPP settings are in config/database.php:
host = localhost
database = student_db
username = root
password = empty

If you set a MySQL password, update config/database.php.

FEATURES
- Dashboard and total student count
- Add student
- List students
- Search by name, roll number or address
- Edit student
- Delete student (POST request with CSRF token)
- Input validation
- PDO prepared statements
- Output escaping against HTML injection

IMPORTANT
This is a learning project for local practice. Before real deployment:
- Add administrator authentication and authorization.
- Use HTTPS.
- Configure strong database credentials.
- Restrict database privileges.
- Add backups, audit logs and appropriate privacy controls.
- Review the code and security with a qualified developer.
