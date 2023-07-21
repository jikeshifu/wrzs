<template>
  <div class="goods-type-manage-index">
    <el-button type="primary" icon="el-icon-plus" @click="addSPType">添加分类</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionObj.length !== 1"
      @click="editSPType(Mix_tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
      @click="delSPType(Mix_tableData.selectionIds)">批量删除</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'type_id')">
      <el-table-column type="selection"></el-table-column>
      <el-table-column label="ID" prop="type_id"></el-table-column>
      <el-table-column label="分类名称" prop="type_name"></el-table-column>
      <el-table-column label="排序">
        <template slot-scope="scope">
          <el-input @blur="changeType(scope.row)" maxlength="10" v-model="scope.row.sort" @input="scope.row.sort = +(scope.row.sort.replace(/[^\d]/g, ''))">
          </el-input>
        </template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-switch @change="changeType(scope.row)" v-model="scope.row.status" :active-value="1" :inactive-value="0">
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button type="warning" icon="el-icon-edit" size="mini" @click="editSPType(scope.row)">编辑</el-button>
          <el-button type="danger" icon="el-icon-delete" size="mini" @click="delSPType([scope.row.type_id])">删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getSPTypeList"></Pagination>
    <el-dialog
      :title="dialog.title"
      :visible.sync="dialog.show"
      :show-close="false"
      width="30%">
      <el-form ref="form" :model="dialog.form" :rules="dialog.formRules" label-width="120px">
        <el-form-item label="分类名称" prop="type_name">
          <el-input placeholder="请输入商品分类名称" maxlength="10" v-model.trim="dialog.form.type_name"></el-input>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-radio v-model="dialog.form.status" :label="1">开启</el-radio>
          <el-radio v-model="dialog.form.status" :label="0">禁用</el-radio>
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input-number v-model="dialog.form.sort" :min="0" :max="500000" :precision="0"></el-input-number>
        </el-form-item>
      </el-form>
      <span slot="footer">
        <el-button @click="hideDialog">取消</el-button>
        <el-button type="primary" @click="submitDialog">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import {
    getSPTypeList,
    addSPType,
    editSPType,
    delSPType
  } from '@/api/shopping-mall-manage/goods-type-manage.js'

  export default {
    name: 'goods-type-manage-index',
    data() {
      return {
        dialog: {
          title: '',
          show: false,
          form: {
            type_name: '',
            status: 1,
            sort: 0,
            type_id: ''
          },
          formRules: {
            type_name: [{
              required: true,
              message: '请输入商品分类名称'
            }]
          }
        }
      }
    },
    methods: {
      // 改变商品分类字段
      async changeType(item) {
        await editSPType(item).then(() => {
          this.$message.success('修改成功')
          this.getSPTypeList()
        })
      },
      // 关闭弹窗
      hideDialog() {
        this.dialog.form.type_name = ''
        this.dialog.form.status = 1
        this.dialog.form.sort = 0
        this.dialog.form.type_id = ''
        this.dialog.show = false
        this.$refs['form'].resetFields()
      },
      // 提交弹窗
      submitDialog() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            if (this.dialog.title === '添加商品分类') {
              await addSPType(this.dialog.form).then(() => {
                this.$message.success('添加成功')
                this.hideDialog()
                this.Mix_tableData.currentPage = 1
                this.getSPTypeList()
              })
            }
            if (this.dialog.title === '编辑商品分类') {
              await editSPType(this.dialog.form).then(() => {
                this.$message.success('修改成功')
                this.hideDialog()
                this.Mix_tableData.currentPage = 1
                this.getSPTypeList()
              })
            }
          } else {
            return false
          }
        })
      },
      // 添加商品分类
      addSPType() {
        this.dialog.title = '添加商品分类'
        this.dialog.show = true
      },
      // 编辑商品分类
      editSPType(data) {
        this.dialog.title = '编辑商品分类'
        this.dialog.form.type_id = data.type_id
        this.dialog.form.type_name = data.type_name
        this.dialog.form.sort = data.sort
        this.dialog.form.status = data.status
        this.dialog.show = true
      },
      // 删除商品分类
      delSPType(type_id) {
        this.$confirm('您确定要删除该商品分类吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await delSPType({
            type_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getSPTypeList()
          })
        }).catch(() => {})
      },
      // 获取商品分类列表
      async getSPTypeList() {
        await getSPTypeList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize
        }).then((data) => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      }
    },
    mounted() {
      this.getSPTypeList()
    }
  }
</script>

<style lang="scss" scoped>

</style>
