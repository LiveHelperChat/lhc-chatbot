#!/bin/sh

if [ ! -f "doc/update_autocomplete.sh" -a \
     ! -d "train" ] ; then
     echo "You seem to be in the wrong directory"
     echo "Place yourself in the extension root directory and run ./doc/update_autocomplete.sh "http://localhost:7700/" master_key"
     exit 1
fi

if [ "$#" -eq  "0" ]
 then
   echo "Usage example - ./doc/update_autocomplete.sh "http://localhost:7700/" master_key"
   exit 1
fi

printf "MeiliSearch search host  - $1\n";
printf "MeiliSearch master key host  - $2\n";

printf "\n";

echo "API Keys"
curl -H "X-Meili-API-Key: $2" -X GET "$1/keys"

printf "\n\n";

echo "[If you do not terminate this script, auto complete update will start in 5 seconds]"

sleep 5

for i in ./train/autocomplete_hash*.json; do # Whitespace-safe but not recursive.

DEP=$(echo "$i" | sed -r 's/(.\/train\/autocomplete\_hash\_|.json)//g')

echo "Removing old data"
curl -H "X-Meili-API-Key: $2" -X DELETE "$1/indexes/dep_hash_$DEP"

echo "Storing new documents as hash completions"
curl -H "X-Meili-API-Key: $2" -X POST "$1/indexes/dep_hash_$DEP/documents" --data "@$i"

done


for i in ./train/autocomplete_text*.json; do # Whitespace-safe but not recursive.

DEP=$(echo "$i" | sed -r 's/(.\/train\/autocomplete\_text\_|.json)//g')

echo "Removing old data"
curl -H "X-Meili-API-Key: $2" -X DELETE "$1/indexes/dep_$DEP"

echo "Storing new documents as text completions"
curl -H "X-Meili-API-Key: $2" -X POST "$1/indexes/dep_$DEP/documents" --data "@$i"

echo "$DEP"

done




