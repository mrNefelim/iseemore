<template>
    <div>
        <div class="youtube-window" v-if="videoId">
            <youtube :video-id="videoId" @ready="ready" @error="error" :player-vars="{ autoplay: 1 }"
                     style="width: 100%;"></youtube>
            <button class="button" @click="nextVideo">Следующее</button>
        </div>
        <div class="go-away" v-else>
            <h4>Если ты готов - жми на кнопку</h4>
            <button class="button" @click="getVideo">В путь</button>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'YoutubeWindow',
    data() {
      return {
        player: {},
        videoId: '',
        id: 0,
      };
    },
    methods: {
      ready(event) {
        this.player = event;
        this.getVideo();
      },
      error() {
        fetch('/api/delete/' + this.id);
        this.nextVideo();
      },
      nextVideo() {
        this.getVideo();
        this.player.playVideo();
      },
      getVideo() {
        fetch('/api/video/' + this.id).then((response) => {
          response.json().then((data) => {
            this.videoId = data.url;
            this.id = data.id;
          });
        });
      },
    },
  };
</script>

<style scoped>
    .go-away {
        padding: 40px;
    }

    .button {
        display: block;
        margin: 0 auto;
        padding: 10px 20px;
        cursor: pointer;
        background: transparent;
        border: 3px solid #fff;
        outline: none;
        border-radius: 3px;
        color: #fff;
        font-weight: lighter;
        text-transform: uppercase;
        font-size: 16px;
    }
</style>