export default {
  methods: {
    // 分页改变
    $pageChange(data) {
      this.Mix_tableData.pageSize = data.limit
      this.Mix_tableData.currentPage = data.page
      data.getList()
    },
    // table 选择框事件
    $selectionChange(arr, id) {
      this.Mix_tableData.selectionObj = arr
      this.Mix_tableData.selectionIds = arr.map(item => item[id])
    },
    // 查询
    $submitSearch(getList) {
      this.Mix_tableData.currentPage = 1
      getList()
    }
  }
}
