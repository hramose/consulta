<template>
	
      
<div class="box box-solid">
	<div class="box-header with-border">
	  <i class="fa fa-book"></i>

	  <h3 class="box-title"><slot>Resumen</slot></h3>
   
	</div>
	<!-- /.box-header -->
	<div class="box-body">
	  <dl class="summary-dl">
	    <dt class="text-aqua"><h4>Historial</h4></dt>
	    <dd>
        <div><strong v-show="activesAlergias.length">Alergias: </strong><span v-for="item in activesAlergias"  v-text="item.value"></span></div>
	      <div><strong v-show="activesPatologicos.length">Ant. Patológicos: </strong><span v-for="item in activesPatologicos"> {{ item.name }} : {{ item.value }} - </span> </div>
	      <div><strong v-show="activesNoPatologicos.length">Ant. No Patológicos: </strong><span v-for="item in activesNoPatologicos"> {{ item.name }} : {{ item.value }} - </span> </div>
	      <div><strong v-show="activesHeredo.length">Ant. Heredofamiliares: </strong><span v-for="item in activesHeredo"> {{ item.name }} : {{ item.value }} - </span></div>
	      <div><strong v-show="activesGineco.length">Ant. Gineco-obstetricios: </strong><span v-for="item in activesGineco"> {{ item.name }} : {{ item.value }} - </span> </div>
		   
         
	    </dd>
      <dt class="text-aqua"><h4>Medicamentos Activos</h4></dt>
      <dd>
         <div v-for="item in summary.medicines"><span>{{ item.name }}</span></div>
        
      </dd>
	    <dt class="text-aqua"><h4>Notas de padecimiento</h4></dt>
	    <dd>
	    	<div><strong v-show="summary.notes.reason">Razón de la visita: </strong>{{ summary.notes.reason }} </div>
	    	<div><strong v-show="summary.notes.symptoms">Síntomas subjetivos: </strong>{{ summary.notes.symptoms }} </div>
	    	<div><strong v-show="summary.notes.phisical_review">Exploración Física: </strong>{{ summary.notes.phisical_review }}</div>
       
	    </dd>
	    <dt class="text-aqua"><h4>Examen Físico</h4></dt>
	    <dd>
	    	<div><strong v-show="summary.exams.cardio">Cardiaco y Vascular: </strong>{{ summary.exams.cardio }} </div>
        <div><strong v-show="summary.exams.linfatico">Sistema Linfático: </strong>{{ summary.exams.linfatico }} </div>
        <div><strong v-show="summary.exams.osteoarticular">Osteoarticular: </strong>{{ summary.exams.osteoarticular }}</div>
        <div><strong v-show="summary.exams.psiquiatrico">Psiquiátrico y Psicológico: </strong>{{ summary.exams.psiquiatrico }} </div>
        <div><strong v-show="summary.exams.digestivo">Aparato Digestivo: </strong>{{ summary.exams.digestivo }} </div>
        <div><strong v-show="summary.exams.dermatologico">Dermatológico: </strong>{{ summary.exams.dermatologico }} </div>
        <div><strong v-show="summary.exams.otorrinolaringologico">Otorrinolaringológico: </strong>{{ summary.exams.otorrinolaringologico }} </div>
        <div><strong v-show="summary.exams.reproductor">Aparato Reproductor: </strong>{{ summary.exams.reproductor }} </div>
        <div><strong v-show="summary.exams.urinario">Aparato Urinario: </strong>{{ summary.exams.urinario }} </div>
        <div><strong v-show="summary.exams.neurologico">Neurológico: </strong>{{ summary.exams.neurologico }}</div>
        <div><strong v-show="summary.exams.pulmonar">Pulmonar o Respiratorio: </strong>{{ summary.exams.pulmonar }}</div>
        
	    </dd>
	    <dt class="text-aqua"><h4>Diagnóstico y Tratamiento</h4></dt>
	    <dd>
         <div><strong v-show="summary.diagnostics.length">Diagnosticos </strong></div>
	    	 <div v-for="item in summary.diagnostics"><span>{{ item.name }}</span></div>
         <div><strong v-show="summary.medical_instructions">Instrucciones  Médicas: </strong>{{ summary.medical_instructions }} </div>
	    </dd>
	    
	  </dl>
	</div>

</div>


</template>

