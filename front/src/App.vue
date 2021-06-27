<template>
  <div id="app">
    <navbar></navbar>
    <dashboard></dashboard>
    <vue-confirm-dialog/>
  </div>
</template>

<script>
import Dashboard from './components/Dashboard.vue'
import Navbar from "@/components/Navbar";
export default {
  name: 'App',
  components: {
    Navbar,
    Dashboard
  }
}
</script>

<style>
</style>
