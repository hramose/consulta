<template>
  <div class="photo-upload" :class="disabled ? 'disabled' : 'enabled'">
    <div class="uploader" :class="{hovering: hovering}" :style="{backgroundImage: backgroundImage}" ref="uploader">
      <span v-show="!(value || preview)" class="upload-instructions" style="color: black;">
       {{ message }}
      </span>
      <input class="file-photo" type="file" @change="handleImage" @dragenter="hovering = true"
             @dragleave="hovering = false" :disabled="disabled"/>
    </div>
  </div>
</template>

<style>
  .uploader {
    position: relative;
    overflow: hidden;
    width: 200px;
    height: 100px;
    background-color: #f3f3f3;
    background-size: contain;
    background-position: center center;
    background-repeat: no-repeat;
    border: 2px dashed #e8e8e8;
  }

  .enabled .uploader.hovering {
    background-color: #bbb;
  }

  .enabled .uploader:hover {
    background-color: skyblue;
  }

  .upload-instructions {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
    text-align: center;
  }

  .file-photo {
    position: absolute;
    width: 300px;
    height: 400px;
    top: -50px;
    left: 0;
    z-index: 2;
    opacity: 0;
  }
  .enabled .file-photo {
    cursor: pointer;
  }

  .uploader img {
    position: absolute;
    width: 100%;
    top: -1px;
    left: -1px;
    z-index: 1;
    border: none;
  }

</style>

<script>
    export default {
        //props: ['value', 'disabled'],
          props: {
            value: {
              type: String
              
            },
             message: {
              type: String,
              default: "Click o arrastra una imagen aqui para subir..."
            },
            disabled: {
              type: Boolean
              
            }
          },
        methods: {
            handleImage(event) {
                if (this.disabled) {
                  return;
                }
                let files = event.target.files;
                if (files.length === 0){
                  return;
                }
                let reader = new FileReader();
                reader.onload = (event) => {
                    this.preview = event.target.result;
                    this.$emit('input', files[0]);
                };
                reader.readAsDataURL(files[0]);

            },
            clearPreview(){
              this.preview = null;
            }
        },
        data() {
            return {
                hovering: false,
                preview: null
            }
        },
        computed: {
            backgroundImage() {
                let image = this.preview || this.value;
                if (!image) {
                    return null;
                }
                return `url('${image}')`;
            }
        },
        created(){
          bus.$on('clearImage', this.clearPreview);
        }
    }
</script>
