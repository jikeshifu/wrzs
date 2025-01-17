<template>
  <div class="region-manage-index">
    <el-button type="primary" icon="el-icon-plus" @click="addRegion">添加区域</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionObj.length !== 1"
      @click="editRegion(Mix_tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
      @click="delRegion(Mix_tableData.selectionIds)">批量删除</el-button>
    <el-input v-model="tableData.public" placeholder="区域名称" maxlength="50" clearable style="width:200px"
      @clear="searchSubmit" @keypress.native.enter="searchSubmit"></el-input>
    <el-button type="primary" icon="el-icon-search" @click="searchSubmit">查询</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'city_id')">
      <el-table-column type="selection"></el-table-column>
      <el-table-column label="ID" prop="city_id"></el-table-column>
      <el-table-column prop="city_name" label="区域名称"></el-table-column>
      <el-table-column prop="longitude" label="经度"></el-table-column>
      <el-table-column prop="latitude" label="纬度"></el-table-column>
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button type="warning" size="mini" icon="el-icon-edit" @click="editRegion(scope.row)">编辑</el-button>
          <el-button type="danger" size="mini" icon="el-icon-delete" @click="delRegion([scope.row.city_id])">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getRegionList"></Pagination>
  </div>
</template>

<script>
  import { getRegionList, delRegion } from '@/api/region-manage.js'

  export default {
    name: 'region-manage-index',
    data() {
      return {
        tableData: {
          public: ''
        }
      }
    },
    methods: {
      // 添加区域
      addRegion() {
        this.$emit('changeComponents', 'add')
      },
      // 编辑区域
      async editRegion(data) {
        await this.$store.dispatch('region-manage/setRegionData', data)
        this.$emit('changeComponents', 'edit')
      },
      // 删除区域
      delRegion(city_id) {
        this.$confirm('您确定要删除该区域吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await delRegion({
            city_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getRegionList()
          })
        }).catch(() => {})
      },
      // 查找
      searchSubmit() {
        this.Mix_tableData.currentPage = 1
        this.getRegionList()
      },
      // 获取区域列表
      async getRegionList() {
        await getRegionList({
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
      this.getRegionList()
    }
  }
</script>

<style lang="scss" scoped>

</style>
