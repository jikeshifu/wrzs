export default {
  data() {
    return {
      // 上传地址
      uploadURL: process.env.VUE_APP_BASE_API + '/upload.File/upload',
      // 域名网址
      baseUrl: process.env.VUE_APP_BASE_HTTP,
      // 公用 table 数据
      Mix_tableData: {
        list: [],
        pageSize: 10,
        currentPage: 1,
        total: 0,
        selectionObj: [],
        selectionIds: []
      }
    }
  }
}
