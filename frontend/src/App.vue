<template>
  <div id="app">
    <Hello/>
    <YoutubeWindow/>
  </div>
</template>

<script>
import Hello from './components/Hello.vue';
import YoutubeWindow from './components/YoutubeWindow.vue';

export default {
  name: 'App',
  components: {
    Hello,
    YoutubeWindow,
  },
};
</script>

<style>
html, body {
  min-width: 100%;
  min-height: 100%;
  margin: 0;
}

html {
  background-image: url('~@/assets/background.jpg');
  background-size: cover;
  background-position: center;
}

#app {
  background: rgba(0, 0, 0, .4);
  max-width: 820px;
  margin: 0 auto;
  padding: 0;
  box-sizing: border-box;
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #fff;
}

@media (min-height: 720px) {
  #app {
    padding-top: 90px;
  }
  .hello {
    margin-bottom: 80px;
  }
}

</style>
