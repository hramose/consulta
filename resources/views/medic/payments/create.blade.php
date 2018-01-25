@extends('layouts.app')
@section('css')
    <script type="text/javascript" src="{{ env('URL_VPOS2')}}" ></script>
@endsection
@section('content')

@include('layouts/partials/header-pages',['page'=>'Realizar de pago'])

    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> {{ config('app.name', 'Laravel') }}
            <small class="pull-right">{{ \Carbon\Carbon::now()->toDateTimeString() }}	</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-6">
        @if(isset($purchaseOperationNumber))
          <b>Numero de operación:</b> {{ $purchaseOperationNumber }}
          <br>
          <b>Estado:</b>
            
        @endif
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    @if(isset($incomes) && $incomes)
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Cant.</th>
              <th>Description</th>
              <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($incomes as $income)
            <tr>
                <td>1</td>
                <td>{{ $income->description }}</td>
                <td>{{ money($income->amount,'$') }}</td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Payment Methods:</p>
          <img src="/img/credit/visa.png" alt="Visa">
          <img src="/img/credit/mastercard.png" alt="Mastercard">
          <!-- <img src="/img/credit/american-express.png" alt="American Express"> -->

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; max-width:350px;">
            <img src="/img/credit/banner_payme_latam.png" alt="Payme" style="width:100%">
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
         
          <div class="table-responsive">
            <table class="table">
              <tbody>
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td>{{ money($amountTotal,'$') }}</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>{{ money($amountTotal,'$') }}</td>
              </tr>
            </tbody></table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
   
      <!-- this row will not appear when printing -->
       <div class="row no-print">
          <div class="terms-container pull-right">
              <h2>Términos y Condiciones</h2>  
                <p>Estimado Dr., al abrir su cuenta en GPS MEDICA usted respeta y acepta los términos y condiciones de este contrato:</p>
                <ol>
                <li style="font-weight: 400;"><span style="font-weight: 400;">El contenido del consiguiente documento digital, establece de manera inamovible los Términos y Condiciones, pactadas para el usuario, respecto al uso del APP o PROGRAMAS propiedad exclusiva de GPS MEDICA, como  las páginas de Internet, links y demás que GPS MEDICA dispone para usted señor usuario.</span></li>
                <li style="font-weight: 400;"><b>GPS MEDICA</b><span style="font-weight: 400;"> es una herramienta web y aplicación de programación adaptada para computadoras y dispositivos móviles la cual facilita la búsqueda, visualización y reservación de una cita médica entre el paciente y el profesional de medicina u odontología. En ningún momento GPS MEDICA será una  herramienta de marketing personalizado, únicamente es una conexión entre el potencial consumidor y el oferente. </span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">GPS MEDICA brinda diversas funciones o valores agregados en la plataforma dirigidos a enriquecer y facilitar los procesos derivados de una consulta entre el profesional citado y el paciente, nunca en ningún momento es o deberá ser considerado una herramienta de marketing con fines de promoción individual o grupal.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Todo material contenido, llamasen publicaciones digitales, videos, links, páginas web de GPS MEDICA tienen como único fin,  proveer a sus USUARIOS, el completo y ágil acceso a todos los servicios y productos  de la marca GPS MEDICA.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">La utilización de las herramientas, páginas, apps, links o cualquier otro medio electrónico o documental, faculta y otorga a quien usa la plataforma de GPS MEDICA la condición de USUARIO de las mismas e implícitamente la aceptación consensuada y sin reservas de todas y cada una de las disposiciones incluidas en este documento electrónico, dicha aceptación será tomada como aceptada para todos los efectos legales, en el momento en que el USUARIO acceda a nuestros servicios y sitios. Por lo que en ningún momento podrá el USUARIO alegar el desconocimiento de las advertencias legales que este aviso y documento electrónico establece.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">La utilización de toda la información y utilización de los productos de GPS MEDICA se encuentra sometida, al fuerte escrutinio del personal de GPS MEDICA, como así apegada en su totalidad a  las Leyes y Legislación de la República de Costa Rica, para sitios como los aquí contratados. </span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">GPS MEDICA se reserva unilateralmente el derecho absoluto  a modificar los Términos Y condiciones en cualquier tiempo y sin previa notificación. Dichas modificaciones serán efectivas a partir del momento en que queden disponibles al USUARIO en nuestra plataforma o sitio electrónico. NUNCA se aplicará de manera retroactiva en perjuicio del Usuario dichas modificaciones. </span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Así mismo GPS MEDICA se reserva el derecho de realizar modificaciones a sus productos o servicios ofrecidos, sin previa autorización o comunicación al usuario.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">GPS MEDICA no será responsable de ninguna manera, por  omisiones u errores, que se puedan presentar dentro de los contenidos electrónicos, tanto en sitios Web, Páginas electrónicas, links, documentos en soporte físico o cualquier otro, y por lo tanto no se compromete a la actualización inmediata de la información recibida o contenida dentro de sus sitios. Dichas omisiones serán subsanadas previa comunicación por escrito o vía correo electrónico a los personeros de la empresa, por parte del USUARIO (S) afectados.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">GPS MEDICA podrá en cualquier momento de la relación comercial, negar, suspender e inclusive dar por terminado el contrato de manera unilateral, sin necesidad de previo aviso, al usuario, si este incumple en una sola de las condiciones pactadas en el contrato, o la violación a alguna de las cláusulas en este documento electrónico establecidas sin limitación. </span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Consecuentemente GPS MEDICA no garantiza al USUARIO que incumpla sus obligaciones la utilidad, disponibilidad y continuidad del funcionamiento de su cuenta, así Servicios y Productos  contratados según sus necesidades.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">La inclusión en esta plataforma será una decisión libre, voluntaria y exclusiva del médico u odontólogo u profesional afín, quien actúa bajo su propia voluntad y responsabilidad cumpliendo responsablemente con las obligaciones que de su actividad se deriven, por lo tanto GPS MEDICA no será responsable nunca por malos tratos, omisiones, mala praxis, o algún tipo de responsabilidad que se pueda derivar entre el usuario final(cliente) y el oferente (profesional que entrega servicio), cualquier reclamo o conflicto entre el usuario cliente y el profesional encargado, deberá de ser dirimido entre las partes contratantes, GPS MEDICA nunca será responsable.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Esta aplicación o herramienta web, no garantiza de ninguna manera el incremento en el volumen de atención de pacientes del médico Marketing Personalizado o Grupal, sin embargo, facilita de forma importante la exposición de profesionales en esta área, así como la búsqueda, la ubicación y la reservación de una cita por parte del usuario.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">GPS MEDICA le brinda al médico titulado como General o Especialista u Odontólogo, herramientas y funciones de gran utilidad para sus labores rutinarias. </span>
                <ol>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Expediente Clínico</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Panel de Facturación y Reportes</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Agenda médica y calendarización propia de pacientes</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Sistema de ubicación gps.</span></li>
                </ol>
                </li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">La responsabilidad del manejo de los datos considerados información personal y de salud entre el profesional a cargo y el usuario, deberá de ser tratada bajo los parámetros establecidos por los respectivos Colegios Profesionales, debiendo el profesional hacer uso debido de la información del usuario bajo su completa responsabilidad, y respetando en todo caso el Secreto Profesional, como así asegurar el correcto manejo y almacenamiento de toda la información contenida en nuestra plataforma.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">GPS Médica ha facilitado la creación de un “Expediente Universal” entendiendo por éste la información clínico médica del usuario en un formato electrónico de acceso médico, la cual puede ser puesta al servicio de otros médicos con acceso exclusivamente autorizado por el paciente a través de un código que él mismo brindará. En tal caso, GPS Médica no tendrá ningún grado de responsabilidad por el uso o manejo que se le dé por parte de los médicos o un tercero a la información personal del paciente. </span></li>
                <li style="font-weight: 400;">GPS Médica podrá generar el COBRO de un monto determinado por el uso que se le dé, por parte del médico o especialista en el área de la salud, a este sitio web y aplicación móvil, ya sea en herramientas como Expediente Clínico, Agenda Virtual, Citas en línea o Software para Clínicas, entre otros.</li>
                <li style="font-weight: 400;"><span style="font-weight: 400;"><span style="font-weight: 400;"><span style="font-weight: 400;">En cuanto al Expediente Clínico, el usuario médico u especialista de la salud, que desee hacer uso de este expediente durante su consulta con los pacientes, deberá cancelar el monto indicado de forma previa, según el paquete escogido. Las opciones brindadas son:</span></span></span>
                <ul style="margin-left: 45px;">
                <li style="font-weight: 400;"><span style="font-weight: 400;">1 mes: $ 10</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">3 meses: $25</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">6 meses: $50</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">1 año: $100</span></li>
                </ul>
                <p><span style="font-weight: 400;">En caso de darse de baja, o no desear utilizar el Expediente Clínico, simplemente deberá no pagar la mensualidad correspondiente y el sistema inhabilitará su acceso al mismo.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">El médico entiende y acepta que en caso de retraso o incumplimiento de pago en el período respectivo,  él será el único responsable por la  inactivación del sistema y los posibles inconvenientes que esto le pudiera ocasionar. Una vez efectuada la cancelación respectiva podrá retomar el acceso a la plataforma y con derecho de uso de las funciones contratadas.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Para seguridad del usuario médico, GPS Médica tiene la opción de transformar los Expedientes Clínicos de sus pacientes en un archivo PDF, el cual a decisión del usuario podrá almacenar en su computadora en caso deseado a modo de respaldo personal.</span></li>
                <li style="font-weight: 400;">GPS Médica podrá modificar el monto a cobrar por el servicio respectivo según su conveniencia no existiendo ningún tipo de restricción que limite de hacerlo en el momento deseado. Sin embargo, en caso de hacerlo, GPS Medica informará con anticipación en la plataforma sobre los cambios establecidos para conocimiento de los usuarios.</li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">La cancelación del monto comisionado por los pacientes atraídos a la plataforma, a través de estrategias publicitarias, u otras, las cuales que hayan permitido la reservación de Citas en Línea, deberá realizarse los días 1 ero de cada mes. Se concederá un período de gracia de 15 días posterior a la fecha de pago. En caso de incumplimiento, a partir del día 16 de cada mes se inactivará la posibilidad de búsqueda del médico en la plataforma, así como el acceso a otras funciones o servicios alojados en la misma.</span></li>
                <li style="font-weight: 400;">GPS Médica dará trazabilidad a los usuarios de la plataforma con el afán de validar la información generada por el sistema, así como retroalimentar al médico desde la perspectiva general de sus pacientes.</li>
                <li><span style="font-weight: 400;">Este contrato puede ser rescindido a voluntad por cualquiera de las partes en el momento que lo desee a través de la exclusión propia de la plataforma, siempre y cuando se cancelen las obligaciones económicas adquiridas durante la ejecución del mismo. Para la cancelación de cualquier adeudo puede comunicarse a la dirección de correo electrónico: </span><a href="mailto:info@gpsmedica.com"><span style="font-weight: 400;">info@gpsmedica.com</span></a><span style="font-weight: 400;">.</span></li>
                <li><span style="font-weight: 400;">Dada la naturaleza de Internet, en Costa Rica GPS MEDICA, en ningún momento garantiza el servicio de internet como acceso a sus sitios, ya que el internet es provisto por terceros y no por GPS MEDICA. Por lo tanto GPS MEDICA no tendrá responsabilidad alguna, incluyéndose los daños, perjuicios o cualquier otra que pudiera ser ocasionada por el mal servicio que del internet se derive, siendo que el único compromiso de GPS MEDICA con el cliente es mantener sus bases de datos actualizadas y en función de sus usuarios, para que estos puedan hacer libre usos de los servicios contratados.</span></li>
                <li><b>GPS MEDICA</b><span style="font-weight: 400;"> le sugiere a sus usuario no brindar su información personal a terceros (passwords y datos meramente personales), por lo que de ocurrir esta situación, será completamente responsabilidad del usuario que entrego o descuido por negligencia sus datos personales y de seguridad. Por lo que GPS MEDICA se reservara el derecho de suspender o negar el acceso a la globalidad de sus servicios a las personas que hagan un mal uso de la información, por lo que el usuario exonera a GPS MEDICA de cualquier acción civil, administrativa o penal que se pueda producir por el mal uso de su información tratada de manera negligente o irresponsable por parte del usuario.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">GPS MEDICA no será de ninguna manera responsable por la presencia de virus u otros elementos lesivos en los Servicios y Productos prestados a través de sus plataformas, que puedan producir alteraciones, malas funcionabilidad en el sistema informático o documentos electrónicos de los usuarios. Así tampoco por el uso o imposibilidad de uso de las páginas, Servicios y Productos o cualquiera de sus partes, o de sus links, plataforma, incluyendo cualquier pérdida de programas o información, producidas con el fallo de los servicios de internet provista por terceras personas (ICE, MOVISTAR, CLARO, TELEFONICA, entre otros).</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Todos los conflictos o controversias que se puedan generar durante la relación entre las partes, su ejecución, incumplimiento, interpretación, se deberá resolver mediante un Centro de </span><b>RESOLUCIÓN ALTERNA DE CONFLICTOS</b><span style="font-weight: 400;"> o profesional competente, dándose absoluta prioridad a este tipo de resolución y en segunda instancia acudirán a los Tribunales de Justicia del país.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Toda información contenida en este apartado, Términos y Condiciones, como así cualquier otra donde participen las partes, no podrá ser cedido, traspasado de manera total o parcial a terceras personas, ya que se refuta el mismo como bilateral entre las partes contratantes.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">GPS MEDICA no será responsable del pago de impuestos o cargas Tributarias, sociales, por concepto de patentes, o cualquier otra que se pueda generar; en función de la labor profesional de cada Usuario afiliado con GPS MEDICA 1.0 o versiones subsecuentes, siendo así TOTAL y ABSOLUTA responsabilidad de cada profesional, que utiliza GPS MEDICA, de mantener al día sus obligaciones de carácter Tributario, Social, Municipales entre otras, que se puedan generar en función de sus labores y los servicios brindados a través de nuestra herramienta GPS MEDICA en cualquiera de sus versiones.</span></li>
                <li style="font-weight: 400;"><span style="font-weight: 400;">Todo reclamo a la funcionabilidad del sistema o sus plataformas, deberá de ser tratado por escrito, y será mediante el  correo electrónico </span><a href="mailto:info@gpsmedica.com"><span style="font-weight: 400;">info@gpsmedica.com</span></a><span style="font-weight: 400;">, único medio válido a los efectos de presentación de reclamos por parte de los usuarios. Pero deberán de manera obligatoria comunicarse al teléfono número 8829-1001 para confirmar la entrega del mensaje, como así le sea asignado un número único de caso, para documentar sus reclamos e inconformidades.</span></li>
                </ol>
            </div>
            <label for="terms">Aceptar Términos y Condiciones.</label>
            <input type="checkbox" name="terms" id="terms"> 
      </div>
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="https://gpsmedica.com/terminos-y-condiciones/" target="_blank">Términos y Condiciones.</a>
          <input type="checkbox" name="terms" id="terms"> Aceptar
        <form method="POST" action="#" class="alignet-form-vpos2 form-horizontal">
            
                <input class="form-control" type="hidden" name ="acquirerId" value="{{ env('ACQUIRE_ID') }}" />
                <input class="form-control" type="hidden" name ="idCommerce" value="{{ env('COMMERCE_ID') }}" />
                <input class="form-control" type="hidden" name="purchaseOperationNumber" value="{{ $purchaseOperationNumber }}" />
                <input class="form-control" type="hidden" name="purchaseAmount" value="{{ $amount }}" />
                <input class="form-control" type="hidden" name="purchaseCurrencyCode" value="{{ $purchaseCurrencyCode }}" />
                <input class="form-control" type="hidden" name="language" value="SP" />
                <input class="form-control" type="hidden" name="shippingFirstName" value="{{ $medic_name }}" />
                <input class="form-control" type="hidden" name="shippingLastName" value="--" />
                <input class="form-control" type="hidden" name="shippingEmail" value="{{ $medic_email }}" />
                <input class="form-control" type="hidden" name="shippingAddress" value="Direccion" />
                <input class="form-control" type="hidden" name="shippingZIP" value="ZIP" />
                <input class="form-control" type="hidden" name="shippingCity" value="CITY" />
                <input class="form-control" type="hidden" name="shippingState" value="STATE" />
                <input class="form-control" type="hidden" name="shippingCountry" value="CR" />
                <input class="form-control" type="hidden" name="userCommerce" value="modalprueba1" />
                <input class="form-control" type="hidden" name="userCodePayme" value="8--580--4390" />
                <input class="form-control" type="hidden" name="descriptionProducts" value="{{ $description }}" />
                <input class="form-control" type="hidden" name="programmingLanguage" value="PHP" />
                <input class="form-control" type="hidden" name="reserved1" value="Valor Reservado ABC" />
                <input class="form-control" type="hidden" name="reserved2" value="{{ $incomesIds }}" />
                
                <input class="form-control" type="hidden" name="purchaseVerification" value="{{ $purchaseVerification }}" />
                <input type="button" onclick="javascript:AlignetVPOS2.openModal('https://integracion.alignetsac.com/')" value="Realizar pago" class="btn btn-success pull-right btn-VPOS">
              
                <!-- <button type="submit" class="btn btn-success btn-sm">Pagar</button> -->
              </form>
            <button class="btn btn-success btn-sm btn-blank pull-right">Realizar pago</button>
        </div>
      </div>
    @endif
    </section>
	
		
@endsection
@section('scripts')
<script>
function printComprobante() {
            window.print();
        }
termscheckboxprepare();
function termscheckboxprepare() {

     if($('#terms').is(':checked')){
        $('.btn-blank').hide();
         $('.btn-VPOS').show();
      }else{
           $('.btn-blank').show();
         $('.btn-VPOS').hide();
      }
}
    
  $('#terms').click(function (e) {
      termscheckboxprepare();
  })

  $('.btn-blank').click(function (e) {
      alert('Aceptar terminos y condiciones para continuar con el proceso de pago')
  });
</script>
@endsection

