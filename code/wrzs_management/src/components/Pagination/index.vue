<template>
  <el-pagination class="mt15 text-right" @size-change="sizeChange" @current-change="currentChange"
    :current-page="tableData.currentPage" :page-sizes="[10, 20, 30, 40]" :page-size="tableData.pageSize"
    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total">
  </el-pagination>
</template>

<script>
  export default {
    props: {
      tableData: {
        type: Object,
        default: _ => {
          return {}
        }
      },
      getList: {
        type: Function,
        default: _ => {
          return () => {}
        }
      }
    },
    data() {
      return {
        page: this.tableData.currentPage,
        limit: this.tableData.pageSize
      }
    },
    methods: {
      // 传给父组件
      commitParent() {
        const data = {
          limit: this.limit,
          page: this.page,
          getList: this.getList
        }
        this.$emit('pageChange', data)
      },
      // 分页大小设置
      sizeChange(val) {
        this.limit = val
        this.commitParent()
      },
      // 当前分页更改
      currentChange(val) {
        this.page = val
        this.commitParent()
      }
    }
  }
</script>
