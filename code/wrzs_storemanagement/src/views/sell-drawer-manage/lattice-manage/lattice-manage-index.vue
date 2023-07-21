<template>
  <div class="lattice-manage-index">
    <el-button @click="back" icon="el-icon-back">返回售货柜列表</el-button>
    <el-button type="primary" icon="el-icon-circle-plus-outline" @click="addLattice">添加商品格</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionIds.length !== 1"
               @click="editLattice(Mix_tableData.selectionObj[0])">编辑
    </el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
               @click="delLattice(Mix_tableData.selectionIds)">批量删除
    </el-button>
    <el-input v-model="tableData.public" placeholder="商品格标题" style="width:200px" maxlength="20" @clear="searchSubmit" @keypress.native.enter="searchSubmit" clearable></el-input>
    <el-button type="primary" icon="el-icon-search" @click="searchSubmit">查询</el-button>
    <el-tag type="primary">当前售货柜：{{ sellDrawerManage.cabinet_name }}</el-tag>
    <el-table ref="table" class="mt15" border height="calc(100vh - 225px)" :data="Mix_tableData.list"
              @selection-change="$selectionChange($event, 'goods_id')">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column label="编号" prop="cabinet_number" width="100"></el-table-column>
      <el-table-column label="主图" width="150">
        <template slot-scope="scope">
          <el-image :src="baseUrl + scope.row.goods_image" fit="cover" alt="" :preview-src-list="[baseUrl + scope.row.goods_image]"></el-image>
        </template>
      </el-table-column>
      <el-table-column label="标题" prop="goods_name"></el-table-column>
      <el-table-column label="库存" prop="stock"></el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="0"
                     @change="changeStatus(scope.row)"></el-switch>
        </template>
      </el-table-column>
      <el-table-column label="价格">
        <template slot-scope="scope">
          {{ scope.row.goods_price }}元
        </template>
      </el-table-column>
      <el-table-column label="操作" type="action" width="200" align="center">
        <template slot-scope="scope">
          <el-button type="warning" size="mini" icon="el-icon-edit" @click="editLattice(scope.row)">编辑</el-button>
          <el-button type="danger" size="mini" icon="el-icon-delete" @click="delLattice([scope.row.goods_id])">删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getLatticeList"></Pagination>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import {getLatticeList, editLatticeStatus, delLattice} from "@/api/sell-drawer-manage/lattice-manage"

export default {
  name: "lattice-manage-index",
  computed: {
    ...mapState({
      sellDrawerManage: state => state['sell-drawer-manage/sell-drawer-manage'].sellDrawerManage
    })
  },
  data() {
    return {
      tableData: {
        public: ''
      }
    }
  },
  methods: {
    // 返回
    back() {
      this.$emit('back', 'index')
    },
    // 编辑商品格
    async editLattice(data) {
      await this.$store.dispatch('sell-drawer-manage/lattice-manage/setLatticeManageData', data)
      this.$emit('componentsChange', 'edit')
    },
    // 查询
    searchSubmit() {
      this.Mix_tableData.currentPage = 1
      this.getLatticeList()
    },
    // 添加商品格
    addLattice() {
      this.$emit('componentsChange', 'add')
    },
    // 删除商品格
    delLattice(goods_id) {
      this.$confirm('您确定要删除该商品格吗?', '温馨提示', {
        type: 'warning'
      }).then(async () => {
        await delLattice({
          goods_id
        }).then(() => {
          this.$message.success('删除成功')
          this.Mix_tableData.currentPage = 1
          this.getLatticeList()
        })
      }).catch(() => {})
    },
    // 改变商品格状态
    async changeStatus(row) {
      await editLatticeStatus({
        goods_id: row.goods_id,
        status: row.status
      }).then(() => {
        this.$message.success('修改成功')
        this.getLatticeList()
      })
    },
    // 获取格子列表
    async getLatticeList() {
      await getLatticeList({
        page: this.Mix_tableData.currentPage,
        limit: this.Mix_tableData.pageSize,
        public: this.tableData.public,
        cabinet_id: this.sellDrawerManage.cabinet_id
      }).then((data) => {
        this.Mix_tableData.list = data.list
        this.Mix_tableData.total = data.count
      })
    }
  },
  mounted() {
    this.getLatticeList()
  }
}
</script>

<style scoped>

</style>
