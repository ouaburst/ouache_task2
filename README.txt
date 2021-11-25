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
- Admin page must be password protected
- Handles login/logout
- Password muste MD5 encrypted in the database

[Tasks]

Administrate registrations:
- Database table: events_reg
- List all the registrations in a table after login
- Confirm a registration for a user. When confirmed the user will receive an email
- Delete a user registration
		
Administrate an event:
- Database table: events
- List all the events in a table after login
- Add an event
- Edit an event
- Delete an event
- Close an event for registration
- Open an event for registration


<Role Users>

[Tasks]

- A user can register to an event via the portal. The administrator will get a notification via email.
- For information about a user see database table events_reg
- Open for the public, no password

Database tables
---------------
admin: Contains information about user admin. File: admin.sql 
events_reg: Contains information about registrations. File: events_reg.sql
events: Contains information about events. File events.sql

