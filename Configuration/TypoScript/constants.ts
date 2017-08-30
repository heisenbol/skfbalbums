
plugin.tx_skfbalbums_fbalbumsdisplay {
  view {
    # cat=plugin.tx_skfbalbums_fbalbumsdisplay/file; type=string; label=Path to template root (FE)
    templateRootPath = EXT:skfbalbums/Resources/Private/Templates/
    # cat=plugin.tx_skfbalbums_fbalbumsdisplay/file; type=string; label=Path to template partials (FE)
    partialRootPath = EXT:skfbalbums/Resources/Private/Partials/
    # cat=plugin.tx_skfbalbums_fbalbumsdisplay/file; type=string; label=Path to template layouts (FE)
    layoutRootPath = EXT:skfbalbums/Resources/Private/Layouts/
  }
  #removed so it uses always the plugin settings of BE
  #persistence {
    # cat=plugin.tx_skfbalbums_fbalbumsdisplay//a; type=string; label=Default storage PID
  #  storagePid =
  #}
}
