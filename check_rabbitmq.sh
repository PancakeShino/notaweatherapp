#!/bin/bash
if systemctl is-active --quiet rabbitmq-server; then
  exit 0
else
  exit 1
fi
