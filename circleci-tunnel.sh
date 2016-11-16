#!/bin/bash -x
set -e

function tunnel_up(){
    sudo apt-get install redsocks -y
    ssh -o StrictHostKeyChecking=no -v aleksandr@10.5.0.90 -22 -i ~/.ssh/id_rsa  -D 9999 -nf "sleep 90000" &
    echo 'base{log_debug = on; log_info = on; log = "file:/tmp/reddi.log";daemon = on; redirector = iptables;}redsocks { local_ip = 127.0.0.1; local_port = 12345; ip = 127.0.0.1;port = 9999; type = socks5; }' > ~/redsocks.conf
    sudo redsocks -c ~/redsocks.conf &
    sudo iptables -t nat -N REDSOCKS
    sudo iptables -t nat -A REDSOCKS -p tcp -d 10.0.0.0/8 -j DNAT --to 127.0.0.1:12345
    sudo iptables -t nat -A OUTPUT -d 10.0.0.0/8 -j REDSOCKS
    sudo iptables -t nat -I PREROUTING 1 -d 10.0.0.0/8 -j REDSOCKS
}

if [[ $1 == "start" ]]; then
    tunnel_up
fi
