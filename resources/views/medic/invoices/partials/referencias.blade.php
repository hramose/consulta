
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><b>Tipo Documento</b></th>
                    <th><b>Numero Documento</b></th>
                    <th><b>Fecha emision</b></th>
                    <th><b>Codigo referencia</b></th>
                    <th><b>Razon</b></th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->documentosReferencia as $ref)
                <tr>
                
                    <td>{{ $ref->tipo_documento_name }}</td>
                    <td>{{ $ref->numero_documento }}</td>
                    <td>{{ $ref->fecha_emision }}</td>
                    <td>{{ $ref->codigo_referencia_name }}</td>
                    <td>{{ $ref->razon }}</td>
                    
                </tr>
                @endforeach
            
            </tbody>
        </table>
   