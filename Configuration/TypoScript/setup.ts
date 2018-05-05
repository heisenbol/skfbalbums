
plugin.tx_skfbalbums_fbalbumsdisplay {
  view {
    templateRootPaths.0 = EXT:skfbalbums/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_skfbalbums_fbalbumsdisplay.view.templateRootPath}
    partialRootPaths.0 = EXT:skfbalbums/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_skfbalbums_fbalbumsdisplay.view.partialRootPath}
    layoutRootPaths.0 = EXT:skfbalbums/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_skfbalbums_fbalbumsdisplay.view.layoutRootPath}
  }
  #removed so it uses always the plugin settings of BE
  #persistence {
    #storagePid = {$plugin.tx_skfbalbums_fbalbumsdisplay.persistence.storagePid}
    #recursive = 1
  #}
  features {
    #skipDefaultArguments = 1
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }
}




module.tx_skfbalbums_web_skfbalbumsmod1 {
  persistence {
    storagePid = {$module.tx_skfbalbums_mod1.persistence.storagePid}
  }
  view {
    templateRootPaths.0 = EXT:skfbalbums/Resources/Private/Backend/Templates/
    templateRootPaths.1 = {$module.tx_skfbalbums_mod1.view.templateRootPath}
    partialRootPaths.0 = EXT:skfbalbums/Resources/Private/Backend/Partials/
    partialRootPaths.1 = {$module.tx_skfbalbums_mod1.view.partialRootPath}
    layoutRootPaths.0 = EXT:skfbalbums/Resources/Private/Backend/Layouts/
    layoutRootPaths.1 = {$module.tx_skfbalbums_mod1.view.layoutRootPath}
  }
}

#module.tx_blogexample {
#    settings < plugin.tx_blogexample.settings
#    persistence < plugin.tx_blogexample.persistence
#    view < plugin.tx_blogexample.view
#    view {
#        templateRootPath = EXT:blog_example/Resources/Private/Backend/Templates/
#        partialRootPath = EXT:blog_example/Resources/Private/Partials/
#        layoutRootPath = EXT:blog_example/Resources/Private/Backend/Layouts/
#    }
#}




plugin.tx_skfbalbums._CSS_DEFAULT_STYLE (
#    textarea.f3-form-error {
#        background-color:#FF9F9F;
#        border: 1px #FF0000 solid;
#    }
#
#    input.f3-form-error {
#        background-color:#FF9F9F;
#        border: 1px #FF0000 solid;
#    }
#
#    .tx-skfbalbums table {
#        border-collapse:separate;
#        border-spacing:10px;
#    }
#
#    .tx-skfbalbums table th {
#        font-weight:bold;
#    }
#
#    .tx-skfbalbums table td {
#        vertical-align:top;
#    }
#
#    .typo3-messages .message-error {
#        color:red;
#    }
#
#    .typo3-messages .message-ok {
#        color:green;
#    }
)
