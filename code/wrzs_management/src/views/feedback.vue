<template>
  <div class="feedback">
    <el-table ref="table" :data="Mix_tableData.list" border height="calc(100vh - 170px)">
      <el-table-column prop="id" label="ID" width="80" align="center"></el-table-column>
      <el-table-column label="头像" width="120" align="center">
        <template slot-scope="scope">
          <el-avatar :size="60" :src="scope.row.member.avatar_url"></el-avatar>
        </template>
      </el-table-column>
      <el-table-column prop="member.nick_name" label="反馈用户名称"></el-table-column>
      <el-table-column prop="member.mobile" label="联系电话"></el-table-column>
      <el-table-column prop="member.province" label="地区"></el-table-column>
      <el-table-column prop="content" label="反馈内容"></el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-tag type="success" v-if="scope.row.status">已处理</el-tag>
          <el-tag type="danger" v-else>未处理</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button v-if="!scope.row.status" type="warning" icon="el-icon-edit" size="mini" @click="statusHandle([scope.row.id])">已处理
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :Mix_tableData="Mix_tableData" @pageChange="$pageChange" :getList="getFeedback"></Pagination>
  </div>
</template>

<script>
  import { getFeedback, statusHandle } from '@/api/feedback.js'
  export default {
    name: 'feedback',
    methods: {
      async getFeedback() {
        await getFeedback({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      },
      async statusHandle(id) {
        await statusHandle({ id }).then(() => {
          this.$message.success('操作成功')
          this.getFeedback()
        })
      }
    },
    mounted() {
      this.getFeedback()
    }
  }
</script>

<style lang="scss">

</style>
