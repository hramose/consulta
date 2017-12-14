<template>
	
      
<div class="box box-solid">
	<div class="box-header with-border">
	  <i class="fa fa-book"></i>

	  <h3 class="box-title"><slot>Resumen</slot></h3>
   
	</div>
	<!-- /.box-header -->
	<div class="box-body summary-flex">
	  <dl class="summary-dl">
      <dt class="text-aqua" v-show="isCurrent"><h4>Medicamentos Activos</h4></dt>
      <dd v-show="isCurrent">
         <div v-for="item in summary.medicines"><span>{{ item.name }}</span></div>
        
      </dd>
      <dt class="text-aqua" v-show="summary.vital_signs.height"><h4>Signos Vitales</h4></dt>
      <dd v-show="summary.vital_signs.height">
        <div class="columns">
          <div class="column">
            <div v-show="summary.vital_signs.height"><strong >Altura: </strong>{{ summary.vital_signs.height }} </div>
            <div v-show="summary.vital_signs.weight"><strong >Peso: </strong>{{ summary.vital_signs.weight }} </div>
            <div v-show="summary.vital_signs.mass"><strong >Masa: </strong>{{ summary.vital_signs.mass }} </div>
            
          </div>
          <div class="column">
            <div v-show="summary.vital_signs.temp"><strong >Temp: </strong>{{ summary.vital_signs.temp }} </div>
            <div v-show="summary.vital_signs.respiratory_rate"><strong >F. Resp: </strong>{{ summary.vital_signs.respiratory_rate }} </div>
              <div v-show="summary.vital_signs.blood_pd"><strong >P. Arte: </strong> P.S {{ summary.vital_signs.blood_ps }} / P.D {{ summary.vital_signs.blood_pd}} </div>
            
          </div>
          <div class="column">
            <div v-show="summary.vital_signs.heart_rate"><strong >F. Cardiaca: </strong>{{ summary.vital_signs.heart_rate }} </div>
            <div v-show="summary.vital_signs.oxygen"><strong > S. de Oxíg: </strong>{{ summary.vital_signs.oxygen }} </div>
              <div v-show="summary.vital_signs.glicemia"><strong > Glicemia: </strong>{{ summary.vital_signs.glicemia }} </div>
          </div>
        </div>
       
        
       
      </dd>
      <dt class="text-aqua"><h4>Notas de padecimiento</h4></dt>
      <dd>
        <div v-show="summary.notes.reason"><strong >Razón de la visita: </strong>{{ summary.notes.reason }} </div>
        <div v-show="summary.notes.symptoms"><strong >Síntomas subjetivos: </strong>{{ summary.notes.symptoms }} </div>
        <div v-show="summary.notes.phisical_review"><strong >Exploración Física: </strong>{{ summary.notes.phisical_review }}</div>
       
      </dd>
      <dt class="text-aqua"><h4>Examen Físico</h4></dt>
      <dd>
        <div v-show="summary.exams.cardio"><strong>Cardiaco y Vascular: </strong>{{ summary.exams.cardio }} </div>
        <div v-show="summary.exams.linfatico"><strong >Sistema Linfático: </strong>{{ summary.exams.linfatico }} </div>
        <div v-show="summary.exams.osteoarticular"><strong>Osteoarticular: </strong>{{ summary.exams.osteoarticular }}</div>
        <div v-show="summary.exams.psiquiatrico"><strong>Psiquiátrico y Psicológico: </strong>{{ summary.exams.psiquiatrico }} </div>
        <div v-show="summary.exams.digestivo"><strong>Aparato Digestivo: </strong>{{ summary.exams.digestivo }} </div>
        <div v-show="summary.exams.dermatologico"><strong>Dermatológico: </strong>{{ summary.exams.dermatologico }} </div>
        <div v-show="summary.exams.otorrinolaringologico"><strong>Otorrinolaringológico: </strong>{{ summary.exams.otorrinolaringologico }} </div>
        <div v-show="summary.exams.reproductor"><strong>Aparato Reproductor: </strong>{{ summary.exams.reproductor }} </div>
        <div v-show="summary.exams.urinario"><strong>Aparato Urinario: </strong>{{ summary.exams.urinario }} </div>
        <div v-show="summary.exams.neurologico"><strong>Neurológico: </strong>{{ summary.exams.neurologico }}</div>
        <div v-show="summary.exams.pulmonar"><strong>Pulmonar o Respiratorio: </strong>{{ summary.exams.pulmonar }}</div>
        
      </dd>
      <dt class="text-aqua"><h4>Pruebas Diagnósticas</h4></dt>
      <dd>
         <div v-for="item in summary.labexams"><span>{{ item.name }}</span></div>
        
      </dd>
      <dt class="text-aqua"><h4>Diagnóstico</h4></dt>
      <dd>
         <div v-for="item in summary.diagnostics"><span>{{ item.name }}</span></div>
        
      </dd>
       <dt class="text-aqua"><h4>Tratamiento</h4></dt>
      <dd>
         <div v-for="item in summary.treatments">
            <div><strong>{{ item.name }}:</strong></div>
            <div>{{ item.comments }}</div>
         </div>
         <div><strong v-show="summary.medical_instructions">Recomendaciones  Médicas: </strong>{{ summary.medical_instructions }} </div>
      </dd>
    </dl>
    <dl class="summary-dl" v-show="isCurrent">
	    <dt class="text-aqua"><h4>Historial</h4></dt>
	    <dd>
        <div v-show="summary.history.allergies.length"><strong>Alergias: </strong><div v-for="item in summary.history.allergies">- {{ item.name }}</div></div>
	      <div v-show="summary.history.pathologicals.length"><strong>Ant. Patológicos: </strong><div v-for="item in summary.history.pathologicals">- {{ item.name }} </div> </div>
	      <div v-show="summary.history.no_pathologicals.length"><strong>Ant. No Patológicos: </strong><div v-for="item in summary.history.no_pathologicals">- {{ item.name }} </div> </div>
	      <div v-show="summary.history.heredos.length"><strong>Ant. Heredofamiliares: </strong><div v-for="item in summary.history.heredos">- {{ item.name }} </div></div>
	      <div v-show="summary.history.ginecos.length"><strong>Ant. Gineco-obstetricios: </strong><div v-for="item in summary.history.ginecos">- {{ item.name }} </div> </div>
		   
         
	    </dd>
      
	    
	  </dl>
    
	</div>

