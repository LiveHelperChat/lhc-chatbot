Automatic suggestions for an operators. AI powered by DeepPavlov.

![See image](https://github.com/LiveHelperChat/lhc-chatbot/blob/master/extension/lhcchatbot/doc/quick-response.png?raw=true)

## Install instructions

* First you have to install extension itself
* After you have installed extension and added few most common questions. You can proceed with DeepPavlov training.

## Live Helper Chat instructions install

After you have cloned repository you can copy `extension/lhcchatbot` to extensions folders of Live Helper chat

so it should look like `lhc_web\extension/lhcchatbot`

### Install database

You can either run [this file](https://github.com/LiveHelperChat/lhc-chatbot/blob/master/extension/lhcchatbot/doc/install.sql) SQL directly or run this command

```shell
php cron.php -s site_admin -e lhcchatbot -c cron/update_structure
```

### Copy extension settings file

`extension/lhcchatbot/settings/settings.ini.default.php` to `extension/lhcchatbot/settings/settings.ini.php`

### Activate extension in main APP settings file

Edit main application settigns.gile

```php
'extensions' => 
      array (
        'lhcchatbot'
      ),
```

### Enter some common questions in back office

* First you have to create at-least one `Context` (`Modules -> Reply predictions`)
* Edit department and choose your newly created `Context` to be server for edited department.
* Adding questions
    * Questions can be added by selecting visitor message with mouse direclty in the chat and clicking plus icon.
    * Questions can also be added from left menu `Modules -> Reply predictions`
* After you have added few questions you can run this command

You have to run before proceeding

```shell
/usr/bin/php cron.php -s site_admin -e lhcchatbot -c cron/deeppavlov_train
```

After that you should see csv file (`train_1.csv` most likely if you have one context) in `extension/lhcchatbot/train` folder.

Copy those files to `deeppavlov/Dockerfiles/deep/train` folder of cloned repository.

## MeiliSearch setup

MeiliSearch allows instant auto completion suggestions based on chats history and canned messages.

Navigate to `deeppavlov` and copy `.env.default` to `.env`

Edit `.env` file `LHC_MEILI_SEARCH_MASTER_KEY` value and set your own master key value.

### Start a docker service

Start one time

```shell
docker-compose -f docker-meilisearch-compose.yml up
```

Start as a service

```shell
docker-compose -f docker-meilisearch-compose.yml up -d
```

### Export data for auto completion

If you are planning to update constantly auto completion data it makes sense to run this command once a week.

```shell
/usr/bin/php cron.php -s site_admin -e lhcchatbot -c cron/auto_complete
```

After above command is execute you will see in `extension/lhcchatbot/train` folder `autocomplete_hash_<dep_id>.json` and `autocomplete_text_<dep_id>.json` files. If you wish you can always adjust file manually or just modify script itself.

Now run in shell. It will feed auto complete data to MeiliSearch. It will print also `Public Key`

```shell
cd extension/lhcchatbot && ./doc/update_autocomplete.sh "http://localhost:7700/" <master_key>
```

### Configure Live Helper chat

In `Reply Predictions` module you will find menu item called `Auto complete` and set `Public key`. Public key you will get from above command. Auto completion has to be enabled per department. Edit department and enable it in `Reply Predictions` tab.

In messages you can also use placeholders `{nick}`, `{operator}`, `{year}`, `{month}` just start typing any of these keywords.

### Nginx configuration example

```apacheconf
location /msearch/ {
    proxy_pass  http://127.0.0.1:7700/;
}
```

### How to use?

* Start typing your regular sentences, and you will see possible sentence endings at the bottom.
* To replace all what you typed you can use `#<your search query>` also

![See image](https://github.com/LiveHelperChat/lhc-chatbot/blob/master/extension/lhcchatbot/doc/auto-complete.png?raw=true)

## DeepPavlov setup

Navigate to `deeppavlov` and copy `.env.default` to `.env`

Training is always happening on a docker image startup.

There is a two ways DeepPavlov can work. Wither with spellchecker or without.

### Without spellchecker

```shell
# Optional to build an image
# docker-compose -f docker-do-compose.yml build

# Train and run image
docker-compose -f docker-dp-compose.yml up
```

Run as service once it's build.

```shell
docker-compose -f docker-dp-compose.yml up -d
```

To test does it works you can use CURL command

```shell
curl -X POST "http://localhost:5000/model" -H  "accept: application/json" -H  "Content-Type: application/json" -d "{\"q\":[\"hi\"]}"
```

### With spellchecker

With spellchecker visitor messages before running against your questions will be checked against spelling errors.

Spellchecker requires these changes.

* Edit `.env` file and change `LHC_API=train_tfidf_logreg_en_faq.json` to `LHC_API=riseapi.json`
* Navigate to `deeppavlov/Dockerfiles/deep/data/downloads/language_models` and see README.md file content. You will need to download file which is 6GB file size!

### Automating retraining

The Easiest way is just to have some shell which would run daily something like that. This is just an example adopt it to your needs.

```shell

# Export trainings Adjust paths!
cd `lhc_web/` && /usr/bin/php cron.php -s site_admin -e lhcchatbot -c cron/deeppavlov_train

# Copy trainings. Adjust paths!
cd ../ && cp extension/lhcchatbot/train/* /deeppavlov/Dockerfiles/deep/train

# Restart docker image
docker-compose restart deeppavlov-lhcchatbot 
```

### Supporting more than one context

The easiest way is just to modify `docker-compose.yml` file and add more than one service with different configuration

Possible workflow

* You should modify `ports` sections of `docker-compose.yml`
* Create a copy of `deeppavlov/Dockerfiles/deep/data` like `deeppavlov/Dockerfiles/deep/data_2`
* Modify `volumes:` section `- ./Dockerfiles/deep/data:/base/deep` to something like `- ./Dockerfiles/deep/data_2:/base/deep`
* Modify `volumes:` section `- "./Dockerfiles/deep/train/${LHC_TRAIN_FILE}:/base/train/train.csv"` to something like `- "./Dockerfiles/deep/train/train_2.csv:/base/train/train.csv"`
* Modify `container_name` from `deeppavlov-lhcchatbot` to `deeppavlov-lhcchatbot-german` as an example
* Modify `- LHC_API=${LHC_API}` if you are using spellchecker. As most likelu it will not setup for other langauge than english. Put there `train_tfidf_logreg_en_faq.json`

After that don't forget to modify your new context and set host to new url with a new port.

Examples configuration

```yaml
  deeppavlov-lhcchatbot-german:
    build: ./Dockerfiles/deep
    environment:
      - LHC_API=train_tfidf_logreg_en_faq.json
    container_name: deeppavlov-lhcchatbot-german
    image: remdex/deeppavlov-lhcchatbot:latest
    ports:
      - "5005:5000"
    volumes:
      - ./Dockerfiles/deep/data_2:/base/deep
      - ./Dockerfiles/deep/config:/base/config
      - "./Dockerfiles/deep/train/train_9.csv:/base/train/train.csv"
    networks:
      - code-network
    restart: always
```
