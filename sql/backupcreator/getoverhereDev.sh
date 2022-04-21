#!/bin/bash
echo "***[CREATE] connecting DEV-GAMITOOL and creating backup"
ssh alex@dev-gamitool "cd /home/alex/dbbackups/tmp && ../create_backup.sh portal"

echo "***[CRAWLING] connecting DEV-GAMITOOL and catching name"
backup=$(ssh alex@dev-gamitool "cd /home/alex/dbbackups/tmp/ && ls")

echo "***[CATCHING] captured backup: $backup; moving to original folder; and copying to localhost"
ssh alex@dev-gamitool "cd /home/alex/dbbackups/tmp/ && mv * ../"
scp alex@dev-gamitool:dbbackups/$backup .

echo "***[LOADING] loading backup to localhost"
./load_backup.sh $backup

