<script setup>
// defineProps({
//   msg: {
//     type: String,
//     required: true
//   }
// })
</script>

<script>
const API_URL = '//localhost:3000/readings';
export default {
  name: 'HelloWorld',
  data() {
    return {
      options: {
        chart: {
          id: 'vuechart-example'
        },
        xaxis: {
          type: 'datetime'
        }
      },
      series: [{
        name: 'Temperature',
        data: []
      }]
    }
  },
  mounted() {
    console.log(`the component is now mounted.`);
    this.axios.get(API_URL, { params: { start: '2022-10-10', end: '2022-10-17' }}).then((response) => {
      // console.log(response.data)
      this.series = [{
        data: response.data.map((reading) => {
          return [reading.timestamp, reading.temperature]
        })
      }];
    });
  }
}
</script>

<template>
  <div class="greetings">
    <div>
      <apexchart width="1024" type="line" :options="options" :series="series"></apexchart>
    </div>
    <div class="container text-center">
        <div class="row">
          <div class="col">
            <button type="button" class="btn btn-primary">1 Week</button>
          </div>
          <div class="col">
            <button type="button" class="btn btn-primary">1 Month</button>
          </div>
          <div class="col">
            <button type="button" class="btn btn-primary">All Data</button>
          </div>
        </div>
    </div>
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
