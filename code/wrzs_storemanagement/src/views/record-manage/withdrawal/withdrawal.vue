<template>
  <div class="withdrawal">
    <Index v-if="type === 'index'" @componentsChange="componentsChange"></Index>
  </div>
</template>

<script>
import Index from './withdrawal-index'

export default {
  name: "withdrawal",
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
