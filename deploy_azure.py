import paramiko
import sys

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect("20.187.146.70", 22, "studentshield", "VonnAsh12HairoMorale")

cmds = [
    "unzip -o ~/ml_service_deploy.zip -d ~/ml_service",
    "cd ~/ml_service && sudo docker build -t studentshield-ml .",
    "sudo docker stop ml-api || true",
    "sudo docker rm ml-api || true",
    "cd ~/ml_service && sudo docker run -d --name ml-api -p 80:7860 studentshield-ml"
]

for cmd in cmds:
    print(f"Running: {cmd}")
    i, o, e = ssh.exec_command(cmd)
    status = o.channel.recv_exit_status()
    print("STDOUT:", o.read().decode('utf-8', 'ignore'))
    print("STDERR:", e.read().decode('utf-8', 'ignore'))

ssh.close()
