import os
import sys
import paramiko
from scp import SCPClient

# Force UTF-8 output on Windows
os.environ["PYTHONIOENCODING"] = "utf-8"

def progress(filename, size, sent):
    pct = float(sent)/float(size)*100
    sys.stdout.write(f"\rUploading: {pct:.0f}%")
    sys.stdout.flush()

if __name__ == "__main__":
    ip = "20.187.146.70"
    user = "studentshield"
    password = "VonnAsh12HairoMorale"
    
    local_file = r"d:\laragon\www\student shield\ml_service_deploy.zip"
    
    print(f"Connecting to {ip}...")
    try:
        ssh = paramiko.SSHClient()
        ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        ssh.connect(ip, 22, user, password, timeout=10)
        print("Connected!")
        
        # Upload zip
        print("Uploading zip...")
        with SCPClient(ssh.get_transport(), progress=progress) as scp:
            scp.put(local_file, remote_path="/home/studentshield/ml_service_deploy.zip")
        print("\nUpload done!")
        
        # Run all commands as a single shell script on the server
        # This avoids the charmap issue entirely
        script = """#!/bin/bash
set -e
echo "=== STEP 1: Unzip ==="
rm -rf ~/ml_service
mkdir -p ~/ml_service
unzip -o ~/ml_service_deploy.zip -d ~/ml_service
echo "=== STEP 2: Docker Build ==="
cd ~/ml_service
sudo docker build -t studentshield-ml .
echo "=== STEP 3: Stop old container ==="
sudo docker stop ml-api 2>/dev/null || true
sudo docker rm ml-api 2>/dev/null || true
echo "=== STEP 4: Run new container ==="
sudo docker run -d --name ml-api -p 80:7860 studentshield-ml
echo "=== DONE ==="
sudo docker ps
"""
        # Write the script to server and execute it
        print("Running build script on server (this takes ~5 min)...")
        stdin, stdout, stderr = ssh.exec_command(f"echo '{script}' > /tmp/deploy.sh && bash /tmp/deploy.sh 2>&1")
        
        # Stream output line by line, handling encoding safely
        while True:
            line = stdout.readline()
            if not line:
                break
            try:
                print(line.rstrip())
            except UnicodeEncodeError:
                print(line.encode('ascii', 'replace').decode('ascii').rstrip())
        
        exit_status = stdout.channel.recv_exit_status()
        print(f"\nExit status: {exit_status}")
        
        ssh.close()
        
        if exit_status == 0:
            print("\n SUCCESS! Your ML API is live at http://20.187.146.70")
        else:
            print("\n Build failed. Check the output above.")
        
    except Exception as e:
        print(f"\nError: {e}")
