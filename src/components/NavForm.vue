<template>
<header>
    <h3>{{title}}</h3>
    </header>
</template>

<script>
    export  default ({
        name: 'NavForm',
        data(){
          return{
            title: 'Group Three @ 2022'
          }
        }
    })
</script>
<style scoped>
header {
  background-color:darkgray;
  padding:10px;
  height:50px;
  text-align:center;
  color:white
}
</style>