<template>
  <div class="region-manage">
    <Index v-if="type === 'index'" @changeComponents="changeComponents"></Index>
    <Add v-if="type === 'add'" @changeComponents="changeComponents"></Add>
    <Edit v-if="type === 'edit'" @changeComponents="changeComponents"></Edit>
  </div>
</template>

<script>
  import Index from './region-manage-index.vue'
  import Add from './region-manage-add.vue'
  import Edit from './region-manage-edit.vue'
  
  export default {
    name: 'region-manage',
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
