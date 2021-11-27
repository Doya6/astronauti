<template>
  <v-card v-show="dialog" class="dialog_card" :color="options.color" :width="options.width" :height="options.height">
    <v-card-title class="px-3 py-2">
      {{ title }}
    </v-card-title>
    <v-card-text
      class="px-3 py-2 black--text"
      v-text="message"
    ></v-card-text>
    <v-card-actions class="pt-3">
      <v-spacer></v-spacer>
      <v-btn
        v-if="!options.noconfirm"
        class="body-2 font-weight-bold"
        text
        @click.native="cancel"
        >{{ options.btn_1 }}</v-btn
      >
      <v-btn
        class="body-2 font-weight-bold"
        text
        @click.native="agree"
        >{{ options.btn_2 }}</v-btn
      >
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: "MsgBox",
  data() {
    return {
      dialog: false,
      resolve: null,
      reject: null,
      message: null,
      title: null,
      options: {
        btn_1: "Cancel", 
        btn_2: "OK", 
        color: "grey lighten-3",
        width: 400,
        height: 200,
        noconfirm: false,
      },
    };
  },

  methods: {
    open(title, message, options) {
      this.dialog = true;
      this.title = title;
      this.message = message;
      this.options = Object.assign(this.options, options);
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },
    agree() {
      this.resolve(true);
      this.dialog = false;
    },
    cancel() {
      this.resolve(false);
      this.dialog = false;
    },
  },
};
</script>

<style scopped>
.dialog_card{
  top: 25%;
  left: 50%;
  transform: translate(-50%);
  z-index: 1000;
}
</style>