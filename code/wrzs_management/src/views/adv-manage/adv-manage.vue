<template>
  <div class="adv-manage">
    <Index v-if="type === 'index'" @changeComponents="changeComponents"></Index>
    <Add v-if="type === 'add'" @changeComponents="changeComponents"></Add>
    <Edit v-if="type === 'edit'" @changeComponents="changeComponents"></Edit>
  </div>
</template>

<script>
  import Index from './adv-manage-index.vue'
  import Add from './adv-manage-add.vue'
  import Edit from './adv-manage-edit.vue'
  
  export default {
    name: 'adv-manage',
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
