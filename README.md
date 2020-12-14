# Live Helper Chat official plugin for ChatterBot

Automatic suggestions for an operators. AI powered by DeepPavlov.

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

## DeepPavlov setup

Navigate to `deeppavlov` and copy `.env.default` to `.env`

Training is always happening on a startup.

There is a two ways DeepPavlov can work. Wither with spellchecker eiter without.

### Without spellchecker

```shell
# Optional to build an image
# docker-compose -f docker-compose.yml build

# Train and run image
docker-compose -f docker-compose.yml up
```

Run as service once it's build

```shell
docker-compose -f docker-compose.yml up -d
```

