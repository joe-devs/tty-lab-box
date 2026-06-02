#!/bin/bash

REPORT_PATH="/root/server-report.txt"

if [ ! -f "$REPORT_PATH" ]; then
  FOUND_REPORT="$(find /home /root -maxdepth 3 -name server-report.txt 2>/dev/null | head -n 1)"
  if [ -n "$FOUND_REPORT" ]; then
    REPORT_PATH="$FOUND_REPORT"
  fi
fi

TOTAL=5
PASSED=0
CHECKS=""

add_check() {
  local name="$1"
  local passed="$2"

  if [ "$passed" = "true" ]; then
    PASSED=$((PASSED + 1))
  fi

  if [ -n "$CHECKS" ]; then
    CHECKS="$CHECKS,"
  fi

  CHECKS="$CHECKS{\"name\":\"$name\",\"passed\":$passed}"
}

if [ -f "$REPORT_PATH" ]; then
  add_check "Report file exists" true
else
  add_check "Report file exists" false
fi

if [ -f "$REPORT_PATH" ] && grep -q "$(hostname)" "$REPORT_PATH"; then
  add_check "Hostname is included" true
else
  add_check "Hostname is included" false
fi

if [ -f "$REPORT_PATH" ] && grep -Eiq "root|$(whoami)|user|current user" "$REPORT_PATH"; then
  add_check "Current user is included" true
else
  add_check "Current user is included" false
fi

if [ -f "$REPORT_PATH" ] && grep -Eiq "rocky|linux|pretty_name|version|os-release|operating system" "$REPORT_PATH"; then
  add_check "Operating system information is included" true
else
  add_check "Operating system information is included" false
fi

if [ -f "$REPORT_PATH" ] && grep -Eiq "filesystem|disk|df|avail|use%|mounted" "$REPORT_PATH"; then
  add_check "Disk usage information is included" true
else
  add_check "Disk usage information is included" false
fi

if [ -f "$REPORT_PATH" ] && grep -Eiq "mem:|memory|ram|free|available|swap" "$REPORT_PATH"; then
  add_check "Memory usage information is included" true
else
  add_check "Memory usage information is included" false
fi

TOTAL=6
SCORE=$((PASSED * 100 / TOTAL))

if [ "$PASSED" -eq "$TOTAL" ]; then
  RESULT_PASSED=true
  MESSAGE="Basic server inspection completed successfully."
else
  RESULT_PASSED=false
  MESSAGE="Some required information is missing from server-report.txt."
fi

printf '{"passed":%s,"score":%s,"checks":[%s],"message":"%s"}\n' \
  "$RESULT_PASSED" "$SCORE" "$CHECKS" "$MESSAGE"