<script setup>
// defineProps({
//   msg: {
//     type: String,
//     required: true
//   }
// })
</script>

<script>
export default {
  name: 'HelloWorld',
  data() {
    return {
      options: {
        chart: {
          id: 'vuechart-example'
        },
        xaxis: {
          categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998]
        }
      },
      series: [{
        name: 'series-1',
        data: [30, 40, 45, 50, 49, 60, 70, 91]
      }]
    }
  },
  mounted() {
    console.log(`the component is now mounted.`);
    let url = 'http://localhost:3000/readings?start=2022-10-10&end=2022-10-17';
    this.axios.get(url).then((response) => {
        console.log(response.data)
      });
  }
}
</script>

<template>
  <div class="greetings">
    <apexchart width="1024" type="line" :options="options" :series="series"></apexchart>
  </div>
</template>

<style scoped>
h1 {
  font-weight: 500;
  font-size: 2.6rem;
  top: -10px;
}

h3 {
  font-size: 1.2rem;
}

.greetings h1,
.greetings h3 {
  text-align: center;
}

@media (min-width: 1024px) {
  .greetings h1,
  .greetings h3 {
    text-align: left;
  }
}
</style>
