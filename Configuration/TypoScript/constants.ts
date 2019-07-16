plugin.tx_nwcitavi {
  view {
    # cat=plugin.tx_nwcitavi/file; type=string; label=Path to template root (FE)
    templateRootPath = EXT:nw_citavi/Resources/Private/Templates/
    # cat=plugin.tx_nwcitavi/file; type=string; label=Path to template partials (FE)
    partialRootPath = EXT:nw_citavi/Resources/Private/Partials/
    # cat=plugin.tx_nwcitavi/file; type=string; label=Path to template layouts (FE)
    layoutRootPath = EXT:nw_citavi/Resources/Private/Layouts/
  }
  persistence {
    # cat=plugin.tx_nwcitavi/links/a; type=string; label=Default storage PID
    storagePid =
  }
  settings {
    # cat=plugin.tx_nwcitavi/file/a; type=string; label=Path to Custom JavaScript (FE)
    jsCustom = EXT:nw_citavilist/Resources/Public/JavaScript/custom.js
    # cat=plugin.tx_nwcitavi/file/a; type=string; label=Path to Clipboard JavaScript (FE)
    jsClipboard = EXT:nw_citavilist/Resources/Public/jquery/ZeroClipboard.js
    # cat=plugin.tx_nwcitavi/enable/a; type=string; label=Enable/Disable Custom css
    cssCustom = 1
    # cat=plugin.tx_nwcitavi/enable/a; type=string; label=Enable/Disable Citavi Upload
    no_upload = 0
    # cat=plugin.tx_nwcitavi/enable/a; type=string; label=Enable/Disable Scheduler mode
    scheduler = 1
    # cat=plugin.tx_nwcitavi/file/a; type=string; label=Path to PDFs
    pdf.path = fileadmin/user_upload/citavi_upload/files/
    # cat=plugin.tx_nwcitavi/file/a; type=string; label=Path to Exports
    export.path = fileadmin/user_upload/citavi_upload/export/
    # cat=plugin.tx_nwcitavi/links/a; type=string; label=storagePid
    sPid = 39
    # cat=plugin.tx_nwcitavi//a; type=string; label=E-Mail
    #email = lutz.eckelmann@netzweber.de
    # cat=plugin.tx_nwcitavi//a; type=string; label=Subject
    #subject = Upload beendet
    # cat=plugin.tx_nwcitavi/links/a; type=int; label= Citavi File Storage pid
    fileStoragePid = 0
  }
}