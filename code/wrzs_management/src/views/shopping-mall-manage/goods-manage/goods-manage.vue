<template>
  <div class="goods-manage">
    <Index v-if="type === 'index'" @changeComponents="changeComponents"></Index>
    <Add v-if="type === 'add'" @changeComponents="changeComponents"></Add>
    <Edit v-if="type === 'edit'" @changeComponents="changeComponents"></Edit>
  </div>
</template>

<script>
  import Index from './goods-manage-index.vue'
  import Add from './goods-manage-add.vue'
  import Edit from './goods-manage-edit.vue'
  
  export default {
    name: 'goods-manage',
    components: {
      Index,
      Add,
      Edit
    },
    data() {
      return {
        type: 'index'
      }
    },
    methods: {
      // 改变组件
      changeComponents(e) {
        this.type = e
      }
    }
  }
</script>

<style lang="scss" scoped>

</style>
