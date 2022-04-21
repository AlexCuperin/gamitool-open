#!/bin/bash
echo "***[CREATE] connecting bob and creating backup"
ssh alex@gamitool "cd /home/alex/dbbackups/tmp && ../create_backup.sh portal"

echo "***[CRAWLING] connecting bob and catching name"
backup=$(ssh alex@gamitool "cd /home/alex/dbbackups/tmp/ && ls")

echo "***[CATCHING] captured backup: $backup; moving to original folder; and copying to localhost"
ssh alex@gamitool "cd /home/alex/dbbackups/tmp/ && mv * ../"
scp alex@gamitool:dbbackups/$backup .

echo "***[LOADING] loading backup to localhost"
./load_backup.sh $backup

