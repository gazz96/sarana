# Admin User Guide - SARANAS System

## Overview
The SARANAS (Sarana Prasarana) system helps schools manage inventory, maintenance requests, and problem reporting. As an **Admin**, you have full access to all system features and are responsible for overseeing the entire workflow.

## 🔐 Admin Login & Dashboard

### Accessing the System
1. **URL**: `https://sarana.sekolah.com/login`
2. **Default Test Credentials**:
   - Email: `admin@test.com`
   - Password: `password123`
3. **Click**: "Login" button

### Dashboard Overview
After login, you'll see:
- **Total Problems**: All maintenance requests
- **Pending Approvals**: Items awaiting your approval
- **Active Issues**: Currently ongoing problems
- **Recent Activities**: Latest system actions

## 👥 User Management

### View All Users
1. Navigate to **Users** → **All Users**
2. See list of all users with their roles
3. Filter by role using the dropdown

### Create New User
1. Go to **Users** → **Create User**
2. Fill in user details:
   - **Name**: Full name
   - **Email**: Unique email address
   - **Role**: Select from dropdown (Guru, Teknisi, Keuangan, Lembaga, Admin)
   - **Password**: Minimum 8 characters
3. Click **Save**

### Edit User
1. Go to **Users** → **All Users**
2. Click **Edit** next to user
3. Update information
4. Click **Update**

### Change User Role
1. Find user in **All Users**
2. Click **Edit**
3. Change **Role** dropdown
4. Click **Update**

## 📦 Inventory Management

### View All Items
1. Navigate to **Inventory** → **All Items**
2. See complete inventory list with:
   - Item codes (BRG-001, BRG-002, etc.)
   - Names and quantities
   - Current locations
   - Status (AKTIF/TIDAK AKTIF)

### Add New Item
1. Go to **Inventory** → **Create Item**
2. Fill in item details:
   - **Name**: Item name
   - **Code**: Auto-generated (BRG-XXX)
   - **Location**: Select from dropdown
   - **Quantity**: Number of items
   - **Status**: AKTIF or TIDAK AKTIF
   - **Description**: Optional details
3. Click **Save**

### Edit Item
1. Find item in inventory list
2. Click **Edit**
3. Update information
4. Click **Update**

### Manage Locations
1. Go to **Inventory** → **Locations**
2. See all locations (buildings, floors)
3. Add new locations for better organization

## 🔧 Problem Management

### View All Problems
1. Navigate to **Problems** → **All Problems**
2. Filter by status:
   - **DRAFT** (0): Initial reports
   - **DIAJUKAN** (1): Submitted for review
   - **PROSES** (2): Being handled
   - **SELESAI DIKERJAKAN** (3): Work completed
   - **DIBATALKAN** (4): Cancelled requests
   - **MENUNGGU PERSETUJUAN MANAGEMENT** (5): Pending management approval
   - **MENUNGGU PERSETUJUAN ADMIN** (6): Pending your approval
   - **MENUNGGU PERSETUJUAN KEUANGAN** (7): Pending finance approval

### Approve Problems
As Admin, you can approve problems in status "6" (MENUNGGU PERSETUJUAN ADMIN):
1. Find problem with status "6"
2. Click **View Details**
3. Review the problem and items
4. Click **Approve** to move to next stage
5. Or click **Reject** if there are issues

### Assign Technicians
1. View problem in "PROSES" status
2. Click **Assign Technician**
3. Select technician from dropdown
4. Click **Save**

### Monitor Progress
1. Check dashboard for statistics
2. View problem details for updates
3. Monitor technician activities
4. Review completed problems

## 📊 Reports & Analytics

### View Statistics
1. Navigate to **Reports** → **Statistics**
2. See charts showing:
   - Problem trends over time
   - Status distribution
   - Most common issues
   - Technician performance

### Export Data
1. Go to **Reports** → **Export**
2. Select data type (Problems, Inventory, Users)
3. Choose date range
4. Click **Generate Report**

## 🔔 Notifications

### Check Notifications
1. Click bell icon (🔔) in top-right corner
2. See all notifications
3. Click on notification to view details

### Notification Types
- **New Problems**: When users submit issues
- **Approval Requests**: When your approval is needed
- **Status Changes**: When problems move to next stage
- **System Alerts**: Important system updates

## ⚙️ System Settings

### Configure System
1. Navigate to **Settings** → **System Configuration**
2. Update settings:
   - School information
   - Email settings
   - Notification preferences
   - Backup schedules

### Monitor Performance
1. Go to **Settings** → **System Status**
2. Check:
   - Database status
   - Server performance
   - Storage usage
   - Active users

## 🔒 Security Best Practices

### Admin Security
- **Change Password**: Every 90 days
- **Use Strong Passwords**: Mix of letters, numbers, symbols
- **Enable 2FA**: If available
- **Monitor Logs**: Check user activity regularly
- **Backup Data**: Ensure daily backups are running

### User Security
- **Force Password Resets**: For new users
- **Remove Inactive Users**: Archive accounts not used in 6+ months
- **Review Permissions**: Regular audit of user roles

## 📞 Troubleshooting

### Common Issues

**Problem**: User can't login
- **Solution**: Check password, reset if needed

**Problem**: Can't approve request
- **Solution**: Verify you have Admin role, check problem status

**Problem**: System running slow
- **Solution**: Clear cache, check server status

**Problem**: Missing notifications
- **Solution**: Check notification settings, verify email configuration

## 📱 Quick Reference

### Keyboard Shortcuts
- `Ctrl + K`: Quick search
- `Ctrl + N`: New problem
- `Ctrl + I`: Inventory list
- `Ctrl + U`: User list

### Important Status Codes
- `0`: DRAFT
- `1`: DIAJUKAN  
- `2`: PROSES
- `3`: SELESAI DIKERJAKAN
- `4`: DIBATALKAN
- `5`: MENUNGGU PERSETUJUAN MANAGEMENT
- `6`: MENUNGGU PERSETUJUAN ADMIN
- `7`: MENUNGGU PERSETUJUAN KEUANGAN

### Emergency Contacts
- **System Administrator**: [Contact Info]
- **Technical Support**: [Contact Info]
- **Backup Admin**: [Contact Info]

---

## 🎯 Admin Daily Checklist

- [ ] Check dashboard for pending approvals
- [ ] Review new problems submitted
- [ ] Approve/reject pending requests
- [ ] Monitor system performance
- [ ] Check backup status
- [ ] Review user activity logs
- [ ] Respond to user inquiries

**Last Updated**: 2026-02-05  
**System Version**: 1.0.0  
**Role**: Administrator (Full Access)