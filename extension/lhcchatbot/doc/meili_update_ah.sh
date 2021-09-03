#!/bin/sh

if [ ! -f "doc/meili_update.sh" -a \
     ! -d "train" ] ; then
     echo "You seem to be in the wrong directory"
     echo "Place yourself in the extension root directory and run ./doc/meili_update.sh "http://localhost:7700/" master_key"
     exit 1
fi

if [ "$#" -eq  "0" ]
 then
   echo "Usage example - ./doc/meili_update.sh "http://localhost:7700/" <master_key> <lhc_address> <secret_hash> <domain>"
   echo "example - ./doc/meili_update.sh "http://localhost:7700/" master_key https://demo.livehelperchat.com/lhc_web/index.php secret_key livehelperchat.com"
   exit 1
fi

printf "MeiliSearch search host  - $1\n";
printf "MeiliSearch master key host  - $2\n";
printf "Live Helper Chat host  - $3\n";
printf "Secret hash - --notshown-- \n";

printf "\n";

wget --no-check-certificate "$3/instance/listexport?secret_hash=$4" -O instance.json

for k in $(jq '.| keys | .[]' instance.json); do
    value=$(jq -r ".[$k]" instance.json);
    id=$(jq -r '.id' <<< "$value");
    address=$(jq -r '.address' <<< "$value");
    echo "**********************************************************\n"
    ./doc/meili_update.sh $1 $2 "https://$address.$5" $4 $id
done







