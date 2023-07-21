<template>
  <div class="joinin-manage">
    <Index v-if="type === 'index'" @changeComponents="changeComponents"></Index>
    <Add v-if="type === 'add'" @changeComponents="changeComponents"></Add>
    <Edit v-if="type === 'edit'" @changeComponents="changeComponents"></Edit>
    <Profit v-if="type === 'profit'" @changeComponents="changeComponents"></Profit>
    <Withdrawal v-if="type === 'withdrawal'" @changeComponents="changeComponents"></Withdrawal>
  </div>
</template>

<script>
  import Index from './joinin-manage-index.vue'
  import Add from './joinin-manage-add.vue'
  import Edit from './joinin-manage-edit.vue'
  import Profit from './profit/index.vue'
  import Withdrawal from './withdrawal/index.vue'
  
  export default {
    name: 'joinin-manage',
    components: {
      Index,
      Add,
      Edit,
      Profit,
      Withdrawal
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
