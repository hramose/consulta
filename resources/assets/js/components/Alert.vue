<template>
  <transition name="fade">
  	<div v-bind:class="'alert alert-' + type" v-show="show">
		<slot></slot>
		<span class="alert-close" v-show="important" @click="show= false">x</span>
	</div> 
  </transition>
	
</template>

<script>
	export default {
		props: {
			type: { default:'info'},
			timeout: { default: 3000},
			important:{
				type: Boolean,
				default: false
			}

		},
		data() {
			return {show: true}
		},
		mounted(){
			if(! this.important){
				setTimeout(
					() => this.show = false,
					this.timeout
				)
			}
		}
	}
</script>