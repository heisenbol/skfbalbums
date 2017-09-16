

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



# TODO
- translations
- remove deprecated methods for TYPO3 8 
- a howto to create facebook app and get the app id and secret
- some loggin for sync process and outbput in manual sync

# Change templates
You can override the template paths to adapt the look and feel of the display. 

Here is a quick howto:

## Album List
The album list supports as layout
- Default
- CssMasonry
- Raw

You can change each one of them, or only a specific one. The best one would be to change the the Raw layout. The others add custom css to the page, which is probably undesired.

The album list uses the following files
Templates/Album/List.html
Partials/Album/List/ALBUM_LIST_LAYOUT_SET_IN_PLUGIN.html

The List.html just includes the Partials/Album/List/ALBUM_LIST_LAYOUT_SET_IN_PLUGIN.html file. So it should suffice to just add your own version.

Let's say you want to change the Raw layout of the album list. Under fileadmin, create the following folders and files
fileadmin/skfbalbumoverrides/Partials/Album/
fileadmin/skfbalbumoverrides/Partials/Album/Raw.html

Take the extension's typo3conf/ext/skfbalbumoverrides/Partials/Album/Raw.html file content and add it to your own Raw.html file you just created.

In your Template setup, add the following

    plugin.tx_skfbalbums_fbalbumsdisplay {
        view {
            partialRootPaths.30 = fileadmin/skfbalbumoverrides/Partials/
        }
    }

Now instead of using the extensions Raw.html file, it will use your own Raw.html file and any changes you have made to it. You will probably need some custom css file and/or JavaScript, which should be included into your template.

To get the URL for the album cover photo, you can use the following viewhelper
{fbalbum:fbImage(photo: album.coverPhoto, size: 'medium', useFbRedirectUrls: useFbRedirectUrls)}

The size property takes the as value small, medium and large. From the available image versions that Facebook returns, it tries to find a suitable size and return it's url.

Please keep in mind that if you set to use Facebook redirect urls, the images returned by Facebook are rather small in dimensions.

## Single Album Photos
The album list supports as layout
- Default
- CssMasonry
- Unitegallery
- Raw

The process is similar to changing the layout of the album list. For overriding the Raw layout, you will need a file

Partials/Album/Show/ALBUM_SHOW_LAYOUT_SET_IN_PLUGIN.html

Similar to the album list, the viewhelper to return an image source is

{fbalbum:fbImage(photo: photo, size: 'medium', useFbRedirectUrls: useFbRedirectUrls)}


# Code
GitHub Repo at https://github.com/sksksksk/skfbalbums

# Changelog
0.0.6
- bump extension state to alpha
- added theme selection to unite gallery
- allow additional custom params for unite gallery
- added Raw layout and added some documentation on how to override it
- removed some TYPO 8 deprecated calls
- some renaming of files

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