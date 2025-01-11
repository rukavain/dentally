<h1>Dentally: A Patient Record Tracking System</h1>
<br>
## 
<strong>Front-end was designed with Tailwind CSS</strong>
<br>
<strong>Back-end was made with Laravel 10</strong>
<h1>Features: </h1>
<p>
    # User Roles and Permissions

## Administrator
The Administrator role has full control over the system. The permissions include:

- **Dashboard**: View dashboard
- **Dentists**:
  - Add, view, edit dentist
- **Staff**:
  - Add, view, edit staff
- **Patients**:
  - Add, view, edit, archive patient
  - Add, view, edit patient record
- **Schedule**:
  - Add, view, edit schedule
- **Appointments**:
  - View walk-in/online appointments
- **Procedures**:
  - Add, view, edit, delete procedures
- **Branches**:
  - Add, view, edit, delete branches
- **Inventory**:
  - Add, view, edit, delete inventory
- **Reports**:
  - View sales report
  - View audit logs
- **Account**:
  - Forgot password
  - Update password
  - Delete account

## Client
The Client role has limited access, focused on appointment and payment management:

- **Appointments**:
  - Add appointment
  - View appointment
  - View next visit
- **Payments**:
  - Add payment
  - View payment
- **Patient Record**:
  - View patient record information

## Staff
Staff members have access to patient management, appointments, and inventory:

- **Dashboard**: View dashboard
- **Patients**:
  - Add, view, edit, archive patient
- **Appointments**:
  - Add, view walk-in/online appointments
- **Inventory**:
  - Add, view, edit, delete inventory
- **Account**:
  - Forgot password
  - Update password
  - Delete account

## Dentist
The Dentist role focuses on appointment approval and patient payments:

- **Dashboard**: View dashboard
- **Appointments**:
  - Add, view walk-in appointments
  - Approve/decline online appointments
- **Payments**:
  - Add patient payments
- **Account**:
  - Forgot password
  - Update password
  - Delete account

</p>
