Task2 overview
--------------
A portal for registration to an event.

Architecture
------------
Development languages: PHP, Ajax, Bootstrap twitter.
Database: Mysql

Roles
-----

<Role Admin>

[Tasks]

Administrate registrations:
- Database table: events_reg
- List all the registrations in a table after login
- Confirm a registration for a user
- When confirmed the user will receive an email
- Delete a user registration
- Related file: admin.php
		
Administrate an event:
- Database table: events
- List all the events in a table after login
- Add an event
- Edit an event
- Delete an event
- Close an event for registration
- Open an event for registration
- Related file: admin_events.php


<Role Users>

[Tasks]

- Register to an event via portal. The administrator will get a notification via email.
- For information about a user see database table events_reg
- Related file: registration.php
- Open for the public

Database tables
---------------
admin: Contains information about user admin. File: admin.sql 
events_reg: Contains information about registrations. File: events_reg.sql
events: Contains information about events

Files
-----
<PHP-files>
admin.php: Users administration.
admin_events.php: Event adminisration.
registrations.php: Portal for registration
ajax.php: Handle Ajax calls
index.php: Home page rediction
login.php: Handles admin login
logout.php: Handles admin logout
menu.php: Top meny in admin page

<SQL-files>
admin.sql: SQL dump for admin table
events_reg.sql: SQL dump for users who registered to the events
events.sql: SQL dump for events table

