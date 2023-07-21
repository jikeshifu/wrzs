<template>
  <div class="adv-manage-index">
    <el-button type="primary" icon="el-icon-plus" @click="addAdv">添加广告位</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'ad_id')">
      <el-table-column type="selection"></el-table-column>
      <el-table-column prop="ad_id" label="ID"></el-table-column>
      <el-table-column label="图片" width="300" align="center">
        <template slot-scope="scope">
          <el-image style="width:260px;height:70px" :src="baseUrl + scope.row.src" fit="cover" :preview-src-list="[baseUrl + scope.row.src]"></el-image>
        </template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="0" @change="statusChange(scope.row)">
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column type="action" label="操作" align="center">
        <template slot-scope="scope">
          <el-button type="warning" size="mini" icon="el-icon-edit" @click="editAdv(scope.row)">编辑</el-button>
          <el-button type="danger" size="mini" icon="el-icon-delete" @click="delAdv([scope.row.ad_id])">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getAdvList"></Pagination>
  </div>
</template>

<script>
  import {
    getAdvList,
    delAdv,
    editAdv
  } from '@/api/adv-manage.js'

  export default {
    data() {
      return {

      }
    },
    methods: {
      // 添加广告
      addAdv() {
        this.$emit('changeComponents', 'add')
      },
      // 编辑广告
      editAdv(data) {
        this.$store.commit('adv/setAdvData', data)
        this.$emit('changeComponents', 'edit')
      },
      // 删除广告位
      delAdv(ad_id) {
        this.$confirm('您确定要删除该广告位吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await delAdv({
            ad_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getAdvList()
          })
        }).catch(() => {})
      },
      // 状态修改
      async statusChange(item) {
        await editAdv({
          ad_id: item.ad_id,
          status: item.status
        }).then(() => {
          this.$message.success('修改成功')
          this.getAdvList()
        })
      },
      // 获取广告位列表
      async getAdvList() {
        await getAdvList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      }
    },
    mounted() {
      this.getAdvList()
    }
  }
</script>

<style scoped>
  .el-upload__tip {
    line-height: normal;
  }
</style>
