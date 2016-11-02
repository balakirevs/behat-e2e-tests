#!/bin/bash

set -x
set -e

if [ ! -e /home/ubuntu/.phantomjs/2.1.1/x86_64-linux/bin ]; then
  mkdir -p /home/ubuntu/.phantomjs/2.1.1/x86_64-linux/bin
  curl --output /home/ubuntu/.phantomjs/2.1.1/x86_64-linux/bin/phantomjs https://s3.amazonaws.com/circle-downloads/phantomjs-2.1.1
  chmod ugo+x /home/ubuntu/.phantomjs/2.1.1/x86_64-linux/bin/phantomjs
fi