</div>


</template>

<script>
    export default {
      props: ['history','medicines','notes','exams','diagnostics','treatments','instructions','labexams','isCurrent','vital_signs'],
      data () {
        return {
          summary: {
          	notes:{},
          	history: {
              allergies:[],
              pathologicals:[],
              no_pathologicals:[],
              heredos:[],
              ginecos:[]

            },
          	medicines: {},
            exams: {},
            labexams: [],
            diagnostics: [],
            treatments: [],
            medical_instructions:"",
            vital_signs:{},
          },
          loader:false,
          
 
        }
      },
      computed:{
         
      },
      methods: {
         
         updateSummaryAllergies(data){

         	this.summary.history.allergies = data;
         },
         updateSummaryPathologicals(data){
          this.summary.history.pathologicals = data;
         },
         updateSummaryNoPathologicals(data){
          this.summary.history.no_pathologicals = data;
         },
         updateSummaryHeredos(data){
          this.summary.history.heredos = data;
         },
         updateSummaryGinecos(data){
          this.summary.history.ginecos = data;
         },
          updateSummaryNotes(data){
         	this.summary.notes = data;
         },
          updateSummaryExams(data){
          this.summary.exams = data;
         },
          updateSummaryLabexams(data){
            if(this.isCurrent)
              this.summary.labexams.push(data);
         },
          updateSummaryMedicines(data){
          this.summary.medicines = data;
         },
          updateSummaryDiagnostics(data){
          this.summary.diagnostics = data;
         },
         updateSummaryTreatments(data){
          this.summary.treatments = data;
         },
          updateSummaryInstructions(data){
          this.summary.medical_instructions = data;
         },
         updateSummaryVitalSigns(data){
         	this.summary.vital_signs = data;
         },
        
          
     
      },
      created () {
           console.log('Component ready. summary')

           bus.$on('actSummaryAllergies', this.updateSummaryAllergies);
           bus.$on('actSummaryPathologicals', this.updateSummaryPathologicals);
           bus.$on('actSummaryNoPathologicals', this.updateSummaryNoPathologicals);
           bus.$on('actSummaryHeredos', this.updateSummaryHeredos);
           bus.$on('actSummaryGinecos', this.updateSummaryGinecos);
           bus.$on('actSummaryNotes', this.updateSummaryNotes);
           bus.$on('actSummaryExams', this.updateSummaryExams);
           bus.$on('actSummaryLabexams', this.updateSummaryLabexams);
           bus.$on('actSummaryMedicines', this.updateSummaryMedicines);
           bus.$on('actSummaryDiagnostics', this.updateSummaryDiagnostics);
           bus.$on('actSummaryTreatments', this.updateSummaryTreatments);
           bus.$on('actSummaryInstructions', this.updateSummaryInstructions);
            bus.$on('actSummaryVitalSigns', this.updateSummaryVitalSigns);

           if(this.history.allergies)
           {
            
                this.summary.history.allergies = this.history.allergies;
                 
           }
           if(this.history.pathologicals)
           {
            
                this.summary.history.pathologicals = this.history.pathologicals;
                 
           }
           if(this.history.nopathologicals)
           {
            
                this.summary.history.no_pathologicals = this.history.nopathologicals;
                 
           }
           if(this.history.heredos)
           {
            
                this.summary.history.heredos = this.history.heredos;
                 
           }
           if(this.history.ginecos)
           {
            
                this.summary.history.ginecos = this.history.ginecos;
                 
           }
           
           this.summary.notes = this.notes;
           this.summary.medicines = this.medicines;
           this.summary.exams = this.exams;
           this.summary.labexams = this.labexams;
           this.summary.diagnostics = this.diagnostics;
           this.summary.treatments = this.treatments;
           this.summary.medical_instructions = this.instructions;
           if(this.vital_signs)
           {
           this.summary.vital_signs = this.vital_signs;
           }
      }
    }
</script>
