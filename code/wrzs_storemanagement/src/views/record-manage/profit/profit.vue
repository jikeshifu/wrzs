<template>
  <div class="profit">
    <Index v-if="type === 'index'" @componentsChange="componentsChange"></Index>
  </div>
</template>

<script>
import Index from './profit-index'

export default {
  name: "profit",
  components: {
    Index
  },
  data() {
    return {
      type: 'index'
    }
  },
  methods: {
    // 组件改变
    componentsChange(e) {
      this.type = e
    }
  }
}
</script>

<style scoped>

</style>
