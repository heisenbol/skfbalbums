GitHub Repo at https://github.com/sksksksk/skfbalbums

THIS IS AN EXTENSION IN IT'S EALRY STAGES. TOTALLY EXPERIMENTAL

Quick setup
- install extension. If you want to use the backend module, add the static template into your template
- anywhere in your page tree, create a record of type "FB Albums->Token". Type some name (for your own use), a Facebook App Id and secret, as well as a Facebook Page Id (can be found on the FB page's about link, at the bottom). Leave the rest empty
- create a scheduler task "Sync Facebook Page Albums". Leave everything empty. 
- run the created task
- go back to the page where you created the Token record. You should see an Album record for each synced FB Page Album, and Photo records for each synced Photo
- now create a page and insert a plugin "FB ALbums List"
- in the plugin settings, set "Record Storage Page" to the page/folder you have created and imported the albums and photos
- you should see in the FE the album list, and after clicking on an album image, the image list. If not, clear your page cache, and then try again
- alternatively to the scheduler task for importing/updating albums, you can use the Backend Module to sync albums


Questions? Contact sk(at)karasavvidis.gr



TODO
- plugin for single album display
- more display layouts using unitegallery
- remove css inclusion from controller to allow easier custom templates
- clarify things with image urls that are FB CDN Urls
- translations
- remove deprecated methods for TYPO3 8 


DONE
- clear page caches where plugin is inserted after each sync
- BE module to allow on demand syncs (without the scheduler) by BE users



New in 0.0.2
- added unitegallery for single album display with default options (needs jquery)
- clarify that inclusion of static TS template is necessary only if backend module is to be used (needed for the module view location)
- scheduler task clears cache of pages showing album lists or albums (used cache tags)
- added Backend Module that allows to sync albums on demand
- bump TYPO3 version requirements to include 8 LTS