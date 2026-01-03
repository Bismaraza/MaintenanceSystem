# Maintenance Request System

**Course:** CS 353 - Database System  
**Submitted By:** Bisma Raza (BSE-F23_M47)  
**Submitted To:** Mr. Asif Raza  
**Department:** Software Engineering, University of Mianwali  
**Submission Date:** 27th Dec, 2025  

---

## Project Overview

The **Maintenance Request System** is a web-based application designed to efficiently manage and track maintenance requests within an organization, building, or institution. It allows users to:

- Submit maintenance requests.
- Assign tasks to staff members.
- Monitor progress of requests.
- Generate detailed reports.

The system uses a **MySQL database** backend with a **PHP frontend**, providing a responsive and user-friendly interface.

---

## Features / Modules

1. **Location Management**  
   - Add, update, view, and delete locations.
2. **Staff Management**  
   - Manage maintenance staff details and roles.
3. **Issue Type Management**  
   - Define and categorize maintenance issues.
4. **Request Management**  
   - Submit, update, and track maintenance requests.
5. **Request Assignment**  
   - Assign staff to requests and monitor status.
6. **Report Management**  
   - Generate detailed reports of maintenance activity.

---

## Technology Stack

| Component | Technology |
|-----------|------------|
| Frontend  | HTML, CSS |
| Backend   | PHP 8+ |
| Database  | MySQL |
| Server    | XAMPP (Apache & MySQL) |

---

## Database Tables

- **Users** – Stores requesters and admin accounts.  
- **MaintenanceStaff** – Stores staff info, roles, and contact.  
- **Location** – Stores buildings and rooms.  
- **IssueType** – Stores types of maintenance issues.  
- **MaintenanceRequest** – Central table for requests.  
- **RequestAssignment** – Tracks assignments of staff to requests.  
- **MaintenanceLogs** – Logs maintenance activities.  

---

## Screenshots

**Home Dashboard**  
![Dashboard Screenshot](https://github.com/Bismaraza/MaintenanceSystem/blob/03da1371dab5f9a13be3f5db8435dd97167f7dca/Project%20Milestones/Dashboard.png))

**Manage Staff Module**  
![Staff Screenshot](path-to-your-image/staff.png)

**Manage Locations Module**  
![Locations Screenshot](path-to-your-image/locations.png)

**Issue Type Module**  
![Issue Type Screenshot](path-to-your-image/issuetype.png)

**Request Management Module**  
![Request Screenshot](path-to-your-image/request.png)

**Reports Module**  
![Reports Screenshot](path-to-your-image/reports.png)

> Replace `path-to-your-image/filename.png` with the folder path where your screenshots are stored in the repo.

---

## Installation / Setup

1. Clone the repository:
```bash
git clone https://github.com/Bismaraza/Maintenance-Request-System.git
