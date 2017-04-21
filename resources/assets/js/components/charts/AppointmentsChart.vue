<template>
  <transition name="fade">
  	<div v-bind:class="'alert alert-' + type" v-show="show">
		<slot></slot>
		<span class="alert-close" v-show="important" @click="show= false">x</span>
	</div> 
  </transition>
	
</template>

<script>
    import { Bar } from 'vue-chartjs'
    
	export default PieChart.extend({
		  ready () {
		    // Overwriting base render method with actual data.
		    this.render({
		      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		      datasets: [
		        {
		          label: 'GitHub Commits',
		          backgroundColor: '#f87979',
		          data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
		        }
		      ]
		    })
		  }
		})
</script>