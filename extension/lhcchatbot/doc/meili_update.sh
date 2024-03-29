#!/bin/sh

if [ ! -f "doc/meili_update.sh" -a \
     ! -d "train" ] ; then
     echo "You seem to be in the wrong directory"
     echo "Place yourself in the extension root directory and run ./doc/meili_update.sh "http://localhost:7700/" master_key"
     exit 1
fi

if [ "$#" -eq  "0" ]
 then
   echo "Usage example - ./doc/meili_update.sh "http://localhost:7700/" <master_key> <lhc_address> <secret_hash> <instance_id>"
   echo "example - ./doc/meili_update.sh "http://localhost:7700/" master_key https://demo.livehelperchat.com/lhc_web/index.php secret_key 0"
   exit 1
fi

printf "MeiliSearch search host  - $1\n";
printf "MeiliSearch master key host  - $2\n";
printf "Live Helper Chat host  - $3\n";
printf "Secret hash - --notshown-- \n";
printf "Live Helper Chat Instance  - $5\n";

printf "\n";

echo "API Keys"
curl -H "X-Meili-API-Key: $2" -X GET "$1/keys"

printf "\n\n";

echo "[If you do not terminate this script, auto complete update will start in 5 seconds]"

rm -f train_old.json
mv train.json train_old.json

wget --no-check-certificate "$3/lhcchatbot/exportsuggestions?secret_hash=$4" -O train.json

if cmp --silent -- "./train.json" "./train_old.json";
then
      echo "Training files are identical. Skipping!"
      exit 1
fi


echo "Removing old data"
curl -H "X-Meili-API-Key: $2" -X DELETE "$1/indexes/lhc_suggest_$5"

printf "\n";

printf "Creating index\n"
curl -H "X-Meili-API-Key: $2" -X POST "$1/indexes" --data "{\"uid\": \"lhc_suggest_$5\" }"

printf "\nSetting searchableAttributes\n";

curl -H "X-Meili-API-Key: $2" -X POST "$1/indexes/lhc_suggest_$5/settings" --data '{
  "searchableAttributes": [
	  "msg"
  ]
}'

printf "\nSetting filterable attribute\n";

curl -H "X-Meili-API-Key: $2" -X POST "$1/indexes/lhc_suggest_$5/settings/filterable-attributes" --data '[
      "context_id"
]'

printf "\n";

curl -H "X-Meili-API-Key: $2" "$1/indexes/lhc_suggest_$5/settings/filterable-attributes"

printf "\nSetting stop words\n";

curl -H "X-Meili-API-Key: $2" -X POST "$1/indexes/lhc_suggest_$5/settings/stop-words" --data "@doc/stop-words.json"

printf "\nStoring new documents as hash completions\n"

curl -H "X-Meili-API-Key: $2" -X POST "$1/indexes/lhc_suggest_$5/documents" --data "@train.json"










