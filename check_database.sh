#!/bin/bash
if systemctl is-active --quiet mysql; then
  exit 0
else
  exit 1
fi
