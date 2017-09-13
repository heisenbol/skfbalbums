

THIS IS AN EXTENSION IN IT'S EALRY STAGES. TOTALLY EXPERIMENTAL

# Quick setup
* install extension. If you want to use the backend module, add the static template into your template
* anywhere in your page tree, create a record of type "FB Albums->Token". Type some name (for your own use), a Facebook App Id and secret, as well as a Facebook Page Id (can be found on the FB page's about link, at the bottom). Leave the rest empty
* create a scheduler task "Sync Facebook Page Albums". Leave everything empty. 
* run the created task
* go back to the page where you created the Token record. You should see an Album record for each synced FB Page Album, and Photo records for each synced Photo
* now create a page and insert a plugin "FB ALbums List"
* in the plugin settings, set "Record Storage Page" to the page/folder you have created and imported the albums and photos
* you should see in the FE the album list, and after clicking on an album image, the image list. If not, clear your page cache, and then try again
* alternatively to the scheduler task for importing/updating albums, you can use the Backend Module to sync albums


Unitegallery layouts needs jquery to work. You have to add it manually to your page template. E.g.

    page.includeJS {
        jquery = //ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js
        jquery {
            forceOnTop = 1
            disableCompression = 1
            excludeFromConcatenation = 1
            external = 1
        }
    }

Questions? Contact sk(at)karasavvidis.gr



TODO
- more display layouts using unitegallery and more themes / options for it
- remove css inclusion from controller to allow easier custom templates
- translations
- remove deprecated methods for TYPO3 8 
- a howto to create facebook app and get the app id and secret
- add a "raw" display mode that outputs just raw data to be easily customized

# Code
GitHub Repo at https://github.com/sksksksk/skfbalbums

# Changelog
0.0.6-dev
- bump extension state to alpha

0.0.5
- option to use redirect urls for images to leverage Facebook CDN. Results in smaller images!
- multiple single album display on single page with different layouts
- move unitegallery initialization to bottom to cope with jquery being at the bottom of the page

0.0.4
- restore php5.5 compatibility
- plugin uses flexform to allow single album display 

0.0.3
- nothing new. Reupload as 0.0.3 due to https://typo3.org/teams/security/security-bulletins/psa/typo3-psa-2017-001/

0.0.2
- added unitegallery for single album display with default options (needs jquery)
- clarify that inclusion of static TS template is necessary only if backend module is to be used (needed for the module view location)
- scheduler task clears cache of pages showing album lists or albums (used cache tags)
- added Backend Module that allows to sync albums on demand
- bump TYPO3 version requirements to include 8 LTS