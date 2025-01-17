<template>
  <div class="coupon-manage">
    <Index v-if="type === 'index'" @changeComponents="changeComponents"></Index>
  </div>
</template>

<script>
  import Index from './coupon-manage-index.vue'
  
  export default {
    name: 'coupon-manage',
    components: {
      Index
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
