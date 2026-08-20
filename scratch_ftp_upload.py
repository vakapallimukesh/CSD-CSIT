#!/usr/bin/env python3
"""Robust upload of files to InfinityFree via FTP with auto-reconnect."""
import ftplib
import os
import time

FTP_HOST = 'ftpupload.net'
FTP_PORT = 21
FTP_USER = 'if0_42636781'
FTP_PASS = '25B91A6262'
LOCAL_ROOT = '/Applications/XAMPP/xamppfiles/htdocs/department-website'
REMOTE_ROOT = '/htdocs'

SKIP_NAMES = {
    '.git', '.DS_Store', '.agents', 'department-website',
    'cache', 'logs', 'uploads', 'scratch_ftp_upload.py', 'node_modules'
}

SKIP_EXTENSIONS = {'.mp4', '.avi', '.mov', '.mkv', '.wmv'}

class RobustFTP:
    def __init__(self):
        self.ftp = None
        self.connect()

    def connect(self):
        if self.ftp:
            try:
                self.ftp.quit()
            except Exception:
                pass
        print(f'Connecting to {FTP_HOST}...')
        self.ftp = ftplib.FTP()
        self.ftp.connect(FTP_HOST, FTP_PORT, timeout=60)
        self.ftp.login(FTP_USER, FTP_PASS)
        self.ftp.set_pasv(True)
        print('Connected successfully!')

    def ensure_remote_dir(self, remote_dir):
        parts = [p for p in remote_dir.split('/') if p]
        curr = ''
        for p in parts:
            curr += '/' + p
            try:
                self.ftp.cwd(curr)
            except Exception:
                try:
                    self.ftp.mkd(curr)
                    print(f'  📁 Created directory: {curr}')
                except Exception:
                    pass

    def upload_file(self, local_path, remote_path, retries=3):
        for attempt in range(retries):
            try:
                # Ensure parent dir exists
                parent_dir = os.path.dirname(remote_path)
                self.ensure_remote_dir(parent_dir)
                
                with open(local_path, 'rb') as f:
                    self.ftp.storbinary(f'STOR {remote_path}', f)
                print(f'  ✓ {remote_path}')
                return True
            except Exception as e:
                print(f'  ⚠️ Upload error on {remote_path} (attempt {attempt+1}/{retries}): {e}')
                time.sleep(2)
                try:
                    self.connect()
                except Exception as ce:
                    print(f'  Failed reconnect: {ce}')
        print(f'  ✗ FAILED to upload {remote_path}')
        return False

def should_skip(name, path):
    if name in SKIP_NAMES:
        return True
    if name.startswith('.git'):
        return True
    _, ext = os.path.splitext(name)
    if ext.lower() in SKIP_EXTENSIONS:
        return True
    if os.path.isfile(path) and os.path.getsize(path) > 10 * 1024 * 1024:
        return True
    return False

def main():
    rftp = RobustFTP()
    
    all_files = []
    for root, dirs, files in os.walk(LOCAL_ROOT):
        # Filter dirs in-place to avoid traversing skipped folders
        dirs[:] = [d for d in dirs if not should_skip(d, os.path.join(root, d))]
        
        rel_path = os.path.relpath(root, LOCAL_ROOT)
        remote_dir = REMOTE_ROOT if rel_path == '.' else f'{REMOTE_ROOT}/{rel_path}'.replace('\\', '/')
        
        for f in files:
            local_file = os.path.join(root, f)
            if not should_skip(f, local_file):
                remote_file = f'{remote_dir}/{f}'
                all_files.append((local_file, remote_file))

    print(f'\nTotal files to process: {len(all_files)}')
    
    success = 0
    failed = 0
    for local_file, remote_file in all_files:
        if rftp.upload_file(local_file, remote_file):
            success += 1
        else:
            failed += 1

    print(f'\n=== SUMMARY ===')
    print(f'Uploaded successfully: {success}')
    print(f'Failed: {failed}')

if __name__ == '__main__':
    main()
