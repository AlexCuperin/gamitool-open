#!/bin/bash
# Load the variables
source $(dirname "$0")/config.sh

# commented loads in dev
# uncommented loads in localhosts - H

dev_user="postgres"
dev_host="localhost"
dbname="gamitool"

# Get the optional -h option for hostname
while getopts ":h:" opt; do
    case $opt in
    h)
        dev_host=$OPTARG
        ;;
    \?)
        echo "Invalid option: -$OPTARG" >&2
        exit
        ;;
    esac
done

# Make $1 the next argument
shift $((OPTIND-1))

# Check the file argument
if [ -z "$1" ]

then
    echo "Usage: [-h hostname] $0 DUMP_FILE";
    exit
fi

# Drop the current database
echo "*************************************************************************"
echo "* Dropping tables of DB '$dbname' in dev: $dev_user@$dev_host"
echo "*************************************************************************"
psql -h $dev_host -U $dev_user $dbname -t -c "select 'drop table \"' || tablename || '\" cascade;' from pg_tables where schemaname='public'" | psql -h $dev_host -U $dev_user $dbname

# Restore
echo "*************************************************************************"
echo "* Restoring dump '$1' of DB '$dbname' in dev: $dev_user@$dev_host"
echo "*************************************************************************"
#pg_restore --exit-on-error --create --clean --schema=public --no-owner -h $dev_host -U $dev_user --dbname=$dbname $1
pg_restore --create --clean --schema=public --no-owner -h $dev_host -U $dev_user --dbname=$dbname $1

