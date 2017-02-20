#!/usr/bin/env bash
cd ~

URL=http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/
PACKAGE=wkhtmltox-0.12.2.1_linux-centos7-amd64.rpm

wget ${URL}${PACKAGE}
yum localinstall -y ${PACKAGE}
rm -f ${PACKAGE}
