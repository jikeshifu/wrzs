<template>
  <div class="manJian-manage">
    <Index v-if="type === 'index'" @back="back" @componentsChange="componentsChange"></Index>
  </div>
</template>

<script>
  import Index from './manJian-manage-index.vue'

  export default {
    name: 'manJian-manage',
    components: {
      Index
    },
    data() {
      return {
        type: 'index'
      }
    },
    methods: {
      // 返回到门店列表
      back(e) {
        this.$emit('componentsChange', e)
      },
      // 组件改变
      componentsChange(e) {
        this.type = e
      }
    }
  }
</script>

<style lang="scss">

</style>
