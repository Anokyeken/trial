<template>
  <div class="mx-auto p-6 bg-gray-200">
    <simple-list />
    <NavForm />
  </div>
</template>

<script>
import SimpleList from "@/components/SimpleList";
import NavForm from './components/NavForm.vue'

export default {
  components: {
    SimpleList,
    NavForm,
   
  }
};
</script>
<style>
body{
padding: 0;
margin: 0;
}
</style>
