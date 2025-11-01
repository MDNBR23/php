# Med Tools Hub - Medical Platform

## Overview
Med Tools Hub is a Spanish-language medical web platform designed for pediatric and neonatal healthcare professionals. It provides a comprehensive system for user management, medical announcements, clinical guidelines, and medication information. The platform features a robust backend with user session management and data isolation. Its purpose is to centralize essential resources and tools for healthcare providers.

## Recent Changes (November 1, 2025)
**MAJOR MIGRATION: Node.js to PHP/MySQL + Email Recovery System**

The entire platform has been converted from Node.js/Express to PHP/MySQL for deployment on traditional web hosting, and a complete password recovery system via email has been implemented:

### Architecture Changes:
1. **Backend Migration**:
   - Converted all Node.js/Express routes to PHP files
   - Migrated from JSON file storage to MySQL database
   - Implemented PHP session management
   - Created RESTful PHP API endpoints

2. **Database Structure**:
   - Created MySQL schema with 5 main tables:
     - `users`: User authentication and profiles
     - `medications`: Vademecum drug database
     - `announcements`: Global medical announcements
     - `guides`: Clinical practice guidelines
     - `suggestions`: User feedback system
   - Includes sample data for immediate use

3. **File Structure**:
   - Created `php/` directory with 18 API endpoints
   - Converted all `.html` pages to `.php` with session validation
   - Updated `script.js` with PHP route mapping
   - Maintained all frontend functionality

4. **Password Recovery System**:
   - Email-based password reset with secure tokens
   - Token expiration (1 hour) and single-use validation
   - SMTP configuration support for email sending
   - Automatic cleanup of expired tokens
   - Professional HTML email templates

5. **Security Enhancements**:
   - PHP session-based authentication
   - Server-side route protection
   - Admin role verification for sensitive pages
   - Password hashing with PHP's password_hash()
   - Secure token generation with random_bytes()

### Files Created:
- **PHP API Files** (18):
  - `php/config.php` - Database configuration and helpers
  - `php/login.php` - User authentication
  - `php/register.php` - User registration
  - `php/logout.php` - Session termination
  - `php/check-session.php` - Session validation
  - `php/users.php` - User management (admin)
  - `php/medications.php` - Drug database CRUD
  - `php/announcements.php` - Announcements management
  - `php/guides.php` - Guidelines management
  - `php/suggestions.php` - User suggestions
  - `php/suggestions-count.php` - Pending suggestions count
  - `php/maintenance.php` - Maintenance mode status
  - `php/profile.php` - User profile management
  - `php/change-password.php` - Password updates
  - `php/reset-password-request.php` - Password recovery (generates token, sends email)
  - `php/reset-password.php` - Password reset (validates token, updates password)
  - `php/verify-reset-token.php` - Token validation
  - `php/email-config.php` - SMTP email configuration

- **PHP Pages** (9):
  - `index.php` - Login page
  - `register.php` - Registration
  - `reset-password.php` - Password recovery
  - `main.php` - Dashboard (protected)
  - `vademecum.php` - Medications (protected)
  - `herramientas.php` - Medical tools (protected)
  - `sugerencias.php` - Suggestions (protected)
  - `configuracion.php` - Settings (protected)
  - `admin.php` - Administration (admin only)

- **Database**:
  - `database.sql` - Complete MySQL schema with sample data (includes password_reset_tokens table)

- **Documentation**:
  - `INSTRUCCIONES_HOSTING.md` - Complete deployment guide (includes email setup)
  - `CONFIGURACION_EMAIL.md` - Comprehensive SMTP email configuration guide

### Migration Notes:
- **Email Functionality**: Fully implemented with SMTP support
  - Requires configuration of email credentials in `php/email-config.php`
  - Professional HTML email templates for password recovery
  - Complete setup guide in `CONFIGURACION_EMAIL.md`
  - Falls back to simple mail() function if SMTP fails
- **Maintained**: All core features, UI/UX, medical tools, and functionality
- **Database**: Changed from PostgreSQL to MySQL (more common in shared hosting)
- **Deployment**: Now compatible with traditional cPanel hosting

## Previous Changes (October 31, 2025)
1. **Enhanced Legal Footer:** Updated footer across all pages with comprehensive legal disclaimer
2. **Colombia Clock Flicker Fix:** Eliminated clock flickering when switching browser tabs
3. **Oxygenation Index Formula Correction:** Fixed critical medical calculation error
4. **Shift Calendar Timezone Fix:** Calendar now correctly highlights current day using Colombia timezone
5. **Drug Interaction Checker Tool:** Added new medical tool for checking medication interactions

