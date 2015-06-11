#!/bin/sh
set -e
CURRENT_DIR=$(pwd)
${CURRENT_DIR}/bin/phpunit --configuration unit_tests.xml
cd ${CURRENT_DIR}/vendor/behat/mink/driver-testsuite/web-fixtures
ps axo pid,command | grep php | grep -v grep | awk '{print $1}' | xargs -I {} kill {}
php -S 127.0.0.1:6789 2>&1 >> /dev/null &
cd ${CURRENT_DIR}
ls -lah
${CURRENT_DIR}/bin/phpunit --configuration integration_tests.xml
