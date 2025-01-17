<template>
  <div class="coupon-manage-index">
    <el-button type="primary" icon="el-icon-plus" @click="addCoupon">添加优惠券</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionObj.length !== 1"
      @click="editCoupon(Mix_tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
      @click="delCoupon(Mix_tableData.selectionIds)">批量删除</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'coupons_id')">
      <el-table-column type="selection"></el-table-column>
      <el-table-column prop="coupons_id" label="ID"></el-table-column>
      <el-table-column prop="title" label="标题"></el-table-column>
      <el-table-column prop="money" label="赠送金额"></el-table-column>
      <el-table-column label="赠送新用户">
        <template slot-scope="scope">
          <el-switch @change="changeNewStatus(scope.row)" v-model="scope.row.new_user_status" :active-value="1" :inactive-value="0">
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column label="创建时间">
        <template
          slot-scope="scope">{{ $dateFormatter(+(scope.row.created_at + '000'), 'yy-mm-dd hh:mm:ss') }}</template>
      </el-table-column>
      <el-table-column prop="day_number" label="有效期(天)"></el-table-column>
      <el-table-column label="操作" width="400" align="center">
        <template slot-scope="scope">
          <el-button type="success" size="mini" icon="el-icon-user-solid" @click="sendAllUserCoupon(scope.row)">全部赠送</el-button>
          <el-button type="primary" size="mini" icon="el-icon-user-solid" @click="sendCoupon(scope.row)">赠送个人</el-button>
          <el-button type="warning" size="mini" icon="el-icon-edit" @click="editCoupon(scope.row)">编辑</el-button>
          <el-button type="danger" size="mini" icon="el-icon-delete" @click="delCoupon([scope.row.coupons_id])">删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getCouponList"></Pagination>
    <el-dialog :title="dialog.title" :show-close="false" :visible.sync="dialog.show" width="30%">
      <el-form ref="form" :model="dialog.form" :rules="dialog.formRules" label-width="120px">
        <el-form-item prop="title" label="优惠券标题">
          <el-input placeholder="请输入套餐标题" v-model.trim="dialog.form.title" maxlength="30"></el-input>
        </el-form-item>
        <el-form-item prop="money" label="赠送金额">
          <el-input-number v-model="dialog.form.money" :min="0" :max="500000" :precision="0"></el-input-number>
        </el-form-item>
        <el-form-item prop="new_user_status" label="赠送新用户">
          <el-radio v-model="dialog.form.new_user_status" :label="0">不赠送</el-radio>
          <el-radio v-model="dialog.form.new_user_status" :label="1">赠送</el-radio>
        </el-form-item>
        <el-form-item prop="day_number" label="有效期">
          <el-input-number v-model="dialog.form.day_number" :min="1" :max="1000" :precision="0"></el-input-number>
          <el-tag>天</el-tag>
        </el-form-item>
      </el-form>
      <span slot="footer">
        <el-button @click="closeDialog">取消</el-button>
        <el-button type="primary" @click="submit">确定</el-button>
      </span>
    </el-dialog>
    <el-dialog title="赠送优惠券" :visible.sync="giveDialog.show" width="30%">
      <el-form ref="giveDialog" :model="giveDialog.form" :rules="giveDialog.formRules" label-width="90px">
        <el-form-item label="用户手机" prop="mobile">
          <el-input maxlength="11" v-model="giveDialog.form.mobile" placeholder="请输入要赠送的用户的手机号"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer">
        <el-button @click="hideGiveDialog">取消</el-button>
        <el-button type="primary" @click="submitGiveDialog">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import {
    getCouponList,
    addCoupon,
    editCoupon,
    delCoupon,
    sendCoupon,
    sendAllUserCoupon
  } from '@/api/discount-manage/coupon-manage.js'

  export default {
    name: 'coupon-manage-index',
    data() {
      const checkMobile = (rule, value, callback) => {
        if (!/^1[3456789]\d{9}$/.test(value)) {
          callback(new Error('请输入11位正确的手机号'))
        }
        callback()
      }
      return {
        dialog: {
          show: false,
          title: '',
          form: {
            coupons_id: '',
            title: '',
            money: '',
            new_user_status: 0,
            day_number: 1
          },
          formRules: {
            title: [{
              required: true,
              message: '请输入优惠券标题'
            }],
            money: [{
              required: true,
              message: '请输入赠送的金额'
            }],
            day_number: [{
              required: true,
              message: '请输入有效期限'
            }],
          }
        },
        // 赠送优惠券的弹窗
        giveDialog: {
          show: false,
          form: {
            mobile: '',
            coupons_id: ''
          },
          formRules: {
            mobile: [{
              required: true,
              message: '请输入要赠送的用户的手机号'
            }, {
              validator: checkMobile
            }]
          }
        }
      }
    },
    methods: {
      // 关闭赠送弹窗
      hideGiveDialog() {
        this.giveDialog.show = false
        this.$refs['giveDialog'].resetFields()
      },
      // 提交赠送弹窗
      submitGiveDialog() {
        this.$refs['giveDialog'].validate(async valid => {
          if (valid) {
            await sendCoupon(this.giveDialog.form).then(() => {
              this.$message.success('赠送成功')
              this.giveDialog.show = false
              this.giveDialog.form.mobile = ''
              this.$refs['giveDialog'].resetFields()
            })
          } else {
            return false
          }
        })
      },
      // 赠送全部用户优惠券
      sendAllUserCoupon(item) {
        this.$confirm('您确定要赠送所有用户该卡券吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await sendAllUserCoupon({
            coupons_id: item.coupons_id
          }).then(() => {
            this.$message.success('赠送成功')
          })
        }).catch(() => {})
      },
      // 赠送用户优惠券
      async sendCoupon(item) {
        this.giveDialog.form.coupons_id = item.coupons_id
        this.giveDialog.show = true
      },
      // 更改赠送新用户状态
      async changeNewStatus(item) {
        await editCoupon({
          coupons_id: item.coupons_id,
          new_user_status: item.new_user_status
        }).then(() => {
          this.$message.success('修改成功')
          this.getCouponList()
        })
      },
      // 提交
      submit() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            if (this.dialog.title === '添加优惠券') {
              await addCoupon(this.dialog.form).then(() => {
                this.$message.success('添加成功')
                this.closeDialog()
                this.getCouponList()
              })
            }
            if (this.dialog.title === '修改优惠券') {
              await editCoupon(this.dialog.form).then(() => {
                this.$message.success('修改成功')
                this.closeDialog()
                this.getCouponList()
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
        this.dialog.form.money = ''
        this.dialog.form.coupons_id = ''
        this.dialog.form.new_user_status = 0
        this.dialog.form.day_number = 1
        this.$refs['form'].resetFields()
        this.dialog.show = false
      },
      // 添加优惠券
      addCoupon() {
        this.dialog.title = '添加优惠券'
        this.dialog.show = true
      },
      // 编辑套餐
      editCoupon(data) {
        this.dialog.title = '修改优惠券'
        this.dialog.form.title = data.title
        this.dialog.form.money = data.money
        this.dialog.form.coupons_id = data.coupons_id
        this.dialog.form.new_user_status = data.new_user_status
        this.dialog.form.day_number = data.day_number
        this.dialog.show = true
      },
      // 删除优惠券
      delCoupon(coupons_id) {
        this.$confirm('您确定要删除该优惠券吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await delCoupon({
            coupons_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getCouponList()
          })
        }).catch(() => {})
      },
      // 获取优惠券列表
      async getCouponList() {
        await getCouponList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize
        }).then((data) => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      }
    },
    mounted() {
      this.getCouponList()
    }
  }
</script>

<style lang="scss" scoped>

</style>
