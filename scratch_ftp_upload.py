#!/usr/bin/env python3
"""Upload corrected files to InfinityFree via FTP."""
import ftplib
import os
import io

FTP_HOST = 'ftpupload.net'
FTP_PORT = 21
FTP_USER = 'if0_42636781'
FTP_PASS = '25B91A6262'
LOCAL_ROOT = '/Applications/XAMPP/xamppfiles/htdocs/department-website'
REMOTE_ROOT = '/htdocs'

# Files/dirs to skip
SKIP_NAMES = {
    '.git', '.DS_Store', '.agents', 'department-website',
    'cache', 'logs', 'uploads', 'scratch_ftp_upload.py',
}

# Large files to skip (videos > 10MB)
SKIP_EXTENSIONS = {'.mp4', '.avi', '.mov', '.mkv', '.wmv'}

def should_skip(name, path):
    if name in SKIP_NAMES:
        return True
    if name.startswith('.git'):
        return True
    _, ext = os.path.splitext(name)
    if ext.lower() in SKIP_EXTENSIONS:
        return True
    # Skip files larger than 10MB
    if os.path.isfile(path) and os.path.getsize(path) > 10 * 1024 * 1024:
        return True
    return False

def upload_file(ftp, local_path, remote_path):
    """Upload a single file."""
    try:
        with open(local_path, 'rb') as f:
            ftp.storbinary(f'STOR {remote_path}', f)
        print(f'  ✓ {remote_path}')
        return True
    except Exception as e:
        print(f'  ✗ {remote_path}: {e}')
        return False

def ensure_remote_dir(ftp, remote_dir):
    """Create remote directory if it doesn't exist."""
    try:
        ftp.cwd(remote_dir)
        ftp.cwd('/')  # go back to root
    except:
        try:
            ftp.mkd(remote_dir)
            print(f'  📁 Created directory: {remote_dir}')
        except:
            pass  # might already exist or be a nested path

def upload_directory(ftp, local_dir, remote_dir):
    """Recursively upload a directory."""
    # Ensure remote dir exists
    ensure_remote_dir(ftp, remote_dir)
    
    entries = sorted(os.listdir(local_dir))
    uploaded = 0
    skipped = 0
    
    for name in entries:
        local_path = os.path.join(local_dir, name)
        remote_path = f'{remote_dir}/{name}'
        
        if should_skip(name, local_path):
            skipped += 1
            continue
        
        if os.path.isdir(local_path):
            ensure_remote_dir(ftp, remote_path)
            sub_up, sub_sk = upload_directory(ftp, local_path, remote_path)
            uploaded += sub_up
            skipped += sub_sk
        else:
            if upload_file(ftp, local_path, remote_path):
                uploaded += 1
            else:
                skipped += 1
    
    return uploaded, skipped

def main():
    print(f'Connecting to {FTP_HOST}...')
    ftp = ftplib.FTP()
    ftp.connect(FTP_HOST, FTP_PORT, timeout=60)
    ftp.login(FTP_USER, FTP_PASS)
    print(f'Connected! Server: {ftp.getwelcome()}')
    
    # Set passive mode
    ftp.set_pasv(True)
    
    # First, upload the two critical fixed files
    print('\n=== Uploading critical fixes ===')
    
    # 1. Upload corrected index.php (replacing XAMPP default)
    upload_file(ftp, f'{LOCAL_ROOT}/index.php', f'{REMOTE_ROOT}/index.php')
    
    # 2. Upload corrected .htaccess
    upload_file(ftp, f'{LOCAL_ROOT}/.htaccess', f'{REMOTE_ROOT}/.htaccess')
    
    # 3. Upload all other files
    print('\n=== Uploading all project files ===')
    uploaded, skipped = upload_directory(ftp, LOCAL_ROOT, REMOTE_ROOT)
    
    print(f'\n=== DONE ===')
    print(f'Uploaded: {uploaded} files')
    print(f'Skipped: {skipped} files/dirs')
    
    # Verify index.php was replaced
    print('\n=== Verifying index.php ===')
    buf = io.BytesIO()
    ftp.retrbinary(f'RETR {REMOTE_ROOT}/index.php', buf.write)
    content = buf.getvalue().decode('utf-8', errors='replace')
    if 'XAMPP' in content or '/dashboard/' in content:
        print('⚠️  WARNING: index.php still has XAMPP content!')
    else:
        print('✓ index.php is the correct department website homepage')
    
    print(f'\nFirst 200 chars: {content[:200]}')
    
    ftp.quit()
    print('\nFTP connection closed.')

if __name__ == '__main__':
    main()
