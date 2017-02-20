#!/usr/bin/env bash
echo "sql_mode=\"\"" | tee -a /etc/my.cnf
service mysqld restart