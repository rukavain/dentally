# Logging function
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}
# Get database credentials from .env file
DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
# Backup database
log_message "Starting database backup..."
mysqldump -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$DB_BACKUP_DIR/dental_db_$TIMESTAMP.sql"
if [ $? -eq 0 ]; then
    log_message "Database backup completed successfully"
    # Compress database backup
    gzip "$DB_BACKUP_DIR/dental_db_$TIMESTAMP.sql"
    log_message "Database backup compressed"
else
    log_message "Error: Database backup failed"
    exit 1
fi
# Backup important files and directories
log_message "Starting files backup..."
DIRS_TO_BACKUP=(
    "app"
    "config"
    "database/migrations"
    "public/assets"
    "resources/views"
    "storage/app/public"
)
# Create tar archive of specified directories
tar -czf "$FILES_BACKUP_DIR/files_$TIMESTAMP.tar.gz" "${DIRS_TO_BACKUP[@]}" 2>/dev/null
if [ $? -eq 0 ]; then
    log_message "Files backup completed successfully"
else
    log_message "Error: Files backup failed"
    exit 1
fi
# Clean up old backups
log_message "Cleaning up old backups..."
find "$DB_BACKUP_DIR" -name "dental_db_*.sql.gz" -mtime +$DAYS_TO_KEEP -delete
find "$FILES_BACKUP_DIR" -name "files_*.tar.gz" -mtime +$DAYS_TO_KEEP -delete
# Create backup report
TOTAL_SIZE=$(du -sh "$BACKUP_DIR" | cut -f1)
DB_COUNT=$(find "$DB_BACKUP_DIR" -name "*.sql.gz" | wc -l)
FILES_COUNT=$(find "$FILES_BACKUP_DIR" -name "*.tar.gz" | wc -l)
log_message "Backup Summary:"
log_message "- Total backup size: $TOTAL_SIZE"
log_message "- Database backups: $DB_COUNT"
log_message "- File backups: $FILES_COUNT"
log_message "- Retention period: $DAYS_TO_KEEP days"
log_message "Backup process completed!"
