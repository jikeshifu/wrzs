<template>
  <div class="goods-manage-index">
    <el-button type="primary" icon="el-icon-plus" @click="addSP">添加商品</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionObj.length !== 1"
      @click="editSP(Mix_tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
      @click="delSP(Mix_tableData.selectionIds)">批量删除</el-button>
    <el-select v-model="tableData.type_id" placeholder="商品分类筛选" clearable filterable>
      <el-option v-for="item in goodsTypeList" :key="item.type_id" :label="item.type_name" :value="item.type_id">
      </el-option>
    </el-select>
    <el-input style="width:200px;" v-model="tableData.goods_name" placeholder="请输入商品名称" maxlength="20" clearable></el-input>
    <el-button type="primary" icon="el-icon-search" @click="filterGoods">查询</el-button>
    <el-button type="primary" icon="el-icon-refresh" @click="resetSearch">重置</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'goods_id')">
      <el-table-column type="selection"></el-table-column>
      <el-table-column label="ID" prop="goods_id" width="80"></el-table-column>
      <el-table-column label="商品主图" width="150">
        <template slot-scope="scope">
          <el-image :src="baseUrl + scope.row.goods_image" style="width:100%;height:150px;" fit="cover"
            :preview-src-list="[baseUrl + scope.row.goods_image]"></el-image>
        </template>
      </el-table-column>
      <el-table-column label="商品名称" prop="goods_name"></el-table-column>
      <el-table-column label="商品价格(元)" prop="goods_price"></el-table-column>
      <el-table-column label="排序" prop="sort">
        <template slot-scope="scope">
          <el-input maxlength="10" v-model="scope.row.sort"
            @input="scope.row.sort = +(scope.row.sort.replace(/[^\d]/g, ''))">
          </el-input>
        </template>
      </el-table-column>
      <el-table-column label="状态" prop="status">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="0" @change="SPstatusChange(scope.row)">
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column label="已售" prop="goods_sold"></el-table-column>
      <el-table-column label="库存" prop="goods_stock"></el-table-column>
      <el-table-column label="分类" prop="type_name">
        <template slot-scope="scope">
          <el-tag>{{ scope.row.type_name }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="操作" width="200" align="center">
        <template slot-scope="scope">
          <el-button type="warning" icon="el-icon-edit" size="mini" @click="editSP(scope.row)">编辑</el-button>
          <el-button type="danger" icon="el-icon-delete" size="mini" @click="delSP([scope.row.goods_id])">删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getSPList"></Pagination>
  </div>
</template>

<script>
  import {
    getSPList,
    delSP,
    editSP
  } from '@/api/shopping-mall-manage/goods-manage.js'
  import {
    getSPTypeList
  } from '@/api/shopping-mall-manage/goods-type-manage.js'

  export default {
    name: 'goods-manage-index',
    data() {
      return {
        goodsTypeList: [],
        tableData: {
          goods_name: '',
          type_id: ''
        }
      }
    },
    methods: {
      // 状态修改
      async SPstatusChange(data) {
        await editSP({
          goods_id: data.goods_id,
          status: data.status
        }).then(() => {
          this.$message.success('修改成功')
          this.getSPList()
        })
      },
      // 重置数据
      resetSearch() {
        this.tableData.type_id = ''
        this.tableData.goods_name = ''
      },
      // 商品分类查询
      filterGoods() {
        this.Mix_tableData.currentPage = 1
        this.getSPList()
      },
      // 添加商品
      addSP() {
        this.$emit('changeComponents', 'add')
      },
      // 编辑商品
      async editSP(data) {
        await this.$store.dispatch('shopping-mall-manage/goods-manage/setGoodsData', data)
        this.$emit('changeComponents', 'edit')
      },
      // 删除商品
      delSP(goods_id) {
        this.$confirm('您确定要删除该商品吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await delSP({
            goods_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getSPList()
          })
        }).catch(() => {})
      },
      // 获取商品列表
      async getSPList() {
        await getSPList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          goods_name: this.tableData.goods_name,
          type_id: this.tableData.type_id
        }).then((data) => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      },
      // 获取商品分类列表
      async getSPTypeList() {
        await getSPTypeList().then((data) => {
          this.goodsTypeList = data.list
        })
      }
    },
    mounted() {
      this.getSPList()
      this.getSPTypeList()
    }
  }
</script>

<style lang="scss" scoped>

</style>
