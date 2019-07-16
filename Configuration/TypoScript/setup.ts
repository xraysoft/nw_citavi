
plugin.tx_nwcitavi {
  view {
    templateRootPaths.0 = EXT:nw_citavi/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_nwcitavi.view.templateRootPath}
    partialRootPaths.0 = EXT:nw_citavi/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_nwcitavi.view.partialRootPath}
    layoutRootPaths.0 = EXT:nw_citavi/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_nwcitavi.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_nwcitavi.persistence.storagePid}
    #recursive = 1
  }
  features {
    #skipDefaultArguments = 1
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }
  settings {
    cssCustom = {$plugin.tx_nwcitavi.settings.cssCustom}
    no_upload = {$plugin.tx_nwcitavi.settings.no_upload}
    scheduler = {$plugin.tx_nwcitavi.settings.scheduler}
    pdf.path = {$plugin.tx_nwcitavi.settings.pdf.path}
    export.path = {$plugin.tx_nwcitavi.settings.export.path}
    sPid = {$plugin.tx_nwcitavi.settings.sPid}
  }
}

page.includeJSFooter.custom = {$plugin.tx_nwcitavi.settings.jsCustom}
page.includeJSFooter.clipboard = {$plugin.tx_nwcitavi.settings.jsClipboard}

ajaxCall = PAGE
ajaxCall {
    typeNum = 667
    config {
        disableAllHeaderCode = 1
        additionalHeaders = Content-type:text/html
        xhtml_cleaning = 0
        admPanel = 0
        debug = 1
        no_cache = 1
        metaCharset = UTF-8
    }

    10 = USER
    10 {
        userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
        pluginName = Citavilist
        extensionName = NwCitavi
        controller = Reference
        vendorName = Netzweber
        action = showsingle
        switchableControllerActions {
            Reference {
                1 = ajaxcall
            }
        }

        settings =< plugin.tx_fr_gallery.settings
        persistence =< plugin.tx_fr_gallery.persistence
        view =< plugin.tx_fr_gallery.view
        update =< plugin.tx_fr_gallery.update
        persistence.storagePid = 22
    }
}

config.tx_extbase.persistence.classes {
  Netzweber\NwCitavi\Domain\Model\Reference {
    mapping {
      tableName = tx_nwcitavi_domain_model_reference
      recordType = \Netzweber\NwCitavi\Domain\Model\Reference
    }
  }
  Netzweber\NwCitavi\Domain\Model\Category {
    mapping {
      tableName = tx_nwcitavi_domain_model_category
      recordType = \Netzweber\NwCitavi\Domain\Model\Category
    }
  }
  Netzweber\NwCitavi\Domain\Model\Keyword {
    mapping {
      tableName = tx_nwcitavi_domain_model_keyword
      recordType = \Netzweber\NwCitavi\Domain\Model\Keyword
    }
  }
  Netzweber\NwCitavi\Domain\Model\KnowledgeItem {
    mapping {
      tableName = tx_nwcitavi_domain_model_knowledgeitem
      recordType = \Netzweber\NwCitavi\Domain\Model\KnowledgeItem
    }
  }
  Netzweber\NwCitavi\Domain\Model\Library {
    mapping {
      tableName = tx_nwcitavi_domain_model_library
      recordType = \Netzweber\NwCitavi\Domain\Model\Library
    }
  }
  Netzweber\NwCitavi\Domain\Model\Location {
    mapping {
      tableName = tx_nwcitavi_domain_model_location
      recordType = \Netzweber\NwCitavi\Domain\Model\Location
    }
  }
  Netzweber\NwCitavi\Domain\Model\Periodical {
    mapping {
      tableName = tx_nwcitavi_domain_model_periodical
      recordType = \Netzweber\NwCitavi\Domain\Model\Periodical
    }
  }
  Netzweber\NwCitavi\Domain\Model\Person {
    mapping {
      tableName = tx_nwcitavi_domain_model_person
      recordType = \Netzweber\NwCitavi\Domain\Model\Person
    }
  }
  Netzweber\NwCitavi\Domain\Model\Publisher {
    mapping {
      tableName = tx_nwcitavi_domain_model_publisher
      recordType = \Netzweber\NwCitavi\Domain\Model\Publisher
    }
  }
  Netzweber\NwCitavi\Domain\Model\Seriestitle {
    mapping {
      tableName = tx_nwcitavi_domain_model_seriestitle
      recordType = \Netzweber\NwCitavi\Domain\Model\Seriestitle
    }
  }
}