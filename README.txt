Task2 overview
--------------
A portal for registration to an event.

Architecture
------------
Development languages: PHP, Ajax, Bootstrap twitter.
Database: Mysql

Roles
-----

Role Admin
- Database table: admin

[Tasks]

Administrate registrations:
- Database table: events_reg
- Confirm a registration for a user.
- When confirmed the user will receive an email.
- Delete a user registration.
		

Administrate an event:
- Database table: events
- Add an event
- Edit an event
- Delete an event
- Close an event for registration
- Open an event for registration

Role Users

- Register to an event. Administrator will get a notification via email.
- For information about a user see database table events_reg

Database tables
---------------
admin: Contains information about user admin. File: admin.sql 
events_reg: Contains information about registrations. File: events_reg.sql
events: Contains information about events

Files
-----
File name
Description
admin_events.php
Handles admin tasks. 
Password protected.
register.php
Handles registrations.
Open for the public
ajax.php
Handles ajax calls from admin.php and register.php
include/DBhandler.php
Handles database queries.
index.php
Redirect to register.php

