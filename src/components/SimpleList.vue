<template>
<form >
  <head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Examination Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
  
<div class="container mx-auto">
 <h1 class="col-md-10"  align="center"> Student Examination Results</h1>
</div>

 <div class="container">
   <label for="search">
    Search
    <input  type="number" v-model="search" placeholder="Matriculation Number"/>
  </label>
    
   
    
   
   <div v-for="data in filtereddata" :key="data.Matriculation_id"></div>
    <!-- Show Add Student Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        <FIcons :icon="['fas', 'user']" /> Show Results
      </button>
    </div>
   
  </div>

      
<table class="table table-bordered">
        <thead>
            <tr>
                
                <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">question number</td>
                <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">question </td>
                <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">correct answer</td>
                <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">chosen answer</td>
                <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">score</td>
                <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">maximum score</td>
                <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">total score</td>
                <td>action</td>
            </tr>

        </thead>
        <tbody>
            <tr v-for="data in data" :key="data.Matriculation_id">
            <td v-if="editting=== Matriculation_id">
            <input type="text" v-model="data.question_number"/>
            </td>
           
            <td v-else>{{data.question_number}}</td>
            <td v-if="editting=== Matriculation_id">
            <input type="text" v-model="data.question"/>
            </td>
            <td v-else>{{data.question}}</td>
            <td v-if="editting=== Matriculation_id">
            <input type="text" v-model="data.chosen_answer"/>
            </td>
            <td v-else>{{data.correct_answer}}</td>
            <td v-if="editting=== Matriculation_id">
            <input type="text" v-model="Matriculation_id"/>
            </td>
            <td v-else>{{data.chosen_answer}}</td>
            <td v-if="editting=== Matriculation_id">
            <input type="number" v-model="data.score"/>
            </td>
            <td v-else>{{data.score}}</td>
            <td v-if="editting=== Matriculation_id">
            <input type="number" v-model="data.maximum_score"/>
            </td>
            <td v-else>{{data.maximum_score}}</td>
            <td v-if="editting=== Matriculation_id">
            <input type="number" v-model="data.total_score"/>
            </td>
            <td class="table-active">{{data.total_score}}</td>
            <td v-if="editting=== Matriculation_id">
              <button>save</button>
              <button @click="editting = null">cancel</button>

            </td>
            <td v-else>
              <button @click="editMode(Matriculation_id)">edit</button>
              <button @click="$emit('del:data, Matriculation_id')">delete</button>

            </td>
            </tr>
            
        </tbody>
          </table>
           </body>
           </form>
           
</template>

<script>
export default {
    name: "simple-list",
    data() {
    return {
      search: "",
      data: [],
      editting: null
    };
  },
  methods: {
    editMode(Matriculation_id){
      this.editting= Matriculation_id;
    }
  },
  created() {
    fetch(" http://localhost:3000/data")
      .then(response => {
        return response.json();
      })
      .then(data => {
        this.data = data;
      });
  },
  computed: {
    filtereddata: function() {
      return this.data.filter((data) => {
        return data.Matriculation_id.match(this.search)
    })
      
      },
        
      
  
    }
  
}

</script>

<style>
form{
    max-width: 420px auto;
    margin: 30px auto;
    background: lightblue;
    text-align: left;
    padding: 40px;
    border-radius: 10px;
}
label {
    color: #000;
    display: inline-block;
    margin: 25px 0 15px;
    font-size: 0.6em;
    text-transform:none;
    letter-spacing: 1px;
    font-weight: bold;
}
input {
    display: block;
    padding: 10px 6px;
    width: 100%;
    box-sizing: border-box;
    border: none;
    border-bottom: 1px solid #ddd;
    color: #555;
}
</style>