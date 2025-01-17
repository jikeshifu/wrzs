<template>
  <div class="cz-package-manage">
    <el-button type="primary" icon="el-icon-plus" @click="addPackage">添加套餐</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionObj.length !== 1"
      @click="editPackage(Mix_tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
      @click="delPackage(Mix_tableData.selectionIds)">批量删除</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'package_id')">
      <el-table-column type="selection"></el-table-column>
      <el-table-column prop="package_id" label="ID"></el-table-column>
      <el-table-column prop="title" label="标题"></el-table-column>
      <el-table-column prop="price" label="充值金额"></el-table-column>
      <el-table-column prop="profit" label="赠送金额"></el-table-column>
      <el-table-column label="创建时间">
        <template slot-scope="scope">{{ $dateFormatter(+(scope.row.created_at + '000'), 'yy-mm-dd hh:mm:ss') }}</template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button type="warning" size="mini" icon="el-icon-edit" @click="editPackage(scope.row)">编辑</el-button>
          <el-button type="danger" size="mini" icon="el-icon-delete" @click="delPackage([scope.row.package_id])">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getCzPackageList"></Pagination>
    <el-dialog
      :title="dialog.title"
      :show-close="false"
      :visible.sync="dialog.show"
      width="30%">
      <el-form ref="form" :model="dialog.form" :rules="dialog.formRules" label-width="120px">
        <el-form-item prop="title" label="套餐标题">
          <el-input placeholder="请输入套餐标题" v-model.trim="dialog.form.title" maxlength="30"></el-input>
        </el-form-item>
        <el-form-item prop="price" label="充值金额">
          <el-input-number v-model="dialog.form.price" :min="0" :max="500000" :precision="0"></el-input-number>
        </el-form-item>
        <el-form-item prop="profit" label="赠送金额">
          <el-input-number v-model="dialog.form.profit" :min="0" :max="500000" :precision="0"></el-input-number>
        </el-form-item>
        <el-form-item prop="status" label="状态">
          <el-radio v-model="dialog.form.status" :label="0">关闭</el-radio>
            <el-radio v-model="dialog.form.status" :label="1">启用</el-radio>
        </el-form-item>
      </el-form>
      <span slot="footer">
        <el-button @click="closeDialog">取消</el-button>
        <el-button type="primary" @click="submit">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { getCzPackageList, addPackage, editPackage, delPackage } from '@/api/discount-manage/cz-package-manage.js'

  export default {
    name: 'cz-package-manage-index',
    data() {
      return {
        dialog: {
          show: false,
          title: '',
          form: {
            package_id: '',
            title: '',
            price: '',
            profit: '',
            status: 1
          },
          formRules: {
            title: [{
              required: true,
              message: '请输入套餐标题'
            }],
            price: [{
              required: true,
              message: '请输入需要充值的金额'
            }],
            profit: [{
              required: true,
              message: '请输入赠送的金额'
            }]
          }
        }
      }
    },
    methods: {
      // 提交
      submit() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            if (this.dialog.title === '添加套餐') {
              await addPackage(this.dialog.form).then(() => {
                this.$message.success('添加成功')
                this.closeDialog()
                this.getCzPackageList()
              })
            }
            if (this.dialog.title === '修改套餐') {
              await editPackage(this.dialog.form).then(() => {
                this.$message.success('修改成功')
                this.closeDialog()
                this.getCzPackageList()
              })
            }
          } else {
            return false
          }
        })
      },
      // 关闭弹窗
      closeDialog() {
        this.dialog.form.title = ''
        this.dialog.form.price = ''
        this.dialog.form.profit = ''
        this.dialog.package_id = ''
        this.$refs['form'].resetFields()
        this.dialog.show = false
      },
      // 添加套餐
      addPackage() {
        this.dialog.title = '添加套餐'
        this.dialog.show = true
      },
      // 编辑套餐
      editPackage(data) {
        this.dialog.title = '修改套餐'
        this.dialog.title = data.title
        this.dialog.price = data.price
        this.dialog.profit = data.profit
        this.dialog.package_id = data.package_id
        this.dialog.show = true
      },
      // 删除套餐
      delPackage(package_id) {
        this.$confirm('您确定要删除该套餐吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await delPackage({
            package_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getCzPackageList()
          })
        }).catch(() => {})
      },
      // 获取套餐列表
      async getCzPackageList() {
        await getCzPackageList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      }
    },
    mounted() {
      this.getCzPackageList()
    }
  }
</script>

<style lang="scss" scoped>

</style>
