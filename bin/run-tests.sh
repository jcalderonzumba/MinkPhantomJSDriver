#!/bin/bash
set -e

BIN_DIR=$(cd $(dirname $0); pwd)

start_browser_api(){
  LOCAL_PHANTOMJS="${BIN_DIR}/phantomjs"
  if [ -f ${LOCAL_PHANTOMJS} ]; then
    ${LOCAL_PHANTOMJS} --ssl-protocol=any --ignore-ssl-errors=true "$BIN_DIR/../vendor/jcalderonzumba/gastonjs/src/Client/main.js" 8510 1024 768 2>&1 &
  else
    phantomjs --ssl-protocol=any --ignore-ssl-errors=true "$BIN_DIR/../vendor/jcalderonzumba/gastonjs/src/Client/main.js" 8510 1024 768 2>&1 >> /dev/null &
  fi
  sleep 2
}

stop_services(){
  ps axo pid,command | grep phantomjs | grep -v grep | awk '{print $1}' | xargs -I {} kill {}
  ps axo pid,command | grep php | grep -v grep | grep -v phpstorm | grep -v php-fpm | awk '{print $1}' | xargs -I {} kill {}
  sleep 2
}

start_local_browser(){
  ${BIN_DIR}/mink-test-server > /dev/null 2>&1 &
  sleep 2
}

function finish() {
  stop_services
  if [ -z "$MINK_STOP_BROWSER" ]; then
    start_browser_api
  fi
}

trap finish EXIT

mkdir -p /tmp/jcalderonzumba/phantomjs
stop_services || true
start_browser_api
start_local_browser
${BIN_DIR}/phpunit --configuration "$BIN_DIR/../integration_tests.xml"
