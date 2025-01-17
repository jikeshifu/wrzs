<template>
  <div class="apply-manage-index">
    <el-input v-model="tableData.public" placeholder="申请人姓名/手机号" maxlength="50" clearable style="width:200px"
      @clear="searchSubmit" @keypress.native.enter="searchSubmit"></el-input>
    <el-button type="primary" icon="el-icon-search" @click="searchSubmit">查询</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)">
      <el-table-column prop="id" label="ID"></el-table-column>
      <el-table-column prop="name" label="申请人姓名"></el-table-column>
      <el-table-column prop="city" label="开通城市"></el-table-column>
      <el-table-column prop="mobile" label="申请人电话"></el-table-column>
      <el-table-column prop="remarks" label="申请留言"></el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-tag type="success" v-if="scope.row.status">已联系</el-tag>
          <el-tag type="danger" v-else>还未联系</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button type="primary" @click="changeStatus(scope.row.id)" size="mini" v-if="!scope.row.status">我已联系</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getJoinApplyList"></Pagination>
  </div>
</template>

<script>
  import { getJoinApplyList, applyStatusChange } from '@/api/joinin-manage.js'

  export default {
    name: 'apply-manage-index',
    data() {
      return {
        tableData: {
          public: ''
        }
      }
    },
    methods: {
      // 我已联系操作
      async changeStatus(id) {
        await applyStatusChange({ id }).then(() => {
          this.$message.success('操作成功')
          this.getJoinApplyList()
        })
      },
      searchSubmit() {
        this.Mix_tableData.currentPage = 1
        this.getJoinApplyList()
      },
      async getJoinApplyList() {
        await getJoinApplyList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          public: this.tableData.public
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      }
    },
    mounted() {
      this.getJoinApplyList()
    }
  }
</script>

<style lang="scss">

</style>
