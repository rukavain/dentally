#!/bin/bash
# Configuration
BACKUP_DIR="/Users/johndalemanaloto/htdocs/dental/storage/backups"
DB_BACKUP_DIR="$BACKUP_DIR/database"
FILES_BACKUP_DIR="$BACKUP_DIR/files"
LOG_FILE="$BACKUP_DIR/recovery_log.txt"
# Logging function
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}
# Get database credentials from .env file
DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
# Function to list available backups
list_backups() {
    echo "Available database backups:"
    ls -lt "$DB_BACKUP_DIR" | grep ".sql.gz" | awk '{print NR".", $9}'
    echo -e "\nAvailable file backups:"
    ls -lt "$FILES_BACKUP_DIR" | grep ".tar.gz" | awk '{print NR".", $9}'
}
# Function to restore database
restore_database() {
    local backup_file="$1"

    if [ ! -f "$backup_file" ]; then
        log_message "Error: Database backup file not found: $backup_file"
        exit 1
    }
    log_message "Starting database restoration from: $backup_file"

    # Create temporary uncompressed file
    gunzip -c "$backup_file" > "${backup_file%.gz}"

    # Restore database
    mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < "${backup_file%.gz}"

    if [ $? -eq 0 ]; then
        log_message "Database restored successfully"
        rm "${backup_file%.gz}" # Clean up temporary file
    else
        log_message "Error: Database restoration failed"
        rm "${backup_file%.gz}" # Clean up temporary file
        exit 1
    fi
}
# Function to restore files
restore_files() {
    local backup_file="$1"
    local restore_dir="$2"

    if [ ! -f "$backup_file" ]; then
        log_message "Error: Files backup not found: $backup_file"
        exit 1
    }
    log_message "Starting files restoration from: $backup_file"

    # Create temporary restoration directory
    local temp_dir="/tmp/dental_restore_${RANDOM}"
    mkdir -p "$temp_dir"

    # Extract files to temporary directory
    tar -xzf "$backup_file" -C "$temp_dir"

    if [ $? -eq 0 ]; then
        # Sync files to target directory
        rsync -av --delete "$temp_dir/" "$restore_dir/"
        log_message "Files restored successfully to: $restore_dir"
    else
        log_message "Error: Files restoration failed"
        rm -rf "$temp_dir"
        exit 1
    fi

    # Clean up
    rm -rf "$temp_dir"
}
# Main recovery process
echo "Dental Management System Recovery Tool"
echo "====================================="
list_backups
# Get user input for which backups to restore
read -p "Enter the number of the database backup to restore (or press Enter to skip): " db_choice
read -p "Enter the number of the files backup to restore (or press Enter to skip): " files_choice
# Process database restoration
if [ ! -z "$db_choice" ]; then
    db_file=$(ls -t "$DB_BACKUP_DIR"/*.sql.gz | sed -n "${db_choice}p")
    if [ ! -z "$db_file" ]; then
        restore_database "$db_file"
    else
        log_message "Invalid database backup selection"
    fi
fi
# Process files restoration
if [ ! -z "$files_choice" ]; then
    files_backup=$(ls -t "$FILES_BACKUP_DIR"/*.tar.gz | sed -n "${files_choice}p")
    if [ ! -z "$files_backup" ]; then
        read -p "Enter restoration directory path: " restore_dir
        if [ ! -d "$restore_dir" ]; then
            mkdir -p "$restore_dir"
        fi
        restore_files "$files_backup" "$restore_dir"
    else
        log_message "Invalid files backup selection"
    fi
fi
log_message "Recovery process completed!"
