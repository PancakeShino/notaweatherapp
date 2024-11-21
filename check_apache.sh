#!/bin/bash
if systemctl is-active --quiet apache2; then
  exit 0
else
  exit 1
fi