## Previous Changes (October 31, 2025)
1. **Platform Rebranding:** Successfully migrated from "NBR WEB" to "Med Tools Hub"
2. **Colombia Clock Enhancement:** Fixed theme-aware colors
3. **Shift Management UX Improvement:** Removed payment type labels for cleaner interface

## Previous Changes (October 16, 2025)
1. **Corrector de Texto Mejorado:** Enhanced text correction tool with transformations
2. **Calculadora de Infusiones - Corrección Crítica:** Fixed medication source management
3. **Corrección de Errores:** Fixed JavaScript scope issues

## Previous Changes (October 14, 2025)
1. **Tools Section Enhancement:** Improved tool descriptions
2. **Arterial Blood Gas Analyzer:** Interactive visual feedback
3. **Infusion Calculator:** Formula corrections
4. **Notification Sound System:** Audio notifications
5. **Maintenance Mode:** Admin-controlled maintenance
6. **Interactive Shift Calendar:** Visual monthly calendar
7. **Shift Exchange System:** Track shift swaps
8. **Admin Menu Security:** Fixed visibility issues

## User Preferences
None recorded yet.

## System Architecture

### Current Architecture (PHP/MySQL)
Med Tools Hub now runs on a traditional LAMP stack for compatibility with shared hosting environments.

### UI/UX Decisions
- **Design:** Modern teal/cyan theme with enhanced contrast, clear typography, and accessible color palettes (WCAG AA compliant)
- **Navigation:** Smooth page transitions, collapsible sidebar with persistent icons
- **Responsiveness:** Optimized for mobile and desktop
- **Theming:** Supports light and dark modes

### Technical Implementation

#### Frontend
- **Languages:** HTML5, CSS3, Vanilla JavaScript
- **Features:** 
  - Pure JavaScript (no frameworks)
  - Theme persistence with localStorage
  - Interactive medical calculators
  - Real-time form validation

#### Backend
- **Language:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Authentication:** 
  - PHP session-based authentication
  - Password hashing with `password_hash()`/`password_verify()`
  - Role-based access control (admin/user)
  - Server-side route protection

#### Database Schema
- **users:** User accounts with authentication and profiles
- **medications:** Vademecum drug database (16 initial entries)
- **announcements:** Global medical announcements
- **guides:** Clinical practice guidelines
- **suggestions:** User feedback and admin responses
- **password_reset_tokens:** Secure tokens for password recovery (auto-cleanup of expired tokens)

#### API Structure
- RESTful PHP endpoints in `php/` directory
- JSON response format
- HTTP method-based routing (GET, POST, PUT, DELETE)
- Session validation middleware
- Admin authorization checks

### Feature Specifications
- **Authentication:** User registration, login, logout, session management
- **User Management (Admin):** Approve/reject registrations, edit profiles, manage status
- **Content Management:** Create/edit/delete announcements and clinical guides
- **Medication Database:** Shared database with search, editable by administrators
- **Profile Customization:** User profile updates with avatar upload
- **Suggestions System:** User feedback with admin responses
- **Medical Tools:** Text corrector, blood gas analyzer, infusion calculator, shift calendar
- **Admin Notifications:** Badges for pending actions
- **Maintenance Mode:** System-wide maintenance control

### System Design Choices
- **Data Storage:** MySQL database for reliability and hosting compatibility
- **API Design:** RESTful architecture with JSON responses
- **Session Management:** PHP native sessions with security settings
- **Deployment:** Compatible with traditional cPanel/shared hosting
- **Security:** 
  - Prepared statements to prevent SQL injection
  - Password hashing (bcrypt via PHP)
  - Session-based authentication
  - Server-side validation

## Deployment

### For Traditional Hosting (cPanel)
1. Create MySQL database and user in cPanel
2. Import `database.sql` via phpMyAdmin
3. Update database credentials in `php/config.php`
4. Upload all files via FTP or File Manager
5. Set correct file permissions (755 for directories, 644 for files)
6. Access via `https://yourdomain.com/index.php`

Refer to `INSTRUCCIONES_HOSTING.md` for complete deployment instructions.

### Initial Access
- **Username:** admin
- **Password:** 1234 (change immediately after first login)

## External Dependencies
- **PHP 7.4+:** Server-side scripting
- **MySQL 5.7+:** Database management
- **Apache/Nginx:** Web server with mod_rewrite
- **SSL/HTTPS:** Recommended for production

## Development Notes
- Original Node.js version preserved in git history
- Frontend JavaScript maintained compatibility
- All medical tools and calculations preserved
- Database includes sample data for testing
- Email functionality requires SMTP configuration in production
