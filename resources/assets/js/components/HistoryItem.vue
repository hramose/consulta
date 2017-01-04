<template>
    <div class="panel box " :class="colorBox" >
        <div class="box-header with-border">
          <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#patologicos" :href="'#'+ nameSluglify(item.name)" aria-expanded="true" class="collapsed">
             {{ item.name }}
            </a>
          </h4>
        </div>
        <div :id="nameSluglify(item.name)" class="panel-collapse collapse" aria-expanded="true" >
          <div class="box-body">
            <textarea cols="30" rows="3" class="form-control" v-model="item.value"></textarea>
            <div class="form-group pull-right">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success"  @click="$emit('update')">Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>

<script>
    export default {
        props: ['item','colorBox'],
        methods: {
          nameSluglify(value) {
            value = value.replace(/^\s+|\s+$/g, ''); // trim
              value = value.toLowerCase();

              // remove accents, swap ñ for n, etc
              var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
              var to   = "aaaaaeeeeeiiiiooooouuuunc------";
              for (var i=0, l=from.length ; i<l ; i++) {
                value = value.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
              }

              value = value.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes

              return value;

            }
        }
    }
</script>