<script>
    export default {
      props: ['history','medicines','notes','exams','diagnostics','instructions'],
      data () {
        return {
          summary: {
          	notes:{},
          	history: {},
          	medicines: {},
            exams: {},
            diagnostics: [],
            medical_instructions:""
          },
          loader:false,
          defaultHistory: {
            version: "1.0",
            alergias: [{
              name: 'Alergias',
              value: ""
            }],
            patologicos:[{
              name:"Hospitalizacion Previa",
              value: ""
            },
            {
              name:"Cirugías Previas",
              value: ""
            },
            {
              name:"Diabetes",
              value: ""
            },
            {
              name: "Enfermedades Tiroideas",
              value: ""
            },
            {
              name: "Hipertensión Arterial",
              value: ""
            },
            {
              name: "Cardiopatías",
              value: ""
            },
            {
              name: "Traumatismos",
              value: ""
            },
            {
              name: "Cáncer",
              value: ""
            },
            {
              name: "Tuberculosis",
              value: ""
            },
            {
              name: "Transfusiones",
              value: ""
            },
            {
              name: "Otros (patologicos)",
              value: ""
            }],
            no_patologicos:[{
              name:"Actividad Física",
              value: ""
            },
            {
              name:"Tabaquismo",
              value: ""
            },
            {
              name:"Alcoholismo",
              value: ""
            },
            {
              name:"Uso de otras sustancias (Drogas)",
              value: ""
            },
            {
              name:"Otros (No Patológicos)",
              value: ""
            }],
            heredofamiliares:[{
               name:"Diabetes (Heredofamiliares)",
               value: ""
            },
            {
               name:"Cardiopatías (Heredofamiliares)",
               value: ""
            },
            {
               name:"Hipertensión Arterial (Heredofamiliares)",
               value: ""
            },
            {
               name:"Enfermedades Tiroideas (Heredofamiliares)",
               value: ""
            },
            {
               name:"Otros (Heredofamiliares)",
               value: ""
            }],
            gineco_obstetricios:[
            {
               name:"Fecha de primera menstruación",
               value: ""
            },
            {
               name:"Fecha de última menstruación",
               value: ""
            },
            {
               name:"Características menstruación",
               value: ""
            },
            {
               name:"Embarazos",
               value: ""
            },
            {
               name:"Cáncer Cérvico",
               value: ""
            },
            {
               name:"Cáncer Uterino",
               value: ""
            },
            {
               name:"Cáncer de Mama",
               value: ""
            },
            {
               name:"Otros (Gineco-Obstetricios)",
               value: ""
            }

            ],
        
          }
 
        }
      },
      computed:{
          activesAlergias(){

              return this.summary.history.alergias.filter(item => item.value);
          },
          activesPatologicos(){

              return this.summary.history.patologicos.filter(item => item.value);
          },
          activesNoPatologicos(){

              return this.summary.history.no_patologicos.filter(item => item.value);
          },
          activesHeredo(){

              return this.summary.history.heredofamiliares.filter(item => item.value);
          },
          activesGineco(){

              return this.summary.history.gineco_obstetricios.filter(item => item.value);
          }
      },
      methods: {
         
         updateSummaryHistory(data){
         	this.summary.history = data;
         },
          updateSummaryNotes(data){
         	this.summary.notes = data;
         },
          updateSummaryExams(data){
          this.summary.exams = data;
         },
          updateSummaryMedicines(data){
          this.summary.medicines = data;
         },
          updateSummaryDiagnostics(data){
          this.summary.diagnostics = data;
         },
          updateSummaryInstructions(data){
          this.summary.medical_instructions = data;
         }
        
          
     
      },
      created () {
           console.log('Component ready. summary')

           bus.$on('actSummaryHistory', this.updateSummaryHistory);
           bus.$on('actSummaryNotes', this.updateSummaryNotes);
           bus.$on('actSummaryExams', this.updateSummaryExams);
           bus.$on('actSummaryMedicines', this.updateSummaryMedicines);
           bus.$on('actSummaryDiagnostics', this.updateSummaryDiagnostics);
           bus.$on('actSummaryInstructions', this.updateSummaryInstructions);

           if(this.history.histories)
           {
            
                this.summary.history = JSON.parse(this.history.histories);
                 
               
           }else{
            this.summary.history = this.defaultHistory;
           }
           this.summary.notes = this.notes;
           this.summary.medicines = this.medicines;
           this.summary.exams = this.exams;
           this.summary.diagnostics = this.diagnostics;
           this.summary.medical_instructions = this.instructions;
          
      }
    }
</script>
