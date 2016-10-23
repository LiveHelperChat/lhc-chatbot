ChatterBot extension for Live Helper Chat

1. Copy osticket folder to extension folder so it should look like
extension/lhcchatbot

2. Execute install.sql script on your database

3. Edit extension settings in lhcchatbot/settings.ini.php
set your 
"secret_hash"
"host"

4. Activate extension in live helper chat, edit settings/settings.ini.php so your extensions part should look like
'extensions' => 
      array (
        0 => 'lhcchatbot',
 )
 
5. Login to back office and clear cache.
 
6. In left menu you will see ChatBot item. Just create new questions answers and suggested answers based on user messages will appear automatically