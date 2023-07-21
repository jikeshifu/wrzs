<template>
  <div class="goods-manage-edit">
    <el-row>
      <el-col>
        <el-form ref="form" :model="form" :rules="formRules" label-width="120px">
          <el-form-item label="商品主图" prop="goods_image">
            <el-upload :action="uploadURL" :before-upload="beforeUpload" list-type="picture-card" :limit="1" :on-remove="removeUpload"
              :on-success="uploadSuccess" :file-list="fileList">
              <i class="el-icon-plus"></i>
            </el-upload>
            <el-tag type="warning">仅限上传一张，尺寸为1065*600</el-tag>
          </el-form-item>
          <el-form-item prop="goods_name" label="商品名称">
            <el-input v-model.trim="form.goods_name" placeholder="请输入商品名称" maxlength="50"></el-input>
          </el-form-item>
          <el-form-item prop="shipping_remarks" label="发货备注">
            <el-input v-model.trim="form.shipping_remarks" placeholder="发货备注"></el-input>
          </el-form-item>
          <el-form-item prop="goods_price" label="商品价格">
            <el-input-number v-model="form.goods_price" :min="0" :max="500000" :precision="2"></el-input-number>
          </el-form-item>
          <el-form-item prop="goods_stock" label="库存">
            <el-input-number v-model="form.goods_stock" :min="0" :max="500000" :precision="0"></el-input-number>
          </el-form-item>
          <el-form-item prop="goods_sold" label="已售数量">
            <el-input-number v-model="form.goods_sold" :min="0" :max="500000" :precision="0"></el-input-number>
          </el-form-item>
          <el-form-item prop="type_id" label="商品分类">
            <el-select v-model="form.type_id" placeholder="请选择">
              <el-option v-for="item in spTypeArr" :key="item.type_id" :label="item.type_name" :value="item.type_id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="sort" label="排序">
            <el-input-number v-model="form.sort" :min="0" :max="500000" :precision="0"></el-input-number>
          </el-form-item>
          <el-form-item prop="recommend_status" label="推荐">
            <el-radio v-model="form.recommend_status" :label="0">否</el-radio>
            <el-radio v-model="form.recommend_status" :label="1">是</el-radio>
          </el-form-item>
          <el-form-item prop="status" label="状态">
            <el-radio v-model="form.status" :label="0">禁用</el-radio>
            <el-radio v-model="form.status" :label="1">开启</el-radio>
          </el-form-item>
          <el-form-item prop="goods_about" label="商品介绍">
            <tinymce ref="tinymce" v-model="form.goods_about" :height="300" />
          </el-form-item>
          <el-form-item>
            <el-button icon="el-icon-back" @click="back">返回</el-button>
            <el-button type="primary" icon="el-icon-check"
              @click="submit">确定修改</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import {
    editSP
  } from '@/api/shopping-mall-manage/goods-manage.js'
  import {
    getSPTypeList
  } from '@/api/shopping-mall-manage/goods-type-manage.js'
  import Tinymce from '@/components/Tinymce'

  export default {
    name: 'goods-manage-eidt',
    components: {
      Tinymce
    },
    computed: {
      ...mapState({
        gooodsData: state => state['shopping-mall-manage/goods-manage'].goodsData
      })
    },
    data() {
      return {
        spTypeArr: [],
        form: {},
        fileList: [],
        formRules: {
          goods_image: [{
            required: true,
            message: '请上传商品主图'
          }],
          goods_name: [{
            required: true,
            message: '请输入商品名称'
          }],
          type_id: [{
            required: true,
            message: '请选择商品类型'
          }]
        }
      }
    },
    methods: {
      beforeUpload(file) { // 文件上传前钩子
        const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png' //图片格式是否为png或jpg
        const isLt10M = file.size / 1024 / 1024 < 10 //判断图片大小是否超过10MB
        const file_size = file.size / 1024 / 1024 //图片大小
        if (!isJpgOrPng) {
          this.$message.error('文件格式错误！')
        } else if (!isLt10M) {
          this.$message.error('文件不能超过10M！')
        } else {
          const _this = this
          return new Promise(resolve => {
            const reader = new FileReader()
            const image = new Image()
            image.onload = (imageEvent) => {
              const canvas = document.createElement('canvas');
              const context = canvas.getContext('2d');
              const originWidth = image.width
              const originHeight = image.height
              // 最大尺寸限制，可通过设置宽高来实现图片压缩程度
              let maxWidth = 800, maxHeight = 800
              // 目标尺寸
              let targetWidth = originWidth, targetHeight = originHeight
              // 图片尺寸超过800x800的限制
              if (originWidth > maxWidth || originHeight > maxHeight) {
                if (originWidth / originHeight > maxWidth / maxHeight) {
                  // 更宽，按照宽度限定尺寸
                  targetWidth = maxWidth;
                  targetHeight = Math.round(maxWidth * (originHeight / originWidth));
                } else {
                  targetHeight = maxHeight;
                  targetWidth = Math.round(maxHeight * (originWidth / originHeight));
                }
              }
              canvas.width = targetWidth
              canvas.height = targetHeight
              context.clearRect(0, 0, targetWidth, targetHeight)
              context.drawImage(image, 0, 0, targetWidth, targetHeight)
              let scale = 1;
              if (file_size < 1 && file_size >0.3) {
                scale = 0.9
              }
              const dataUrl = canvas.toDataURL('image/jpeg',scale)
              const blobData = _this.dataURItoBlob(dataUrl, 'image/jpeg')
              console.log(file.size / 1024)
              console.log(blobData.size / 1024)
              resolve(blobData)
            }
            reader.onload = (e => {
              image.src = e.target.result;
            })
            reader.readAsDataURL(file)
          })
        }
        return isJpgOrPng && isLt10M
      },
      dataURItoBlob(dataURI, type) {
        var binary = atob(dataURI.split(',')[1]);
        var array = [];
        for(var i = 0; i < binary.length; i++) {
          array.push(binary.charCodeAt(i));
        }
        return new Blob([new Uint8Array(array)], {type: type});
      },

      // 上传图片成功的钩子
      uploadSuccess(res) {
        this.form.goods_image = res.data
      },
      // 删除上传门店图片
      removeUpload() {
        this.form.goods_image = ''
      },
      back() {
        this.$emit('changeComponents', 'index')
      },
      submit() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            await editSP(this.form).then(() => {
              this.$message.success('修改成功')
              this.back()
            })
          } else {
            return false
          }
        })
      },
      // 获取商品分类列表
      async getSPTypeList() {
        await getSPTypeList().then((data) => {
          this.spTypeArr = data.list
        })
      },
      // 初始化图片
      initFileList() {
        this.fileList = [{
          name: '',
          url: this.baseUrl + this.form.goods_image
        }]
      }
    },
    mounted() {
      this.getSPTypeList()
      this.form = this.gooodsData
      this.initFileList()
    }
  }
</script>

<style lang="scss" scoped>

</style>
