RTE.default {
  linkhandler {
    tx_aigallery_galleries {
      default {
        # instead of default you could write the id of the storage folder
        # id of the Single Gallery Page
        parameter = 14
        additionalParams = &tx_aigallery_pi1[gallery]={field:uid}
        additionalParams.insertData = 1
        select = uid,title as header,hidden,starttime,endtime
        sorting = crdate desc
      }
    }
  }
}