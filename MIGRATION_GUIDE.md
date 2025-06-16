
# Multi-Branch Migration Guide

## Overview
This guide provides step-by-step instructions for migrating from a single-branch to a multi-branch architecture.

## Prerequisites
- Full database backup
- Test environment setup
- Maintenance mode enabled

## Migration Steps

### Step 1: Backup Existing Data
```bash
# Create database backup
php artisan migrate:multi-branch --backup

# Export database (alternative method)
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Step 2: Run Core Migrations
```bash
# Run the branch-related migrations
php artisan migrate

# Ensure all tables have branch_id columns
php artisan migrate:status
```

### Step 3: Assign Default Branch
```bash
# Create default branch and assign existing data
php artisan migrate:multi-branch --assign-default

# Verify data assignment
php artisan db:seed --class=MultibranchMigrationSeeder
```

### Step 4: Test the System
```bash
# Run comprehensive tests
php artisan test:multi-branch

# Test specific branch
php artisan test:multi-branch --branch=1
```

### Step 5: Setup Additional Branches
```bash
# Create new branch with admin
php artisan branch:setup "North Campus" --admin-email=admin@north.school.edu

# Copy structure from existing branch
php artisan branch:setup "South Campus" --copy-structure=1 --admin-email=admin@south.school.edu
```

## Verification Checklist

### Data Integrity
- [ ] All records have valid branch_id
- [ ] No orphaned records
- [ ] Foreign key constraints intact
- [ ] Backup tables created successfully

### Functionality Testing
- [ ] Branch switching works
- [ ] Data filtering by branch
- [ ] Role-based access control
- [ ] Branch-specific settings
- [ ] User authentication per branch

### Performance Testing
- [ ] Query performance with branch filtering
- [ ] Dashboard loading times
- [ ] Report generation speed
- [ ] Concurrent branch access

## Rollback Plan

### If Issues Occur
1. **Stop all operations immediately**
2. **Restore from backup:**
   ```bash
   mysql -u username -p database_name < backup_file.sql
   ```
3. **Revert migrations:**
   ```bash
   php artisan migrate:rollback --step=5
   ```
4. **Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

## Post-Migration Tasks

### 1. Update Application Settings
- Configure branch-specific themes
- Set up branch-specific email settings
- Configure branch logos and branding

### 2. User Management
- Assign users to appropriate branches
- Set up branch administrators
- Configure role permissions

### 3. Data Cleanup
- Remove temporary migration tables
- Optimize database tables
- Update search indexes

### 4. Documentation
- Update user manuals
- Train staff on new features
- Document branch-specific procedures

## Monitoring and Maintenance

### Daily Checks
- Verify branch data isolation
- Monitor query performance
- Check error logs for branch-related issues

### Weekly Reviews
- Review branch usage statistics
- Check data consistency across branches
- Monitor system performance metrics

## Troubleshooting

### Common Issues

#### Branch ID Missing
```sql
-- Find records without branch_id
SELECT COUNT(*) FROM table_name WHERE branch_id IS NULL;

-- Fix missing branch_ids
UPDATE table_name SET branch_id = 1 WHERE branch_id IS NULL;
```

#### Performance Issues
```bash
# Add database indexes for branch_id columns
php artisan db:seed --class=BranchIndexSeeder

# Optimize queries
php artisan optimize:clear
```

#### Access Control Problems
```bash
# Refresh role permissions
php artisan cache:forget spatie.permission.cache
```

## Support Contacts
- System Administrator: admin@school.edu
- Technical Support: support@school.edu
- Emergency Contact: +1234567890
