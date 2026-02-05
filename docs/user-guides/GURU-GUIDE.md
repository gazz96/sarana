# Guru (Teacher) User Guide - SARANAS System

## Overview
The SARANAS (Sarana Prasarana) system helps you report maintenance issues and facility problems quickly and easily. As a **Guru**, you can report problems with school facilities, equipment, and inventory items.

## 🔐 Login & Dashboard

### Accessing the System
1. **URL**: `https://sarana.sekolah.com/login`
2. **Default Test Credentials**:
   - Email: `guru@test.com`
   - Password: `password123`
3. **Click**: "Login" button

### Your Dashboard
After login, you'll see:
- **My Problems**: Problems you've reported
- **Pending Issues**: Problems awaiting action
- **Recent Activities**: Latest updates on your reports

## 📝 Reporting Problems

### Create New Problem Report
1. **Navigate to**: Problems → **Create Problem**
2. **Fill in the details**:
   - **Issue Description**: Clearly describe the problem
     * Example: "AC di ruang kelas 4A tidak dingin"
     * Example: "Meja belajar patah di laboratorium komputer"
   - **Location**: Select where the problem is
   - **Add Items**: Select affected items from inventory

### Add Problem Items
1. After creating the basic problem, click **"Add Items"**
2. For each damaged/faulty item:
   - **Select Item**: Choose from inventory dropdown
   - **Quantity**: How many items are affected
   - **Estimated Cost**: Approximate repair cost (if known)
   - **Description**: Specific damage details
   - **Photos**: Upload photos of damage (optional)
3. Click **"Add Item"**
4. Repeat for additional items
5. Click **"Save Draft"** or **"Submit"**

### Save vs Submit
- **Save Draft**: Save your work to continue later (Status: DRAFT)
- **Submit**: Send problem for review (Status: DIAJUKAN)

### Edit Draft Problems
1. Go to **My Problems**
2. Find draft problem (Status: DRAFT)
3. Click **Edit**
4. Make changes
5. Save or Submit

## 📋 View Your Problems

### Problem List
1. Navigate to **Problems** → **My Problems**
2. See all your reported problems with:
   - **Problem Code**: PRB-XXX
   - **Issue**: Brief description
   - **Status**: Current workflow status
   - **Date**: When reported
   - **Actions**: View details, edit (if draft)

### Problem Status Explained
- **DRAFT (0)**: Still being prepared
- **DIAJUKAN (1)**: Submitted, waiting review
- **PROSES (2)**: Technician is working on it
- **SELESAI DIKERJAKAN (3)**: Repair completed
- **DIBATALKAN (4)**: Request cancelled
- **MENUNGGU PERSETUJUAN (5-7)**: Awaiting management/admin/finance approval

### View Problem Details
1. Click **View** on any problem
2. See complete information:
   - Full issue description
   - All affected items with costs
   - Current status and stage
   - Assigned technician (if any)
   - Notes from technicians/admin
   - Photo evidence (if uploaded)

## 🔔 Track Progress

### Monitor Your Problems
1. Check **My Problems** regularly
2. Look for status changes
3. Read notes/updates from technicians

### Notification Types
You'll receive notifications for:
- **Status Changes**: When your problem moves to next stage
- **Technician Assignment**: When someone is assigned
- **Completion**: When work is finished
- **Cancellation**: If request is cancelled

### Check Notifications
1. Click bell icon (🔔) in top-right corner
2. See all your notifications
3. Click to view problem details

## ✅ After Repair Completion

### Verify Completed Work
1. When status changes to "SELESAI DIKERJAKAN"
2. Check the problem details
3. Verify work is completed satisfactorily
4. If issues remain, contact admin/technician

### Provide Feedback
1. After completion, you can add notes
2. Rate the repair quality (if enabled)
3. Report any remaining issues

## 🔍 Search & Filter

### Find Problems
1. Use **Search** box:
   - Search by problem code
   - Search by issue description
   - Search by location

### Filter Problems
1. Use **Filter** dropdown:
   - Filter by status (All, Draft, Submitted, In Progress, Completed)
   - Filter by date range
   - Filter by location

## 📱 Tips for Effective Reporting

### Good Problem Reports
✅ **DO**:
- Be specific about the issue
- Include exact location
- Upload photos if possible
- Provide realistic cost estimates
- Report problems promptly

❌ **DON'T**:
- Use vague descriptions like "something wrong"
- Forget to specify location
- Ignore minor issues that become major
- Submit incomplete reports

### Example Good Report
**Issue**: "AC di kelas 4A tidak menghasilkan udara dingin. Lampu indikator menyala tapi tidak ada suara kipas berputar. Lokasi di dekat jendela sebelah kanan."

**Items Affected**:
- AC Daikin 1PK (BRG-045): Quantity 1, Est. Cost Rp 500.000

### Example Poor Report  
**Issue**: "AC rusak" 
*(Too vague - missing location, specific problem, AC details)*

## 🔧 Common Issues & Solutions

### Can't Submit Problem
**Issue**: Submit button not working  
**Solution**: Make sure you've added at least one item and filled all required fields

### Can't Find Item in Inventory
**Issue**: Item not listed in dropdown  
**Solution**: Contact admin to add the item to inventory first

### Problem Status Not Changing
**Issue**: Problem stuck in same status for days  
**Solution**: Contact admin or technician for follow-up

### Upload Photos Not Working
**Issue**: Can't upload photo evidence  
**Solution**: Check file size (max 2MB), ensure format is JPG/PNG

## 📊 My Statistics

### View Your Report Stats
1. Navigate to **My Statistics**
2. See:
   - Total problems reported
   - Problems completed
   - Problems in progress
   - Average completion time
   - Most common issues you report

## 🆘 Getting Help

### Contact Information
- **Admin**: [Contact Details]
- **Technical Support**: [Contact Details]
- **Emergency Issues**: Use hotline: [Phone Number]

### When to Contact Admin
- Can't find needed items in inventory
- Problem stuck in same status for >3 days
- Need to cancel submitted problem
- Technical issues with system

## ⏰ Daily Usage Tips

### Best Practices
1. **Check Notifications**: Start of day
2. **Monitor Active Problems**: After lunch
3. **Report New Issues**: As soon as discovered
4. **Verify Completed Work**: When notified

### Weekly Routine
- Review all your open problems
- Follow up on pending issues
- Update any draft reports
- Provide feedback on completed work

---

## 🎯 Quick Reference Card

### Problem Status Flow
```
DRAFT → DIAJUKAN → PROSES → SELESAI DIKERJAKAN
                  ↓
            (Approval Stages 5-7)
```

### Keyboard Shortcuts
- `Ctrl + N`: New problem
- `Ctrl + M`: My problems
- `Ctrl + S`: Save draft
- `Ctrl + Enter`: Submit problem

### Important Codes
- **Problem Codes**: PRB-001, PRB-002, etc.
- **Item Codes**: BRG-001, BRG-002, etc.
- **Status 0**: Draft
- **Status 3**: Completed

---

**Last Updated**: 2026-02-05  
**System Version**: 1.0.0  
**Role**: Guru (Problem Reporter)